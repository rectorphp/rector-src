<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source;

final class NonTypedApp
{
    public function callDynamic(string $func): void
    {
    }
}

$a = new NonTypedApp();
$a->callDynamic('phpinfo');