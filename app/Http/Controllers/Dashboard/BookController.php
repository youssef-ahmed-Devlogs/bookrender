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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

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

        $chapters = $project->chapters;  // include every chapter in stored order

        if ($project->format === 'Word') {
            $phpWord = new PhpWord();

            // --- Title Page ---
            $titleSection = $phpWord->addSection(['breakType' => 'nextPage']);
            $titleSection->addText($project->title, ['name' => 'Arial', 'size' => 24, 'bold' => true], ['alignment' => 'center']);
            $titleSection->addText($project->second_title, ['name' => 'Arial', 'size' => 18, 'italic' => true], ['alignment' => 'center']);
            $titleSection->addText("By: " . $project->author, ['name' => 'Arial', 'size' => 14], ['alignment' => 'center', 'spaceBefore' => 480]);
            $titleSection->addText($project->description, ['name' => 'Arial', 'size' => 12], ['alignment' => 'justify', 'spaceBefore' => 240]);

            // Set default font (will be overridden by HTML styling from editor)
            // This is removed as per user request to respect editor styling
            // $phpWord->setDefaultFontName($project->text_style);
            // $phpWord->setDefaultFontSize($project->font_size);

            // Add sections
            if ($project->book_intro == 'Yes') {
                $section = $phpWord->addSection();
                $section->addTitle('Book Introduction', 1);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $project->book_intro);
            }

            if ($project->copyright_page == 'Yes') {
                $section = $phpWord->addSection();
                $section->addTitle('Copyright', 1);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $project->copyright_page);
            }

            if ($project->table_of_contents == 'Yes') {
                $section = $phpWord->addSection();
                $section->addTitle('Table of Contents', 1);
                foreach ($chapters as $chapter) {
                    $section->addListItem($chapter->title, 0);
                }
            }

            foreach ($chapters as $chapter) {
                $section = $phpWord->addSection();
                $section->addTitle($chapter->title, 1);
                \PhpOffice\PhpWord\Shared\Html::addHtml($section, $chapter->content);

                if ($project->add_page_num == 'Yes') {
                    $footer = $section->addFooter();
                    $footer->addPreserveText('Page {PAGE} of {NUMPAGES}', null, ['alignment' => 'center']);
                }
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = "{$project->title}.docx";
            $objWriter->save($fileName);

            return response()->download($fileName)->deleteFileAfterSend(true);
        }

        // Handle PDF and PDF Print
        $pdf = Pdf::loadView('dashboard.books.export', compact('project', 'chapters'));

        // Configure PDF options
        $options = [
            'isHtml5ParserEnabled' => true, 
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ];

        if ($project->format === 'PDF Print') {
            // Additional settings for print if necessary
            $options['dpi'] = 300;
            $options['isPhpEnabled'] = true;
        }

        // Enable PHP for page numbering if requested
        if ($project->add_page_num === 'Yes') {
            $options['isPhpEnabled'] = true;
        }

        $pdf->setOptions($options);

        return $pdf->download("{$project->title}.pdf");
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
