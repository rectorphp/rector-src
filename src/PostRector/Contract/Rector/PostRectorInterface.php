<?php

declare(strict_types=1);

namespace Rector\PostRector\Contract\Rector;

use PhpParser\NodeVisitor;
use Rector\ValueObject\Application\File;

/**
 * @internal
 */
interface PostRectorInterface extends NodeVisitor
{
    public function setFile(File $file): void;
}
