<?php

final class Cart
{
    public static function create(string $name = 'default', Product ...$products)
    {
        $cart = new Cart($name);
        $cart->products = $products;
        return $cart;
    }
}

$cart = Cart::create('trial', new Product('A'), new Product('B'));
