<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;
abstract class SkipVariableVariable
{
    private static function loadCurveByParam(\DOMXPath $xpath)
    {
        $$param = self::decodeValue($result->item(0)->textContent);
    }
}
