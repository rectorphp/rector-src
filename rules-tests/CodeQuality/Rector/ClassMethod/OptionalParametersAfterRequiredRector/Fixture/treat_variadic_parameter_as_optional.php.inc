<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class Cart
{
    public static function create(string $name = 'default', Product ...$products)
    {
        $cart = new Cart($name);
        $cart->products = $products;
        return $cart;
    }
}
