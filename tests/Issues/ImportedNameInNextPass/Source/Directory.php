<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ImportedNameInNextPass\Source;

enum Directory: string
{
    case One = 'one';
}
