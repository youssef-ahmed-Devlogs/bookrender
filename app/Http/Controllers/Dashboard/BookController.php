<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Chapter;
use App\Models\Project;
use App\Models\Setting;
use App\Services\BookService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

class BookController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['reached-maximum-books'])->only(['store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::first();
        $books = Project::where('user_id', auth()->user()->id)
            ->when(request()->get('search'), function ($query, $value) {
                $query->where('title', 'LIKE', "%{$value}%");
                $query->orWhere('second_title', 'LIKE', "%{$value}%");
                $query->orWhere('description', 'LIKE', "%{$value}%");
                $query->orWhere('author', 'LIKE', "%{$value}%");
            })
            ->get();

        return view('dashboard.books.index', [
            'settings' => $settings,
            'books' => $books,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        session([
            'project_id' => null,
            'generated_content' => []
        ]);

        return view('dashboard.books.create');
    }

    // The request example
    //  [
    //   "title" => "The good boy"
    //   "author" => "Mr. Joo"
    //   "description" => "boy life"
    //   "treem_size" => "5" x 8"" // the trim size
    //   "page_count" => "180"
    //   "format" => "PDF"
    //   "bleed_file" => "Yes"
    //   "category" => "Fantasy"
    //   "chapters" => "Chapter 1,Chapter 2,Chabter 3"
    //   "text_style" => "A"
    //   "font_size" => "14"
    //   "add_page_num" => "Yes"
    //   "book_intro" => "Yes"
    //   "copyright_page" => "Yes"
    //   "table_of_contents" => "Yes"
    // ]

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            // Clear any existing session data
            session([
                'project_id' => null,
                'generated_content' => []
            ]);

            $user = auth()->user();
            $bookService = new BookService();

            // Generate book content using the improved service
            $generatedContent = $bookService->generateBookContent($request->all());

            if (empty($generatedContent)) {
                return redirect()->route('dashboard.books.create')
                    ->with('error', 'Failed to generate book content. Please try again.');
            }

            // Calculate word count for quota checking
            $totalWordCount = 0;
            foreach ($generatedContent as $section) {
                $totalWordCount += str_word_count(strip_tags($section['content']));
            }

            // Check quota before creating the project
            $subscription = $user->subscriptions->first();
            $wordLimit = $subscription->plan->word_number ?? 0;
            $usedWords = $subscription->used_words ?? 0;
            $remainingWords = $wordLimit - $usedWords;

            if ($totalWordCount > $remainingWords) {
                return redirect()->route('dashboard.books.index')
                    ->with('error', 'Your remaining word limit is not enough to generate this content. Required: ' . $totalWordCount . ' words, Available: ' . $remainingWords . ' words.');
            }

            // Create the project
            $project = $bookService->create($request);

            // Update subscription usage
            $subscription->used_words = $usedWords + $totalWordCount;
            $subscription->used_books += 1;
            $subscription->save();

            // Store generated content in session for preview
            session([
                'project_id' => $project->id,
                'generated_content' => $generatedContent
            ]);

            // Save chapters to database
            foreach ($generatedContent as $chapterData) {
                Chapter::create([
                    'project_id' => $project->id,
                    'title' => $chapterData['title'],
                    'content' => $chapterData['content'],
                    'type' => $chapterData['type'] ?? 'chapter',
                    'chapter_number' => $chapterData['chapter_number'] ?? null,
                ]);
            }

            if ($request->submit_type === 'save_close') {
                return redirect()->route('dashboard.books.index')
                    ->with('success', 'Book created successfully!');
            }

            return redirect()->route('dashboard.showpredict', ['project_id' => $project->id])
                ->with('success', 'Book generated successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating book: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Provide more specific error messages
            $errorMessage = 'An error occurred while creating the book. Please try again.';
            if (str_contains($e->getMessage(), 'Failed to generate') || str_contains($e->getMessage(), 'SSL connection timeout')) {
                $errorMessage = 'Unable to connect to AI service. Please check your internet connection and try again. If the problem persists, the AI service may be temporarily unavailable.';
            } elseif (str_contains($e->getMessage(), 'cURL error 28')) {
                $errorMessage = 'Connection timeout while generating content. Please try again with a stable internet connection.';
            } elseif (str_contains($e->getMessage(), 'word limit')) {
                $errorMessage = 'Insufficient word quota to generate this book. Please upgrade your plan or reduce the content.';
            } elseif (str_contains($e->getMessage(), 'Both primary and fallback')) {
                $errorMessage = 'AI service is currently unavailable. Please try again in a few minutes.';
            }

            return redirect()->route('dashboard.books.create')
                ->with('error', $errorMessage);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $project = Project::with('chapters')->findOrFail($id);

        $settings = Setting::first();

        // Exclude special sections from being displayed in the editor
        $specialSections = ['Book Introduction', 'Copyright Page', 'Table of Contents'];
        $filteredChapters = $project->chapters->whereNotIn('title', $specialSections);

        $isPreview = $request->has('preview');

        session()->put('last_book', $id);

        return view('dashboard.books.editor', [
            'project' => $project,
            'chapters' => $filteredChapters,
            'settings' => $settings,
            'isPreview' => $isPreview
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Project::findOrFail($id);
        return view('dashboard.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request->all();
        $request->validate([
            'author' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'second_title' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        $book = Project::findOrFail($id);

        $image = $request->file('image') ?? $book->image;

        if ($image != $book->image) {
            $imageName = $image->store('uploads/books', 'public');
        } else {
            $imageName = $book->image;
        }

        $book->update([
            'user_id' => auth()->user()->id,
            'image' => $imageName,
            'author' => $request->author,
            'title' => $request->title,
            'second_title' => $request->second_title,
            'description' => $request->description,
        ]);
        return redirect()->route('dashboard.books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Project::with('chapters')->findOrFail($id);

        // Delete the cover image if it exists
        if ($book->image && Storage::disk('public')->exists('books/' . $book->image)) {
            Storage::disk('public')->delete('books/' . $book->image);
        }

        // Delete associated chapter content if any
        foreach ($book->chapters as $chapter) {
            // Assuming chapters have a method or property to get their file path
            // If not, this logic needs to be adjusted based on how chapters are stored.
            // For now, we'll just delete the chapter record.
            $chapter->delete();
        }

        $book->delete();

        $url = url()->previous();

        return redirect()->to($url . '#recent')->with('success', 'Book and all its assets have been deleted.');
    }




    public function exportBook($bookId)
    {
        $project = Project::with('chapters')->findOrFail($bookId);

        // Add authorization check to ensure only the owner can export
        if (auth()->id() !== $project->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Get chapters ordered by type and chapter_number
        $chapters = $project->chapters()
            ->orderByRaw("CASE 
                WHEN type = 'introduction' THEN 1 
                WHEN type = 'copyright' THEN 2 
                WHEN type = 'table_of_contents' THEN 3 
                WHEN type = 'chapter' THEN 4 
                ELSE 5 END")
            ->orderBy('chapter_number')
            ->get();

        // Define size mappings to match editor dimensions
        $sizeMap = [
            '5" x 8"' => ['w' => 5, 'h' => 8, 'css' => '5in 8in'],
            '6" x 9"' => ['w' => 6, 'h' => 9, 'css' => '6in 9in'],
            '7" x 10"' => ['w' => 7, 'h' => 10, 'css' => '7in 10in'],
            '7" x 10.5"' => ['w' => 7, 'h' => 10.5, 'css' => '7in 10.5in'],
        ];

        $dimensions = $sizeMap[$project->treem_size] ?? ['w' => 6, 'h' => 9, 'css' => '6in 9in'];

        if ($project->format === 'Word') {
            return $this->exportToWord($project, $chapters, $dimensions);
        } else {
            return $this->exportToPdf($project, $chapters, $dimensions);
        }
    }

    private function exportToWord($project, $chapters, $dimensions)
    {
        $phpWord = new PhpWord();

        // Set document properties to match editor
        $properties = $phpWord->getDocInfo();
        $properties->setCreator($project->author);
        $properties->setTitle($project->title);
        $properties->setDescription($project->description);

        // Configure page settings to match exact dimensions and editor margins
        $sectionStyle = [
            'pageSizeW' => Converter::inchToTwip($dimensions['w']),
            'pageSizeH' => Converter::inchToTwip($dimensions['h']),
            'marginLeft' => Converter::inchToTwip(0.5),   // Match editor margins
            'marginRight' => Converter::inchToTwip(0.5),  // Match editor margins
            'marginTop' => Converter::inchToTwip(0.75),   // Match editor margins
            'marginBottom' => Converter::inchToTwip(1),   // Match editor margins
            'headerHeight' => Converter::inchToTwip(0.3),
            'footerHeight' => Converter::inchToTwip(0.3),
        ];

        // Add cover page if image exists
        if ($project->image && file_exists(public_path('books/' . $project->image))) {
            $coverSection = $phpWord->addSection(array_merge($sectionStyle, ['breakType' => 'nextPage']));
            $coverSection->addImage(
                public_path('books/' . $project->image),
                [
                    'width' => Converter::inchToPoint($dimensions['w'] - 1.5),
                    'height' => Converter::inchToPoint($dimensions['h'] - 2),
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                ]
            );
        }

        // Title page with exact styling from editor
        $titleSection = $phpWord->addSection(array_merge($sectionStyle, ['breakType' => 'nextPage']));

        // Main title - matching editor h1 style
        $titleSection->addText(
            $project->title,
            [
                'name' => 'Arial',
                'size' => 32, // Matches editor font-size: 32px
                'bold' => true,
                'color' => '000000'
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'spaceBefore' => Converter::pointToTwip(24),
                'spaceAfter' => Converter::pointToTwip(15)
            ]
        );

        // Subtitle
        if ($project->second_title) {
            $titleSection->addText(
                $project->second_title,
                [
                    'name' => 'Arial',
                    'size' => 24,
                    'italic' => true,
                    'color' => '555555'
                ],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => Converter::pointToTwip(20)]
            );
        }

        // Author
        $titleSection->addText(
            "By: " . $project->author,
            [
                'name' => 'Arial',
                'size' => 16,
                'color' => '333333'
            ],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => Converter::pointToTwip(30)]
        );

        // Description
        if ($project->description) {
            $titleSection->addText(
                $project->description,
                [
                    'name' => 'Arial',
                    'size' => (int)$project->font_size,
                    'color' => '666666'
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
                    'spaceBefore' => Converter::pointToTwip(20)
                ]
            );
        }

        // Process chapters with exact HTML content from editor
        foreach ($chapters as $chapter) {
            $section = $phpWord->addSection(array_merge($sectionStyle, ['breakType' => 'nextPage']));

            // Add page numbers if requested
            if ($project->add_page_num == 'Yes') {
                $footer = $section->addFooter();
                $footer->addPreserveText(
                    'Page {PAGE} of {NUMPAGES}',
                    ['name' => 'Arial', 'size' => 10],
                    ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
                );
            }

            // Convert HTML content to Word format while preserving styling
            try {
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $chapter->content, false, false);
            } catch (\Exception $e) {
                // Fallback: add as plain text with basic formatting
                $section->addText(
                    strip_tags($chapter->content),
                    [
                        'name' => 'Arial',
                        'size' => (int)$project->font_size
                    ],
                    [
                        'alignment' => $project->text_style === 'justify' ? \PhpOffice\PhpWord\SimpleType\Jc::BOTH : \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
                        'lineHeight' => 1.6
                    ]
                );
            }
        }

        // Save and download
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $fileName = storage_path('app/temp/' . Str::slug($project->title) . '.docx');

        // Ensure temp directory exists
        if (!file_exists(dirname($fileName))) {
            mkdir(dirname($fileName), 0755, true);
        }

        $objWriter->save($fileName);

        return response()->download($fileName, Str::slug($project->title) . '.docx')
            ->deleteFileAfterSend(true);
    }

    private function exportToPdf($project, $chapters, $dimensions)
    {
        // Prepare data for the view
        $exportData = [
            'project' => $project,
            'chapters' => $chapters,
            'dimensions' => $dimensions,
            'isPrintVersion' => $project->format === 'PDF Print'
        ];

        // Load the enhanced export view
        $pdf = Pdf::loadView('dashboard.books.export', $exportData);

        // Configure PDF options for better rendering
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
            'logOutputFile' => storage_path('logs/dompdf.log'),
            'tempDir' => storage_path('app/temp'),
        ]);

        // Set paper size with proper dimensions
        $paperWidth = $dimensions['w'] * 72; // Convert inches to points
        $paperHeight = $dimensions['h'] * 72;
        $pdf->setPaper([0, 0, $paperWidth, $paperHeight], 'portrait');

        $fileName = Str::slug($project->title) . '.pdf';

        return $pdf->download($fileName);
    }

    public function updateTitle(Request $request)
    {
        $project = auth()->user()->books()->where('id', $request->project_id)->first();

        if ($project && $request->title != null) {
            $project->title = $request->title;
            $project->save();
            return [
                'success' => true,
                'message' => 'Updated'
            ];
        }

        return [
            'success' => false,
            'message' => 'Did not updated'
        ];
    }
}
