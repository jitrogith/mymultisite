<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class TextEditorContent
{
    private const ALLOWED_TAGS = ['a', 'b', 'blockquote', 'br', 'code', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'i', 'img', 'li', 'ol', 'p', 'pre', 's', 'strong', 'u', 'ul'];

    public static function sanitize(string $content): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8"?><div>'.$content.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementsByTagName('div')->item(0);
        if (! $root) {
            return '';
        }

        self::sanitizeChildren($root);

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        if ($result !== '' && ! preg_match('/<[a-z][\s\S]*>/i', $result)) {
            return '<p>'.$result.'</p>';
        }

        return $result;
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        for ($node = $parent->firstChild; $node; ) {
            $next = $node->nextSibling;

            if ($node instanceof DOMElement) {
                if (! in_array(strtolower($node->tagName), self::ALLOWED_TAGS, true)) {
                    if (in_array(strtolower($node->tagName), ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math'], true)) {
                        $parent->removeChild($node);
                    } else {
                        self::sanitizeChildren($node);
                        while ($node->firstChild) {
                            $parent->insertBefore($node->firstChild, $node);
                        }
                        $parent->removeChild($node);
                    }
                } else {
                    self::sanitizeElement($node);
                    self::sanitizeChildren($node);
                }
            }

            $node = $next;
        }
    }

    private static function sanitizeElement(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $allowedAttributes = match ($tag) {
            'a' => ['href', 'title'],
            'img' => ['src', 'alt', 'title'],
            default => [],
        };

        for ($index = $element->attributes->length - 1; $index >= 0; $index--) {
            $attribute = $element->attributes->item($index);
            if (! $attribute || ! in_array(strtolower($attribute->name), $allowedAttributes, true)) {
                if ($attribute) {
                    $element->removeAttribute($attribute->name);
                }
            }
        }

        if ($tag === 'a' && $element->hasAttribute('href') && ! preg_match('#^(https?://|mailto:)#i', $element->getAttribute('href'))) {
            $element->removeAttribute('href');
        }

        if ($tag === 'img' && ! preg_match('#^https://#i', $element->getAttribute('src'))) {
            $element->parentNode?->removeChild($element);
        }
    }
}
