<?php

declare(strict_types=1);

namespace Rector\Naming\ParamRenamer;

use Rector\BetterPhpDocParser\PhpDocManipulator\PropertyDocBlockManipulator;
use Rector\Naming\ValueObject\ParamRename;
use Rector\Naming\VariableRenamer;

final class ParamRenamer
{
    public function __construct(
        private readonly VariableRenamer $variableRenamer,
        private readonly PropertyDocBlockManipulator $propertyDocBlockManipulator
    ) {
    }

    public function rename(ParamRename $paramRename): void
    {
        // 1. rename param
        $paramRename->getVariable()
            ->name = $paramRename->getExpectedName();

        // 2. rename param in the rest of the method
        $this->variableRenamer->renameVariableInFunctionLike(
            $paramRename->getFunctionLike(),
            $paramRename->getCurrentName(),
            $paramRename->getExpectedName(),
            null
        );

        // 3. rename @param variable in docblock too
        $this->propertyDocBlockManipulator->renameParameterNameInDocBlock($paramRename);
    }
}
