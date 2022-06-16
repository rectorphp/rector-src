<?php declare(strict_types=1);

namespace App;

class Foo {

    /**
     * @param \DateTime|\DateTimeImmutable $date
     * @return string
     */
    public function bar($date) {
        return $date->format('C');
    }
}
