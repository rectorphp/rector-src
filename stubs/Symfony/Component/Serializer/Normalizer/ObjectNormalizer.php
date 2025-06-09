<?php

declare(strict_types=1);

namespace Symfony\Component\Serializer\Normalizer;

if (class_exists('Symfony\Component\Serializer\Normalizer\ObjectNormalizer')) {
    return;
}

class ObjectNormalizer
{
    public const SKIP_NULL_VALUES = 'skip_null_values';
}
