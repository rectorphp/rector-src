<?php

namespace Rector\Tests\Issues\ClassReflectionRefresh\Fixture;

use Rector\Tests\Issues\ClassReflectionRefresh\Source\SomeTrait;

final class SkipAlreadyUsedImportedTrait
{
    use SomeTrait;
}
