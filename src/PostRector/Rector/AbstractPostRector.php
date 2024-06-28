<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node\Stmt;
use PhpParser\NodeVisitorAbstract;
use Rector\PostRector\Contract\Rector\PostRectorInterface;
use Rector\ValueObject\Application\File;
use Webmozart\Assert\Assert;

abstract class AbstractPostRector extends NodeVisitorAbstract implements PostRectorInterface
{
    private File|null $file = null;

    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(array $stmts): bool
    {
        return true;
    }

    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    public function getFile(): File
    {
        Assert::isInstanceOf($this->file, File::class);

        return $this->file;
    }
}
