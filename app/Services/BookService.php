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
            Log::info('Starting book content generation', ['title' => $data['title'] ?? 'Unknown']);
            
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

            Log::info('Book content generation completed', [
                'sections_generated' => count($generatedContent),
                'total_content_length' => array_sum(array_map(fn($section) => strlen($section['content']), $generatedContent))
            ]);
            
            return $generatedContent;
        } catch (\Exception $e) {
            Log::error('Error generating book content: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
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
        
        // Calculate font sizes based on user selection
        $base = (int) ($data['font_size'] ?? 12);
        $sizes = [
            'p' => $base,
            'h1' => $base + 20,
        ];
        $lhBody = 1.7;
        $lhHead = 1.25;
        $spaceSm = (int) round($base * 0.75);
        $spaceMd = (int) round($base * 1.25);
        $spaceLg = (int) round($base * 2.0);

        $headingStyle = $this->getHeadingStyleCss();
        
        $content = "
            <div class='copyright-page'>
                <h1 style=\"font-size: {$sizes['h1']}px; line-height: {$lhHead}; margin: {$spaceLg}px 0 {$spaceMd}px; text-align: center; {$headingStyle}\">{$title}</h1>
                <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: center;\">Copyright © {$currentYear} by {$author}</p>
                <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: justify;\">All rights reserved. No part of this book may be reproduced, distributed, or transmitted in any form or by any means, including photocopying, recording, or other electronic or mechanical methods, without the prior written permission of the publisher, except in the case of brief quotations embodied in critical reviews and certain other noncommercial uses permitted by copyright law.</p>
                <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: justify;\">For permission requests, write to the publisher, addressed 'Attention: Permissions Coordinator,' at the address below.</p>
                <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: center;\">Published by BookRender</p>
                <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: center;\">First Edition: {$currentYear}</p>
            </div>
        ";

        return $this->formatAsHtml($content, 'copyright');
    }

    private function generateTableOfContents(array $chapterNames, array $data): string
    {
        // Calculate font sizes based on user selection
        $base = (int) ($data['font_size'] ?? 12);
        $sizes = [
            'p' => $base,
            'h1' => $base + 20,
        ];
        $lhBody = 1.7;
        $lhHead = 1.25;
        $spaceSm = (int) round($base * 0.75);
        $spaceMd = (int) round($base * 1.25);
        $spaceLg = (int) round($base * 2.0);
        
        $headingStyle = $this->getHeadingStyleCss();

        $html = '<div class="table-of-contents" style="max-width: 100%; margin: 0 auto;">';
        $html .= '<h1 style="font-size: ' . $sizes['h1'] . 'px; line-height: ' . $lhHead . '; margin: ' . $spaceLg . 'px 0 ' . $spaceMd . 'px; text-align: center; ' . $headingStyle . '">Table of Contents</h1>';
        $html .= '<div class="toc-content" style="margin-top: ' . $spaceLg . 'px;">';

        // Add special sections if they exist
        $pageNumber = 1;

        if ($data['book_intro'] === 'Yes') {
            $html .= $this->generateTocItem('Book Introduction', $pageNumber, $sizes['p'], $spaceSm);
            $pageNumber++;
        }

        if ($data['copyright_page'] === 'Yes') {
            $html .= $this->generateTocItem('Copyright Page', $pageNumber, $sizes['p'], $spaceSm);
            $pageNumber++;
        }

        if ($data['table_of_contents'] === 'Yes') {
            $html .= $this->generateTocItem('Table of Contents', $pageNumber, $sizes['p'], $spaceSm);
            $pageNumber++;
        }

        // Add chapters
        foreach ($chapterNames as $index => $chapterName) {
            $chapterTitle = 'Chapter ' . ($index + 1) . ': ' . $chapterName;
            $html .= $this->generateTocItem($chapterTitle, $pageNumber, $sizes['p'], $spaceSm);
            $pageNumber++;
        }

        $html .= '</div></div>';

        return $html;
    }
    
    private function generateTocItem(string $title, int $pageNumber, int $fontSize, int $marginBottom): string
    {
        // PDF-compatible TOC item using table layout instead of flexbox
        return '<div class="toc-item" style="' .
            'display: table; ' .
            'width: 100%; ' .
            'margin-bottom: ' . $marginBottom . 'px; ' .
            'line-height: 1.6; ' .
            'table-layout: fixed;' .
        '">' .
            '<span class="toc-title" style="' .
                'display: table-cell; ' .
                'font-size: ' . $fontSize . 'px; ' .
                'width: auto; ' .
                'padding-right: 8px; ' .
                'background: white; ' .
                'vertical-align: bottom; ' .
                'white-space: nowrap;' .
            '">' . htmlspecialchars($title) . '</span>' .
            '<span class="toc-leader" style="' .
                'display: table-cell; ' .
                'width: 100%; ' .
                'border-bottom: 1px dotted #333; ' .
                'height: 1px; ' .
                'vertical-align: bottom;' .
            '"></span>' .
            '<span class="toc-page" style="' .
                'display: table-cell; ' .
                'font-size: ' . $fontSize . 'px; ' .
                'width: auto; ' .
                'padding-left: 8px; ' .
                'background: white; ' .
                'vertical-align: bottom; ' .
                'font-weight: bold; ' .
                'text-align: right; ' .
                'white-space: nowrap;' .
            '">' . $pageNumber . '</span>' .
        '</div>';
    }

    private function generateChapterContent(string $chapterName, array $data, ?Category $category, int $chapterNumber): string
    {
        try {
            Log::info('Generating chapter content', ['chapter' => $chapterName, 'number' => $chapterNumber]);
            
            $aiService = new AiService();

            $prompt = $this->buildChapterPrompt($chapterName, $data, $category, $chapterNumber);

            $response = $aiService->buildPrompt(function () use ($prompt) {
                return $prompt;
            })->send();

            if (!$response->successful()) {
                Log::error('AI service failed for chapter', ['chapter' => $chapterName, 'response' => $response]);
                throw new \Exception("Failed to generate content for chapter: {$chapterName}");
            }

            $content = $response->json()['choices'][0]['text'] ?? '';
            
            if (empty($content)) {
                Log::error('Empty content received from AI', ['chapter' => $chapterName]);
                throw new \Exception("Empty content received for chapter: {$chapterName}");
            }
            
            // Additional cleanup for AI-generated content before formatting
            $content = $this->cleanupAiGeneratedContent($content, $chapterName);
            
            $finalContent = $this->formatAsHtml($content, 'chapter', $chapterNumber);
            
            Log::info('Chapter content generated successfully', [
                'chapter' => $chapterName,
                'content_length' => strlen($finalContent)
            ]);
            
            return $finalContent;
        } catch (\Exception $e) {
            Log::error('Error generating chapter content', [
                'chapter' => $chapterName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
        
        // Get styling based on user choice
        $textStyle = $data['text_style'] ?? 'A';
        $paragraphStyle = $this->getTextStyleCss($textStyle);
        $headingStyle = $this->getHeadingStyleCss();

        $prompt = "Write a book introduction in HTML format.\n\n";
        $prompt .= "Book Title: {$data['title']}\n";
        $prompt .= "Author: {$data['author']}\n";
        $prompt .= "Description: {$data['description']}\n";
        $prompt .= "Category: {$data['category']}\n\n";

        if ($category && $category->prompt) {
            $prompt .= "Style: {$category->prompt}\n\n";
        }

        $prompt .= "CRITICAL: Use EXACT inline typography (MUST be inline styles on the elements, not <style> tags):\n";
        $prompt .= "- Book title <h1 style=\"font-size: {$sizes['h1']}px; line-height: {$lhHead}; margin: {$spaceLg}px 0 {$spaceMd}px; text-align: center; {$headingStyle}\">\n";
        $prompt .= "- Author name <h2 style=\"font-size: {$sizes['h2']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px; text-align: center; {$headingStyle}\">\n";
        $prompt .= "- Paragraphs <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; text-align: justify; {$paragraphStyle}\">\n";
        $prompt .= "- Optional section headings <h3 style=\"font-size: {$sizes['h3']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px; {$headingStyle}\">\n";

        $prompt .= "Content rules:\n";
        $prompt .= "- Start with EXACTLY this title: '{$data['title']}' (write it only ONCE) in h1 tags with the specified styling\n";
        $prompt .= "- Follow with EXACTLY this author: 'by {$data['author']}' (write it only ONCE) in h2 tags with the specified styling\n";
        $prompt .= "- Write 2–3 engaging paragraphs that hook the reader\n";
        $prompt .= "- Use EXACTLY the font sizes specified above\n";
        $prompt .= "- Use only inline styles for all typography and spacing\n";
        $prompt .= "- Do NOT include any <style> blocks or external CSS\n";
        $prompt .= "- Do NOT use markdown code blocks (```html or ```)\n";
        $prompt .= "- Do NOT generate full HTML documents with <!DOCTYPE>, <html>, <head>, <body> tags\n";
        $prompt .= "- Generate ONLY the content HTML elements (h1, h2, p tags with inline styles)\n";
        $prompt .= "- CRITICAL: Do NOT repeat any text - write each title/name only ONCE\n";
        $prompt .= "- CRITICAL: Do NOT duplicate words within titles (avoid 'TitleTitle' patterns)\n\n";
        $prompt .= "Generate ONLY the content HTML elements (with exact inline styles as specified). No extra commentary, no code blocks, no full HTML document:";

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
        
        // Get styling based on user choice
        $textStyle = $data['text_style'] ?? 'A';
        $paragraphStyle = $this->getTextStyleCss($textStyle);
        $headingStyle = $this->getHeadingStyleCss();

        $prompt = "Write a book chapter content in HTML format.\n\n";
        $prompt .= "Chapter: {$chapterName} (Chapter {$chapterNumber})\n";
        $prompt .= "Book Description: {$data['description']}\n";
        $prompt .= "Category: {$data['category']}\n\n";

        if ($category && $category->prompt) {
            $prompt .= "Style: {$category->prompt}\n\n";
        }

        $prompt .= "Inline typography (MUST be inline styles on the elements, not <style> tags):\n";
        $prompt .= "- Chapter title <h1 style=\"font-size: {$sizes['h1']}px; line-height: {$lhHead}; margin: {$spaceLg}px 0 {$spaceMd}px; text-align: center; {$headingStyle}\">\n";
        $prompt .= "- Section headings <h2 style=\"font-size: {$sizes['h2']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px; {$headingStyle}\"> or <h3 style=\"font-size: {$sizes['h3']}px; line-height: {$lhHead}; margin: {$spaceMd}px 0 {$spaceSm}px; {$headingStyle}\">\n";
        $prompt .= "- Paragraphs <p style=\"font-size: {$sizes['p']}px; line-height: {$lhBody}; margin: 0 0 {$spaceSm}px 0; {$paragraphStyle}\">\n";
        $prompt .= "- Use <strong> and <em> where appropriate (you may add inline style if needed)\n";

        $prompt .= "Content rules:\n";
        $prompt .= "- Start with EXACTLY this chapter title: '{$chapterName}' (write it only ONCE) in h1 tags with the specified styling\n";
        $prompt .= "- 600–1200 words, engaging, with narrative flow\n";
        $prompt .= "- Include description and occasional dialogue\n";
        $prompt .= "- Match the book's theme\n";
        $prompt .= "- DO NOT repeat the book title in the chapter content\n";
        $prompt .= "- CRITICAL: Do NOT repeat the chapter title multiple times\n";
        $prompt .= "- CRITICAL: Do NOT duplicate words within the chapter title (avoid 'TitleTitle' patterns)\n";
        $prompt .= "- Focus only on the chapter content, not book metadata\n";
        $prompt .= "- Use only inline styles for all typography and spacing\n";
        $prompt .= "- Do NOT include any <style> blocks or external CSS\n";
        $prompt .= "- Do NOT use markdown code blocks (```html or ```)\n";
        $prompt .= "- Do NOT generate full HTML documents with <!DOCTYPE>, <html>, <head>, <body> tags\n";
        $prompt .= "- Generate ONLY the content HTML elements (h1, h2, h3, p tags with inline styles)\n\n";
        $prompt .= "Generate ONLY the content HTML elements (with inline styles). No extra commentary, no code blocks, no full HTML document:";

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

        // Fix duplicate titles by removing repeated h1 tags with the same content
        $content = $this->removeDuplicateTitles($content);

        // Ensure proper HTML structure - but don't add automatic chapter titles
        if (!preg_match('/<html|<body|<div|<h[1-6]|<p/', $content)) {
            // If no HTML tags found, wrap in appropriate structure without adding titles
            $content = "<div class='{$type}'>{$content}</div>";
        }

        // Ensure all h1 tags are centered and bold (fallback for any that might not have proper styling)
        $content = preg_replace_callback('/<h1([^>]*)>/', function($matches) {
            $attributes = $matches[1];
            $needsCenter = !str_contains($attributes, 'text-align');
            $needsBold = !str_contains($attributes, 'font-weight');
            
            if ($needsCenter || $needsBold) {
                if (str_contains($attributes, 'style="')) {
                    // Add to existing style attribute
                    $additions = [];
                    if ($needsCenter) $additions[] = 'text-align: center;';
                    if ($needsBold) $additions[] = 'font-weight: bold;';
                    $attributes = str_replace('style="', 'style="' . implode(' ', $additions) . ' ', $attributes);
                } else {
                    // Add new style attribute
                    $additions = [];
                    if ($needsCenter) $additions[] = 'text-align: center;';
                    if ($needsBold) $additions[] = 'font-weight: bold;';
                    $attributes .= ' style="' . implode(' ', $additions) . '"';
                }
            }
            return '<h1' . $attributes . '>';
        }, $content);
        
        // Ensure all other heading tags (h2, h3, h4, h5, h6) are bold
        for ($i = 2; $i <= 6; $i++) {
            $content = preg_replace_callback('/<h' . $i . '([^>]*)>/', function($matches) use ($i) {
                $attributes = $matches[1];
                $needsBold = !str_contains($attributes, 'font-weight');
                
                if ($needsBold) {
                    if (str_contains($attributes, 'style="')) {
                        $attributes = str_replace('style="', 'style="font-weight: bold; ', $attributes);
                    } else {
                        $attributes .= ' style="font-weight: bold;"';
                    }
                }
                return '<h' . $i . $attributes . '>';
            }, $content);
        }

        // Process underline tags for better Quill compatibility
        $content = $this->normalizeUnderlineForQuill($content);
        
        // Enhance underline visibility
        $content = $this->enhanceUnderlineVisibility($content);

        // DO NOT add CSS classes that might override inline styles
        // The inline styles from the AI prompts should take precedence
        // Only add minimal wrapper classes if needed
        if ($type === 'chapter') {
            $content = "<div class='chapter-content'>{$content}</div>";
        } elseif ($type === 'introduction') {
            $content = "<div class='introduction-content'>{$content}</div>";
        } elseif ($type === 'copyright') {
            $content = "<div class='copyright-content'>{$content}</div>";
        }

        return $content;
    }

    private function removeDuplicateTitles(string $content): string
    {
        // First, handle HTML h1 tags with duplicate content
        $pattern = '/<h1[^>]*>(.*?)<\/h1>/i';
        
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            $seenTitles = [];
            $toRemove = [];
            
            foreach ($matches as $match) {
                $fullTag = $match[0];
                $titleText = strip_tags($match[1]);
                $cleanTitle = trim($titleText);
                
                if (in_array($cleanTitle, $seenTitles)) {
                    // This is a duplicate, mark for removal
                    $toRemove[] = $fullTag;
                } else {
                    $seenTitles[] = $cleanTitle;
                }
            }
            
            // Remove duplicate titles
            foreach ($toRemove as $duplicateTag) {
                $content = str_replace($duplicateTag, '', $content);
            }
        }
        
        // Handle duplicated text within h1 tags (like "TitleTitle" inside <h1>TitleTitle</h1>)
        $content = preg_replace_callback('/<h1([^>]*)>([^<]+)<\/h1>/i', function($matches) {
            $attributes = $matches[1];
            $titleText = $matches[2];
            
            // Check if the title text is duplicated within itself
            $cleanedTitle = $this->removeDuplicatedText($titleText);
            
            return "<h1{$attributes}>{$cleanedTitle}</h1>";
        }, $content);
        
        // Handle duplicated text within h2 tags (for author names)
        $content = preg_replace_callback('/<h2([^>]*)>([^<]+)<\/h2>/i', function($matches) {
            $attributes = $matches[1];
            $titleText = $matches[2];
            
            // Check if the title text is duplicated within itself
            $cleanedTitle = $this->removeDuplicatedText($titleText);
            
            return "<h2{$attributes}>{$cleanedTitle}</h2>";
        }, $content);
        
        // Also handle cases where titles might be repeated as plain text at the beginning of lines
        $content = preg_replace_callback('/^(.{1,50}?)\1+$/m', function($matches) {
            $line = trim($matches[0]);
            // Only apply this to lines that look like titles (short lines, no complex HTML)
            if (strlen($line) < 100 && substr_count($line, '<') <= 2) {
                return trim($matches[1]);
            }
            return $line;
        }, $content);
        
        return $content;
    }
    
    private function removeDuplicatedText(string $text): string
    {
        $text = trim($text);
        
        // Handle cases like "TitleTitle" -> "Title"
        // Split the text in half and check if both halves are identical
        $length = strlen($text);
        
        // Try different split points to find duplications
        for ($i = 1; $i <= $length / 2; $i++) {
            $firstPart = substr($text, 0, $i);
            $secondPart = substr($text, $i, $i);
            
            if ($firstPart === $secondPart) {
                // Check if the rest of the string continues the pattern
                $pattern = str_repeat($firstPart, floor($length / $i));
                if (strpos($text, $pattern) === 0) {
                    return $firstPart;
                }
            }
        }
        
        // If no duplication pattern found, return original text
        return $text;
    }

    private function cleanupAiGeneratedContent(string $content, string $expectedTitle): string
    {
        // Clean up the raw AI response
        $content = trim($content);
        
        // Remove any leading/trailing quotes or extra formatting
        $content = trim($content, '"\'');
        
        // CRITICAL: Remove full HTML document structure that AI is generating
        // Remove ```html code blocks
        $content = preg_replace('/^```html\s*/i', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);
        
        // Remove DOCTYPE and HTML document structure
        $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);
        $content = preg_replace('/<html[^>]*>/i', '', $content);
        $content = preg_replace('/<\/html>/i', '', $content);
        $content = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $content);
        $content = preg_replace('/<body[^>]*>/i', '', $content);
        $content = preg_replace('/<\/body>/i', '', $content);
        $content = preg_replace('/<meta[^>]*>/i', '', $content);
        
        // Clean up any remaining whitespace after removing HTML structure
        $content = trim($content);
        
        // AGGRESSIVE: Handle the specific patterns we're seeing
        // Pattern 1: "TitleTitle" at the beginning of content
        $escapedTitle = preg_quote($expectedTitle, '/');
        $content = preg_replace('/^' . $escapedTitle . '\s*' . $escapedTitle . '/i', $expectedTitle, $content);
        
        // Pattern 2: Handle cases where title words are duplicated individually
        $titleWords = explode(' ', $expectedTitle);
        foreach ($titleWords as $word) {
            if (strlen($word) > 2) { // Only process meaningful words
                $escapedWord = preg_quote($word, '/');
                // Replace "WordWord" with "Word" at the beginning of lines
                $content = preg_replace('/^' . $escapedWord . '\s*' . $escapedWord . '/im', $word, $content);
            }
        }
        
        // Pattern 3: Handle any repeated text at the beginning of lines (more aggressive)
        $lines = explode("\n", $content);
        $cleanedLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                $cleanedLines[] = $line;
                continue;
            }
            
            // Check if this line has repeated text
            $cleanedLine = $this->removeLineRepetition($line);
            $cleanedLines[] = $cleanedLine;
        }
        
        $content = implode("\n", $cleanedLines);
        
        // Clean up any double spaces or line breaks
        $content = preg_replace('/\s+/', ' ', $content);
        $content = preg_replace('/\n\s*\n/', "\n\n", $content);
        
        // Log the content for debugging (remove in production)
        Log::info('AI Generated Content for: ' . $expectedTitle, [
            'original_length' => strlen($content),
            'first_100_chars' => substr($content, 0, 100)
        ]);
        
        return trim($content);
    }
    
    private function removeLineRepetition(string $line): string
    {
        $line = trim($line);
        $length = strlen($line);
        
        // Try to find if the line is repeated text
        for ($i = 1; $i <= $length / 2; $i++) {
            $firstPart = substr($line, 0, $i);
            $remaining = substr($line, $i);
            
            // Check if the remaining part starts with the same text
            if (strpos($remaining, $firstPart) === 0) {
                // Check how many times it repeats
                $pattern = str_repeat($firstPart, floor($length / $i));
                if (strpos($line, $pattern) === 0) {
                    return $firstPart;
                }
            }
        }
        
        return $line;
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
    
    private function getTextStyleCss(string $textStyle): string
    {
        switch (strtoupper($textStyle)) {
            case 'B':
                return 'font-weight: bold;';
            case 'I':
                return 'font-style: italic;';
            case 'U':
                return 'text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 2px;';
            case 'A':
            default:
                return 'font-weight: normal; font-style: normal; text-decoration: none;';
        }
    }
    
    private function getHeadingStyleCss(): string
    {
        // Headings are always bold regardless of user choice
        return 'font-weight: bold;';
    }
    
    private function normalizeUnderlineForQuill(string $content): string
    {
        // Convert <u> tags to proper CSS text-decoration for better Quill compatibility
        $content = preg_replace_callback('/<u([^>]*)>(.*?)<\/u>/s', function($matches) {
            $attributes = $matches[1];
            $innerContent = $matches[2];
            
            // If it's already a styled element, add underline to existing style
            if (preg_match('/<([^>]+)\s+style="([^"]*)"([^>]*)>(.*?)<\/\1>/s', $innerContent, $innerMatches)) {
                $tag = $innerMatches[1];
                $existingStyle = $innerMatches[2];
                $otherAttrs = $innerMatches[3];
                $text = $innerMatches[4];
                
                // Add underline to existing style if not already present
                if (!str_contains($existingStyle, 'text-decoration')) {
                    $newStyle = $existingStyle . '; text-decoration: underline;';
                } else {
                    $newStyle = $existingStyle;
                }
                
                return "<{$tag} style=\"{$newStyle}\"{$otherAttrs}>{$text}</{$tag}>";
            } else {
                // Wrap in span with underline style
                return "<span style=\"text-decoration: underline;\">{$innerContent}</span>";
            }
        }, $content);
        
        return $content;
    }
    
    private function enhanceUnderlineVisibility(string $content): string
    {
        // Enhance underline visibility by ensuring proper CSS properties
        $content = preg_replace_callback('/style="([^"]*text-decoration:\s*underline[^"]*)"/', function($matches) {
            $style = $matches[1];
            
            // Add additional properties to make underline more visible
            if (!str_contains($style, 'text-decoration-thickness')) {
                $style .= '; text-decoration-thickness: 1px;';
            }
            if (!str_contains($style, 'text-underline-offset')) {
                $style .= '; text-underline-offset: 2px;';
            }
            
            return 'style="' . $style . '"';
        }, $content);
        
        return $content;
    }
}
