<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

use Symfony\Component\Console\Style\SymfonyStyle;

final class SkipNonFileSystemIteratorInstance
{
    public function run($input, $output)
    {
        return new SymfonyStyle($input, $output);
    }
}
