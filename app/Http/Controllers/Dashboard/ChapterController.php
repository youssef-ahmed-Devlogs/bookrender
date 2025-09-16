<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $settings = Setting::first();
        $book = Project::findOrFail($id);
        return view('dashboard.chapter.createchapter', [
            'settings' => $settings,
            'book' => $book,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $book = $request->book_id;
        $request->validate([
            'title' => 'required',
            // 'subtitle' => 'required',
        ]);

        // Fetch previous chapters for context
        $previousChapters = \App\Models\Chapter::where('project_id', $book)->orderBy('id')->get();
        $previousContent = '';
        foreach ($previousChapters as $prev) {
            $previousContent .= "Title: {$prev->title}\nSubtitle: {$prev->subtitle}\nContent: " . ($prev->content ?? '') . "\n\n";
        }

        // Build AI prompt
        $prompt = "You are an expert book writer. Write a new chapter for the book.\n";
        if ($previousContent) {
            $prompt .= "Previous chapters for context:\n$previousContent\n";
        }
        $prompt .= "Now write a new chapter with the following metadata:\n";
        $prompt .= "Title: {$request->title}\nSubtitle: {$request->subtitle}\n";
        $prompt .= "Limit the chapter to approximately 200 words.\n";
        $prompt .= "Respond ONLY with the content of the new chapter.\n";
        \Log::info('Together AI prompt: ' . $prompt);
        $content = $this->generateAIContent($prompt);

        \App\Models\Chapter::create([
            'project_id' => $book,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'license' => $request->license,
            'doi' => $request->doi,
            'content' => $content,
        ]);
        return redirect()->route('dashboard.books.show', $book);
    }

    /**
     * Generate content using Gemini AI API (DRY, reusable)
     */
    private function generateAIContent($prompt)
    {
        $apiKey = config('services.togetherai.api_key');
        $url = "https://api.together.xyz/v1/completions";
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(90)
            ->withOptions([
                'ssl_version' => CURL_SSLVERSION_TLSv1_2
            ])
            ->post($url, [
                'model' => 'mistralai/Mistral-7B-Instruct-v0.3',
                'prompt' => $prompt,
                'max_tokens' => 1024,
            ]);
        \Log::info('Together AI response: ' . $response->body());
        if ($response->successful()) {
            return $response->json()['choices'][0]['text'] ?? '';
        }
        return '';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        $settings = Setting::first();
        $book = Project::findOrFail($chapter->project_id);
        return view('dashboard.chapter.createchapter', [
            'settings' => $settings,
            'book' => $book,
            'chapter' => $chapter
        ]);
    }

    public function updateChapter(Request $request, Chapter $chapter)
    {
        $request->validate([
            'title' => 'required',
            // 'subtitle' => 'required',
        ]);

        $chapter->update($request->all());

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $chapter = Chapter::findOrFail($id);
            $chapter->content = $request->input('content');
            $chapter->save();

            return response()->json(['success' => true, 'message' => 'Chapter updated successfully.']);
        } catch (\Exception $e) {
            Log::error("Chapter update failed: {$e->getMessage()}");
            return response()->json(['success' => false, 'message' => 'Failed to update chapter.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return redirect()->back();
    }


    public function updateContentAI(Request $request, $id)
    {
        $user = auth()->user();

        foreach ($user->subscriptions as $subscription) {
            if ($subscription->used_words >= $subscription->plan->word_number) {
                return response()->json(['error' => 'You have reached your word limit.']);
            }
        }

        $chapter = Chapter::findOrFail($id);
        $chapter->content = $request->input('content');
        $chapter->save();

        $wordcount = $request->input('word_count');

        foreach ($user->subscriptions as $subscription) {
            $subscription->used_words += $wordcount;
            $subscription->save();
        }

        return response()->json(['success' => true]);
    }


    public function generateContentAI(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'chapters' => 'required|array',
            'chapters.*.title' => 'required|string',
            'chapters.*.content' => 'required|string',
        ]);

        try {
            foreach ($request->chapters as $chapterData) {
                Chapter::create([
                    'project_id' => $request->project_id,
                    'title' => $chapterData['title'],
                    'content' => $chapterData['content'],
                ]);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error saving chapters via API: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to save chapters.'], 500);
        }
    }

    public function clearSession(Request $request)
    {
        try {
            $request->session()->forget(['project_id', 'generated_content']);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error clearing predict session: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Failed to clear session.'], 500);
        }
    }
}
