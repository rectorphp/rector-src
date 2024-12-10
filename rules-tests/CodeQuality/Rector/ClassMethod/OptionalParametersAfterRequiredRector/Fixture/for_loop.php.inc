<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class SkipCountForLoop
{
    public function __invoke($em)
    {
        $visitedProduct1 = null;
        $visitedProduct2 = null;
        $visitedProduct3 = null;

        $products = $em->getRepository(Product::class)->findAll();
        $visitedProducts = [];
        for ($i = 0; $i < count($products), count($visitedProducts) <= 3; ++$i) {
            $product = $products[$i];
            if (!in_array($product, $exclude)) {
                $varName = 'visitedProduct' . ($i + 1);
                $visitedProducts[$varName] = $product;
            }
        }

        extract($visitedProducts);
    }
}
