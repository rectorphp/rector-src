<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

enum SkipperResult
{
    case noSkip;
    case skipNode;
    case skipTree;
}
