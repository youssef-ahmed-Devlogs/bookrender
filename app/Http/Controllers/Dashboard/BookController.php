<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Chapter;
use App\Models\Project;
use App\Models\Setting;
use App\Services\BookService;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        session([
            'project_id' => null,
            'generated_content' => []
        ]);

        $user = auth()->user();

        $bookService = new BookService();
        $response = $bookService->sendPrompt($request->all());



        if ($response->successful()) {
            $text = $response->json()['choices'][0]['text'] ?? '';

            // --- 1. Check Quota BEFORE Creating the Project ---
            $wordCount = str_word_count(strip_tags($text));
            $subscription = $user->subscriptions->first(); // Assuming one subscription
            $wordLimit = $subscription->plan->word_number ?? 0;
            $usedWords = $subscription->used_words ?? 0;
            $remainingWords = $wordLimit - $usedWords;

            if ($wordCount > $remainingWords) {
                return redirect()->route('dashboard.books.index')->with('error', 'Your remaining word limit is not enough to generate this content.');
            }

            // --- 2. Create Project and Update Quota AFTER successful check ---
            $project = $bookService->create($request);



            $subscription->used_words = $usedWords + $wordCount;
            $subscription->used_books += 1;
            $subscription->save();

            // --- 3. Pass Generated Content to Session ---
            $sectionPattern = '/Title:(.*?)Content:(.*?)(?=Title:|$)/s';
            preg_match_all($sectionPattern, $text, $matches, PREG_SET_ORDER);

            $generatedContent = [];
            foreach ($matches as $match) {
                $title = trim($match[1]);
                $content = trim($match[2]);
                $generatedContent[] = ['title' => $title, 'content' => $content];
            }

            session([
                'project_id' => $project->id,
                'generated_content' => $generatedContent
            ]);

            foreach ($generatedContent as $chapterData) {
                Chapter::create([
                    'project_id' => $project->id,
                    'title' => $chapterData['title'],
                    'content' => $chapterData['content'],
                ]);
            }

            if ($request->submit_type === 'save_close') {
                return redirect()->route('dashboard.books.index');
            }

            return redirect()->route('dashboard.showpredict', ['project_id' => $project->id]);
        }

        return redirect()->route('dashboard.books.create')->with('error', 'Failed to generate book content. Please try again.');
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
        $pdf = Pdf::loadView('user.book.export', compact('project', 'chapters'));

        if ($project->format === 'PDF Print') {
            // Additional settings for print if necessary
            $pdf->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        }

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
