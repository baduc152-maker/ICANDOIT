<?php

namespace App\Support;

/**
 * Chuyển đổi giá trị giữa ô nhập trên trang quản trị và dữ liệu lưu trữ.
 */
class ContentField
{
    /**
     * Giá trị đã lưu -> chuỗi hiển thị trong ô nhập.
     */
    public static function toInput(array $field, mixed $value): mixed
    {
        return match ($field['type']) {
            'checkbox' => $value ? '1' : '0',
            'lines' => is_array($value) ? implode("\n", $value) : (string) $value,
            'paragraphs' => is_array($value) ? implode("\n\n", $value) : (string) $value,
            'pairs' => is_array($value)
                ? implode("\n", array_map(
                    fn ($pair) => ($pair['title'] ?? '').' '.SiteContentSchema::PAIR_SEPARATOR.' '.($pair['text'] ?? ''),
                    $value
                ))
                : (string) $value,
            default => is_scalar($value) ? (string) $value : '',
        };
    }

    /**
     * Dữ liệu người dùng nhập -> giá trị lưu trữ.
     */
    public static function fromInput(array $field, mixed $raw): mixed
    {
        $text = is_string($raw) ? self::normalise($raw) : $raw;

        return match ($field['type']) {
            'checkbox' => (bool) $raw,
            'number' => (int) $raw,
            'lines' => self::splitLines($text),
            'paragraphs' => self::splitParagraphs($text),
            'pairs' => self::splitPairs($text),
            default => is_string($text) ? trim($text) : $text,
        };
    }

    /**
     * @return array<int, string>
     */
    private static function splitLines(string $text): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $text)), fn ($line) => $line !== ''));
    }

    /**
     * @return array<int, string>
     */
    private static function splitParagraphs(string $text): array
    {
        $parts = preg_split('/\n\s*\n/', $text) ?: [];

        return array_values(array_filter(
            array_map(fn ($part) => trim(preg_replace('/\s*\n\s*/', ' ', $part) ?? ''), $parts),
            fn ($part) => $part !== ''
        ));
    }

    /**
     * @return array<int, array{title: string, text: string}>
     */
    private static function splitPairs(string $text): array
    {
        $pairs = [];

        foreach (self::splitLines($text) as $line) {
            $parts = explode(SiteContentSchema::PAIR_SEPARATOR, $line, 2);

            $pairs[] = [
                'title' => trim($parts[0]),
                'text' => trim($parts[1] ?? ''),
            ];
        }

        return $pairs;
    }

    private static function normalise(string $text): string
    {
        return str_replace(["\r\n", "\r"], "\n", $text);
    }
}
