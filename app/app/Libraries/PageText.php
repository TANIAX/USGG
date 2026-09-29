<?php

namespace App\Libraries;

/**
 * Text of the pages written in the administration (charter, privacy policy), in a simple Markdown:
 *  - "## Title" and "### Subtitle" (the titles "##" make the table of contents),
 *  - lines starting with "- " (list), an empty line between two paragraphs,
 *  - **bold**, *italic*, [text of a link](https://...), and the variables {email} (contact address) and {site}.
 * Everything is escaped first: the text can not contain HTML. Only the links http(s), mailto, tel and relative (/...) are kept.
 */
class PageText
{
    /**
     * HTML of the text (to be displayed in an element with the class "page-text", see input.css).
     */
    public static function toHtml(string $text): string
    {
        $text = self::replaceVariables(str_replace(["\r\n", "\r"], "\n", $text));
        $html = [];
        $paragraph = [];
        $list = [];

        $flushParagraph = function () use (&$paragraph, &$html) {
            if ($paragraph)
                $html[] = '<p>' . implode('<br>', array_map([self::class, 'inline'], $paragraph)) . '</p>';
            $paragraph = [];
        };
        $flushList = function () use (&$list, &$html) {
            if ($list)
                $html[] = '<ul>' . implode('', array_map(fn($item) => '<li>' . self::inline($item) . '</li>', $list)) . '</ul>';
            $list = [];
        };

        foreach (explode("\n", $text) as $line) {
            $line = rtrim($line);
            if (preg_match('/^(#{2,3})\s+(.+)$/u', $line, $match)) {
                $flushParagraph();
                $flushList();
                $tag = strlen($match[1]) === 2 ? 'h2' : 'h3';
                $id = $tag === 'h2' ? ' id="' . esc(self::anchor($match[2]), 'attr') . '"' : '';
                $html[] = "<$tag$id>" . self::inline($match[2]) . "</$tag>";
            } elseif (preg_match('/^\s*[-*]\s+(.+)$/u', $line, $match)) {
                $flushParagraph();
                $list[] = $match[1];
            } elseif (trim($line) === '') {
                $flushParagraph();
                $flushList();
            } else {
                $flushList();
                $paragraph[] = trim($line);
            }
        }
        $flushParagraph();
        $flushList();

        return implode("\n", $html);
    }

    /**
     * Titles "##" of the text, for the table of contents: [[anchor, title]]
     */
    public static function headings(string $text): array
    {
        preg_match_all('/^##\s+(.+)$/mu', self::replaceVariables($text), $matches);
        return array_map(fn($title) => [self::anchor(trim($title)), trim(preg_replace('/\*+/', '', $title))], $matches[1]);
    }

    /**
     * Bold, italic and links of a line (escaped first)
     */
    private static function inline(string $text): string
    {
        $text = esc($text);
        $text = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $text);
        $text = preg_replace('/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/u', '<em>$1</em>', $text);

        return preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/u', function ($match) {
            // The url was escaped with the rest of the text: its "&amp;" are kept as they are, valid in an attribute
            $url = $match[2];
            if (!preg_match('#^(https?://|mailto:|tel:|/)#i', html_entity_decode($url, ENT_QUOTES)))
                return $match[1];
            $external = preg_match('#^https?://#i', $url) && !str_starts_with($url, rtrim(base_url(), '/'));
            return '<a href="' . $url . '"' . ($external ? ' target="_blank" rel="noopener"' : '') . '>' . $match[1] . '</a>';
        }, $text);
    }

    private static function replaceVariables(string $text): string
    {
        return strtr($text, [
            '{email}' => config('Site')->contactEmail,
            '{site}' => rtrim(base_url(), '/'),
        ]);
    }

    /**
     * Anchor of a title: "Qui sommes-nous ?" -> "qui-sommes-nous"
     */
    private static function anchor(string $title): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', strip_tags(preg_replace('/\*+/', '', $title))) ?: $title;
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($ascii)), '-') ?: 'section';
    }
}
