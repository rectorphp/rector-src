<?php
declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source;

final class OrderRepository
{
    public function fetchAllForBuyer(): array
    {
        return [];
    }
}
