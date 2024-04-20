<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

function skip_correct_optional_after_required($required, $optional = 1)
{
}

skip_correct_optional_after_required($required, $optional);
