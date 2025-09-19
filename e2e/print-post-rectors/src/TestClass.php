<?php

declare(strict_types=1);

namespace E2e\Parallel\Print\Post\Rectors;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Expr\New_;

class TestClass
{
    /**
     * @var \PhpParser\Node\Name\FullyQualified|null
     */
    private $fullyQualified = null;

    /**
     * @var bool
     */
    private $cond1;

    /**
     * @var \DateTime|null
     */
    private $dateTime = null;

    public function run()
    {
        if ($this->cond1) {
            $this->doSomething();
        } else {
            if ($this->dateTime !== null) {
                $this->doSomething();
            }
        }
    }

    private function doSomething()
    {
    }

    public function getFullyQualified(\PhpParser\Node\Name\FullyQualified $fullyQualified): FullyQualified|null
    {
        if ($this->fullyQualified === null) {
            return $this->fullyQualified;
        }

        $this->fullyQualified = new FullyQualified('foo');

        return new FullyQualified('foo');
    }
}
