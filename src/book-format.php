<?php

/**
 * christianberkman/book-format
 * (C) Christian Berkman 2024
 * MIT License
 */

if (! function_exists('formatAsTitle')) {
    /**
     * Format string as title
     * e.g. "The Beautiful code" --> "Beautiful Code, The"
     *
     * @param string|null $value
     * @param array<string>|null $articles Array of articles
     * @return string|null
     */
    function formatAsTitle(?string $value, ?array $articles = ['a', 'an', 'the'], $makeSingleSpaces = true): ?string
    {
        if ($value === null) {
            return null;
        }

        $output = trim($value);

        // Replace all double whitespace characters with a single space
        if ($makeSingleSpaces) {
            $output = preg_replace('/(\s)+/', ' ', $output);
        }

        // Move the article to the end of the string
        $articlePattern = implode('|', $articles);
        $pattern = "/^({$articlePattern})\s(.*)/i";
        $match   = preg_match($pattern, $output, $matches);
        if ($match) {
            $output = ucwords($matches[2]) . ', ' . ucfirst($matches[1]);
        } else {
            $output = ucwords($output);
        }

        return $output;
    }
}

if (! function_exists('formatAsAuthor')) {
    /**
     * * Format as author
     * Examples:
     *    W H Shakespeare -> Shakespeare, W.H.
     *    Shakespeare W.H. -> Shakespeare, W.H.
     *    Shakespeare W H -> Shakespear, W.H.
     *    e.t.c.
     *
     * @param string|null $value
     * @return string|null
     */
    function formatAsAuthor(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $output = trim($value);

        // Remove double spaces
        $output = preg_replace('/(\s)+/', ' ', $output);

        // Add space after comma
        $output = preg_replace('/,([a-zA-Z])/', ', $1', $output);

        // Capitalize first in every word
        $output = ucwords($output, ' -/');

        // Make initials
        $output = preg_replace(('/\b([A-Z])\b\.?/'), '$1.', $output);

        // Move initials behind surname
        $output = preg_replace('/^(([A-Z]\. )+)(.*)/', '$3, $1', $output);

        // Add comma after surname
        $output = preg_replace('/( ?([A-Z]\. ?)+)$/', ',$1', $output);

        // Remove double comma
        $output = preg_replace('/,,/', ',', $output);

        return trim($output);
    }
}
