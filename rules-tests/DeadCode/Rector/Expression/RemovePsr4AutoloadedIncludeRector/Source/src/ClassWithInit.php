<?php

declare(strict_types=1);

namespace App;

final class ClassWithInit
{
    public static function init(): void
    {
    }
}

ClassWithInit::init();
