<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

use stdClass;

final class SkipParamReassign
{
    /** @param int|stdClass $item */
    public function process($item) {
        $item = $this->resolve($item);
        $this->send($item);
    }

    /** @param int|stdClass $item */
    private function resolve($item): stdClass {
        if ($item instanceof stdClass) {
            return $item;
        }
        return new stdClass();
    }

    private function send(stdClass $item): void {
    }
}
