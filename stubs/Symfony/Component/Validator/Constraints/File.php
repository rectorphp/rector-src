<?php

namespace Symfony\Component\Validator\Constraints;

if (class_exists('Symfony\Component\Validator\Constraints\File')) {
    return;
}

// @see https://github.com/symfony/validator/blob/94e7465b1271ba024bd96a424da037e3390184a5/Constraints/File.php

class File
{
    public function __construct(
        int|string|null $maxSize = null
    ) {
    }
}
