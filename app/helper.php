<?php

if (!function_exists('bb_format_text')) {
    /**
     * টেক্সটকে স্মার্টলি HTML এ রূপান্তর করে:
     * - একাধিক লাইন থাকলে → bullet list
     * - bullet/dash/number দিয়ে শুরু হলে → সেগুলো পরিষ্কার করে list item
     * - এক লাইন/প্যারা হলে → paragraph
     */
    function bb_format_text($text)
    {
        if (!$text) return '';

        $text = trim($text);

        // লাইনে ভাগ করি (\n, \r\n, অথবা bullet চিহ্ন দিয়ে)
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_filter(array_map('trim', $lines), fn($l) => $l !== '');
        $lines = array_values($lines);

        // একটাই লাইন — কিন্তু ভেতরে •, -, ; থাকলে সেটাও ভাঙি
        if (count($lines) === 1) {
            $single = $lines[0];
            // bullet চিহ্ন (•) বা " - " দিয়ে একাধিক আইটেম থাকলে
            if (preg_match('/[•·]/u', $single) || substr_count($single, ' - ') >= 1) {
                $parts = preg_split('/\s*[•·]\s*|\s+-\s+/u', $single);
                $parts = array_filter(array_map('trim', $parts), fn($p) => $p !== '');
                $lines = array_values($parts);
            }
        }

        // একটাই আইটেম হলে — প্যারাগ্রাফ
        if (count($lines) <= 1) {
            $clean = e($lines[0] ?? $text);
            return '<p class="jp-text">' . nl2br($clean) . '</p>';
        }

        // একাধিক — list বানাই (শুরুর bullet/dash/number চিহ্ন সরিয়ে)
        $items = '';
        foreach ($lines as $line) {
            // শুরুর •, -, *, 1. 2) ইত্যাদি সরাই
            $clean = preg_replace('/^\s*([•·\-\*]|\d+[\.\)])\s*/u', '', $line);
            $clean = trim($clean);
            if ($clean === '') continue;
            $items .= '<li>' . e($clean) . '</li>';
        }

        return '<ul class="jp-list">' . $items . '</ul>';
    }
}