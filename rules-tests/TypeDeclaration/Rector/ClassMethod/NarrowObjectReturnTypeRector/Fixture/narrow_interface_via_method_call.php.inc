<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\PaymentInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\StripePayment;

final class PaymentFactory
{
    public function create(): PaymentInterface
    {
        return $this->createStripePayment();
    }

    private function createStripePayment(): StripePayment
    {
        return new StripePayment();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\PaymentInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\StripePayment;

final class PaymentFactory
{
    public function create(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\StripePayment
    {
        return $this->createStripePayment();
    }

    private function createStripePayment(): StripePayment
    {
        return new StripePayment();
    }
}

?>