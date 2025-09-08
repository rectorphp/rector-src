<?php
declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\Class_\FinalPropertyPromotionRector\Fixture;

$obj = new class {
    public function __construct(
        /** @final */
        public string $id
    ){}
};
?>