<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;
abstract class SkipVariableVariable2
{
    private static function loadCurveByParam(\DOMXPath $xpath)
    {
        $$param = $$bar = self::decodeValue($result->item(0)->textContent);
    }
}
