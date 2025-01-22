<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipAlwaysStringIfElseDomElement {
    public function getTitle(string $html): string {
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        $titleElement = $dom->getElementsByTagName('title')->item(0);
        if ($titleElement?->nodeValue !== null) {
            return trim($titleElement->nodeValue);
        } else {
            return 'Unnamed document';
        }
    }
}