<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

/**
 * @see https://3v4l.org/7CEb0
 */
final class SkipByRefMethod {
    private int $monday = 0;
    private int $tuesday = 0;
    // ...

    private function &getRef(int $index): int {
        switch ($index) {
            case 0:
                return $this->monday;
            case 1:
                return $this->tuesday;
            // ...
            default:
                throw new LogicException();
        }
    }

    public function setAtIndex(int $index, int $value) {
        $ref = &$this->getRef($index);
        $ref = $value;
    }
}

?>
