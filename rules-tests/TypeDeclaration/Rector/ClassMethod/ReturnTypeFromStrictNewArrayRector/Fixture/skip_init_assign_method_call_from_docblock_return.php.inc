<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\OrderRepositoryReturnDocblock;

final class SkipInitAssignMethodCallFromDocblockReturn
{
    public function fetchAllForBuyer(OrderRepositoryReturnDocblock $orderRepository)
    {
        $orders = $orderRepository->fetchAllForBuyer();

        foreach ($orders as $order) {

        }

        return $orders;
    }
}
