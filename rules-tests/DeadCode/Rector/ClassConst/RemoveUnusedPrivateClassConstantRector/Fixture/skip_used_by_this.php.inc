<?php declare(strict_types=1);

final class SkipUsedByThis
{
    private const string SOME_CONST = '1';

    public function hello(): string
    {
        return $this::SOME_CONST;
    }
}
