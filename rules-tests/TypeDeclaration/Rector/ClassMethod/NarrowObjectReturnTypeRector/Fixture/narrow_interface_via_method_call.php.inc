<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

interface PaymentInterface
{
}

final class StripePayment implements PaymentInterface
{
}

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

interface PaymentInterface
{
}

final class StripePayment implements PaymentInterface
{
}

final class PaymentFactory
{
    public function create(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\StripePayment
    {
        return $this->createStripePayment();
    }

    private function createStripePayment(): StripePayment
    {
        return new StripePayment();
    }
}

?>