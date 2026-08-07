<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @phpstan-type ConfigArray array{is_dry: bool}
 * @phpstan-type CustomConfig array{}
 */
interface SkipUnionOfPHPStanTypeAliases
{
    /** @return ConfigArray|CustomConfig */
    public function getConfig(): array;
}
