<?php

namespace CompanyA;

use CompanyB\Storage;

class Bar
{
    /**
     * @var Storage
     */
    protected $storage;

    public function __construct(
        Storage $storage
    ) {
        \Log::error('Some loggin');

        $this->storage        = $storage;
    }

    private function foo(): string
    {
        new Foo($this->storage);
    }
}


?>
