<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;

class BookService
{
    public function create($request): Project
    {
        $user = auth()->user();

        $project = Project::create([
            'user_id' => $user->id,
            'image' => null,
            'author' => $request->author,
            'title' => $request->title,
            'second_title' => $request->second_title,
            'description' => $request->description,
            'treem_size' => $request->treem_size,
            'page' => $request->page_count,
            'format' => $request->format,
            'bleed_file' => $request->bleed_file,
            'category' => $request->category,
            'chapter' => $request->chapters, // The names of chapters
            'text_style' => $request->text_style,
            'font_size' => $request->font_size,
            'add_page_num' => $request->add_page_num,
            'book_intro' => $request->book_intro,
            'copyright_page' => $request->copyright_page,
            'table_of_contents' => $request->table_of_contents,
        ]);

        return $project;
    }

    public function sendPrompt(array $data)
    {
        $category = Category::where('name', $data['category'])->first();
        $chapterNames = explode(',', $data['chapters']);

        $aiService = new AiService();
        $response = $aiService->buildPrompt(function () use ($data, $category, $chapterNames) {
            $prompt = "Generate a book with the title " . "'" . $data['title'] . "'" . 'and description' . "'" . $data['description'] . "'" . ".\n";
            $prompt .= "Please design the output to be can added to the ckeditor as HTML";

            if ($category && $category->prompt) {
                $prompt .= "The book's category is '{$category->name}'. Follow these category-specific instructions: {$category->prompt}\n";
            }

            // Sections to generate
            $sections = [];

            if ($data['book_intro'] === 'Yes') {
                $sections[] = 'Book Introduction';
            }

            if ($data['copyright_page'] === 'Yes') {
                $sections[] = 'Copyright Page';
            }

            if ($data['table_of_contents'] === 'Yes') {
                $sections[] = 'Table of Contents';
            }

            $chapterNames = array_map('trim', explode(',', $data['chapters']));

            foreach ($chapterNames as $chapterName) {
                $sections[] = $chapterName;
            }

            $prompt .= "Generate ONLY the following sections, in this exact order. For each, use the format: Title: [section name]\nContent: [section content]\n. Do not generate any extra sections or chapters. The sections are:\n";

            foreach ($sections as $section) {
                $prompt .= "- {$section}\n";
            }

            $prompt .= "\nRespond in English and follow the format strictly.";

            return $prompt;
        })
            ->send();


        return $response;
    }
}
