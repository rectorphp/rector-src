<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\OrderRepository;

final class InitAssignMethodCall
{
    public function fetchAllForBuyer(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->fetchAllForBuyer();

        foreach ($orders as $order) {

        }

        return $orders;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\OrderRepository;

final class InitAssignMethodCall
{
    /**
     * @return mixed[]
     */
    public function fetchAllForBuyer(OrderRepository $orderRepository): array
    {
        $orders = $orderRepository->fetchAllForBuyer();

        foreach ($orders as $order) {

        }

        return $orders;
    }
}

?>
