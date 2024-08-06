<?php

namespace Symfony\Component\Validator\Constraints;

if (class_exists('Symfony\Component\Validator\Constraints\Length')) {
    return;
}

// @see https://github.com/symfony/validator/blob/94e7465b1271ba024bd96a424da037e3390184a5/Constraints/Length.php

class Length
{
    public function __construct(
        ?int $min,
        ?int $max
    ) {
    }
}
