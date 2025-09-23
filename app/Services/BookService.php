<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

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
            'chapter' => $request->chapters,
            'text_style' => $request->text_style,
            'font_size' => $request->font_size,
            'add_page_num' => $request->add_page_num,
            'book_intro' => $request->book_intro,
            'copyright_page' => $request->copyright_page,
            'table_of_contents' => $request->table_of_contents,
        ]);

        return $project;
    }

    public function generateBookContent(array $data): array
    {
        try {
            $category = Category::where('name', $data['category'])->first();
            $chapterNames = array_map('trim', explode(',', $data['chapters']));

            // Generate content for each section
            $generatedContent = [];

            // Generate Book Introduction if requested
            if ($data['book_intro'] === 'Yes') {
                $introContent = $this->generateBookIntroduction($data, $category);
                $generatedContent[] = [
                    'title' => 'Book Introduction',
                    'content' => $introContent,
                    'type' => 'introduction'
                ];
            }

            // Generate Copyright Page if requested
            if ($data['copyright_page'] === 'Yes') {
                $copyrightContent = $this->generateCopyrightPage($data);
                $generatedContent[] = [
                    'title' => 'Copyright Page',
                    'content' => $copyrightContent,
                    'type' => 'copyright'
                ];
            }

            // Generate Table of Contents if requested
            if ($data['table_of_contents'] === 'Yes') {
                $tocContent = $this->generateTableOfContents($chapterNames, $data);
                $generatedContent[] = [
                    'title' => 'Table of Contents',
                    'content' => $tocContent,
                    'type' => 'table_of_contents'
                ];
            }

            // Generate each chapter
            foreach ($chapterNames as $index => $chapterName) {
                $chapterContent = $this->generateChapterContent($chapterName, $data, $category, $index + 1);
                $generatedContent[] = [
                    'title' => $chapterName,
                    'content' => $chapterContent,
                    'type' => 'chapter',
                    'chapter_number' => $index + 1
                ];
            }

            return $generatedContent;
        } catch (\Exception $e) {
            Log::error('Error generating book content: ' . $e->getMessage());
            throw new \Exception('Failed to generate book content. Please try again.');
        }
    }

    private function generateBookIntroduction(array $data, ?Category $category): string
    {
        $aiService = new AiService();

        $prompt = $this->buildBookIntroductionPrompt($data, $category);

        $response = $aiService->buildPrompt(function () use ($prompt) {
            return $prompt;
        })->send();

        if (!$response->successful()) {
            throw new \Exception('Failed to generate book introduction');
        }

        $content = $response->json()['choices'][0]['text'] ?? '';
        return $this->formatAsHtml($content, 'introduction');
    }

    private function generateCopyrightPage(array $data): string
    {
        $currentYear = date('Y');
        $author = $data['author'];
        $title = $data['title'];

        return $this->formatAsHtml("
            <div class='copyright-page'>
                <h1>{$title}</h1>
                <p>Copyright © {$currentYear} by {$author}</p>
                <p>All rights reserved. No part of this book may be reproduced, distributed, or transmitted in any form or by any means, including photocopying, recording, or other electronic or mechanical methods, without the prior written permission of the publisher, except in the case of brief quotations embodied in critical reviews and certain other noncommercial uses permitted by copyright law.</p>
                <p>For permission requests, write to the publisher, addressed 'Attention: Permissions Coordinator,' at the address below.</p>
                <p>Published by BookRender</p>
                <p>First Edition: {$currentYear}</p>
            </div>
        ", 'copyright');
    }

    private function generateTableOfContents(array $chapterNames, array $data): string
    {
        $html = '<div class="table-of-contents">';
        $html .= '<h1>Table of Contents</h1>';
        $html .= '<div class="toc-content">';

        // Add special sections if they exist
        $pageNumber = 1;

        if ($data['book_intro'] === 'Yes') {
            $html .= '<div class="toc-item"><span class="toc-title">Book Introduction</span><span class="toc-dots">........................</span><span class="toc-page">' . $pageNumber . '</span></div>';
            $pageNumber++;
        }

        if ($data['copyright_page'] === 'Yes') {
            $html .= '<div class="toc-item"><span class="toc-title">Copyright Page</span><span class="toc-dots">........................</span><span class="toc-page">' . $pageNumber . '</span></div>';
            $pageNumber++;
        }

        if ($data['table_of_contents'] === 'Yes') {
            $html .= '<div class="toc-item"><span class="toc-title">Table of Contents</span><span class="toc-dots">........................</span><span class="toc-page">' . $pageNumber . '</span></div>';
            $pageNumber++;
        }

        // Add chapters
        foreach ($chapterNames as $index => $chapterName) {
            $html .= '<div class="toc-item"><span class="toc-title">Chapter ' . ($index + 1) . ': ' . $chapterName . '</span><span class="toc-dots">........................</span><span class="toc-page">' . $pageNumber . '</span></div>';
            $pageNumber++;
        }

        $html .= '</div></div>';

        return $html;
    }

    private function generateChapterContent(string $chapterName, array $data, ?Category $category, int $chapterNumber): string
    {
        $aiService = new AiService();

        $prompt = $this->buildChapterPrompt($chapterName, $data, $category, $chapterNumber);

        $response = $aiService->buildPrompt(function () use ($prompt) {
            return $prompt;
        })->send();

        if (!$response->successful()) {
            throw new \Exception("Failed to generate content for chapter: {$chapterName}");
        }

        $content = $response->json()['choices'][0]['text'] ?? '';
        return $this->formatAsHtml($content, 'chapter', $chapterNumber);
    }

    private function buildBookIntroductionPrompt(array $data, ?Category $category): string
    {
        $base = (int) ($data['font_size'] ?? 12);
        $sizes = [
            'p' => $base,
            'h6' => $base + 2,
            'h5' => $base + 4,
            'h4' => $base + 6,
            'h3' => $base + 10,
            'h2' => $base + 14,
            'h1' => $base + 20,
        ];
        $lhBody = 1.7;
        $lhHead = 1.25;
        $spaceSm = (int) round($base * 0.75);
        $spaceMd = (int) round($base * 1.25);
        $spaceLg = (int) round($base * 2.0);

        $prompt = "Write a book introduction in HTML format.\n\n";
        $prompt .= "Book: {$data['title']} by {$data['author']}\n";
        $prompt .= "Description: {$data['description']}\n";
        $prompt .= "Category: {$data['category']}\n\n";

        if ($category && $category->prompt) {
            $prompt .= "Style: {$category->prompt}\n\n";
        }

        $prompt .= "Inline typography (MUST be inline styles on the elements, not <style> tags):\n";
        $prompt .= "- Paragraphs <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0;\">\n";
        $prompt .= "- <h1 style=\"font-size: {$sizes['h1']}px; line-height: {$lhHead}; margin: {$spaceLg}px 0 {$spaceMd}px;\"> (main title)\n";
        $prompt .= "- <h2 style=\"font-size: {$sizes['h2']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px;\"> (subtitle)\n";
        $prompt .= "- Optional section headings may use <h3 style=\"font-size: {$sizes['h3']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px;\">\n";

        $prompt .= "Content rules:\n";
        $prompt .= "- Write 2–3 engaging paragraphs that hook the reader\n";
        $prompt .= "- Use only inline styles for all typography and spacing\n";
        $prompt .= "- Do NOT include any <style> blocks or external CSS\n";
        $prompt .= "- Do NOT use markdown code blocks (```)\n\n";
        $prompt .= "Generate ONLY the HTML (with inline styles). No extra commentary:";

        return $prompt;
    }

    private function buildChapterPrompt(string $chapterName, array $data, ?Category $category, int $chapterNumber): string
    {
        $base = (int) ($data['font_size'] ?? 12);
        $sizes = [
            'p' => $base,
            'h6' => $base + 2,
            'h5' => $base + 4,
            'h4' => $base + 6,
            'h3' => $base + 10,
            'h2' => $base + 14,
            'h1' => $base + 20,
        ];
        $lhBody = 1.7;
        $lhHead = 1.25;
        $spaceSm = (int) round($base * 0.75);
        $spaceMd = (int) round($base * 1.25);
        $spaceLg = (int) round($base * 2.0);

        $prompt = "Write a book chapter in HTML format.\n\n";
        $prompt .= "Book: {$data['title']} by {$data['author']}\n";
        $prompt .= "Chapter: {$chapterName} (Chapter {$chapterNumber})\n";
        $prompt .= "Description: {$data['description']}\n";
        $prompt .= "Category: {$data['category']}\n\n";

        if ($category && $category->prompt) {
            $prompt .= "Style: {$category->prompt}\n\n";
        }

        $prompt .= "Inline typography (MUST be inline styles on the elements, not <style> tags):\n";
        $prompt .= "- Chapter title <h1 style=\"font-size: {$sizes['h1']}px; line-height: {$lhHead}; margin: {$spaceLg}px 0 {$spaceMd}px;\">\n";
        $prompt .= "- Section headings <h2 style=\"font-size: {$sizes['h2']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px;\"> or <h3 style=\"font-size: {$sizes['h3']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px;\">\n";
        $prompt .= "- Paragraphs <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0;\">\n";
        $prompt .= "- Use <strong> and <em> where appropriate (you may add inline style if needed)\n";

        $prompt .= "Content rules:\n";
        $prompt .= "- 600–1200 words, engaging, with narrative flow\n";
        $prompt .= "- Include description and occasional dialogue\n";
        $prompt .= "- Match the book's theme\n";
        $prompt .= "- Use only inline styles for all typography and spacing\n";
        $prompt .= "- Do NOT include any <style> blocks or external CSS\n";
        $prompt .= "- Do NOT use markdown code blocks (```)\n\n";
        $prompt .= "Generate ONLY the HTML (with inline styles). No extra commentary:";

        return $prompt;
    }

    private function formatAsHtml(string $content, string $type, int $chapterNumber = null): string
    {
        // Clean up the content
        $content = trim($content);

        // Remove markdown code blocks (```html and ```)
        $content = preg_replace('/^```html\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        $content = preg_replace('/^```\s*/', '', $content);

        // Remove any other markdown formatting if present
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
        $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);

        // Clean up any extra whitespace
        $content = trim($content);

        // Ensure proper HTML structure
        if (!preg_match('/<html|<body|<div|<h[1-6]|<p/', $content)) {
            // If no HTML tags found, wrap in appropriate structure
            if ($type === 'chapter' && $chapterNumber) {
                $content = "<div class='chapter'><h1>Chapter {$chapterNumber}</h1><div class='chapter-content'>{$content}</div></div>";
            } else {
                $content = "<div class='{$type}'>{$content}</div>";
            }
        }

        // Add CSS classes for styling
        $content = str_replace('<h1>', '<h1 class="chapter-title">', $content);
        $content = str_replace('<h2>', '<h2 class="section-title">', $content);
        $content = str_replace('<p>', '<p class="paragraph">', $content);

        return $content;
    }

    // Legacy method for backward compatibility
    public function sendPrompt(array $data)
    {
        try {
            $generatedContent = $this->generateBookContent($data);

            // Convert to the old format for compatibility
            $formattedContent = '';
            foreach ($generatedContent as $section) {
                $formattedContent .= "Title: {$section['title']}\nContent: {$section['content']}\n\n";
            }

            // Create a mock response object
            return (object) [
                'successful' => function () {
                    return true;
                },
                'json' => function () use ($formattedContent) {
                    return ['choices' => [['text' => $formattedContent]]];
                }
            ];
        } catch (\Exception $e) {
            Log::error('Error in sendPrompt: ' . $e->getMessage());
            return (object) [
                'successful' => function () {
                    return false;
                },
                'json' => function () {
                    return [];
                }
            ];
        }
    }
}
