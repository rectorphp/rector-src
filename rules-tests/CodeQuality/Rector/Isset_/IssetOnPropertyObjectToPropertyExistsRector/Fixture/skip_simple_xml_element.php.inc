<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

use SimpleXMLElement;

final class SkipSimpleXmlElement
{
    public function x(SimpleXMLElement $svg) {
        $svgAttributes = $svg->attributes();

        if (isset($svgAttributes->width) && isset($svgAttributes->height)) {
            return true;
        }

        return isset($svg->viewBox);
    }
}
