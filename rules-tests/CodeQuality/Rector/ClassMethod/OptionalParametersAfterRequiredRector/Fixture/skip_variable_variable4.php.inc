<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

abstract class SkipVariableVariable4
{
    private static function loadCurveByParam(\DOMXPath $xpath)
    {
        $$param = $$bar = self::decodeValue($result->getItem()->getTextContent());
    }
}
