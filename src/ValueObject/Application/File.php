<?php

declare(strict_types=1);

namespace Rector\ValueObject\Application;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Exception\ShouldNotHappenException;
use Rector\ValueObject\Reporting\FileDiff;

final class File
{
    private bool $hasChanged = false;

    private readonly string $originalFileContent;

    private ?FileDiff $fileDiff = null;

    /**
     * @var Node[]
     */
    private array $oldStmts = [];

    /**
     * @var Node[]
     */
    private array $newStmts = [];

    /**
     * @var array<int, array{int, string, int}|string>
     */
    private array $oldTokens = [];

    /**
     * @var RectorWithLineChange[]
     */
    private array $rectorWithLineChanges = [];

    public function __construct(
        private readonly string $filePath,
        private string $fileContent
    ) {
        $this->originalFileContent = $fileContent;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileContent(): string
    {
        return $this->fileContent;
    }

    public function changeFileContent(string $newFileContent): void
    {
        if ($this->fileContent === $newFileContent) {
            return;
        }

        $this->fileContent = $newFileContent;
        $this->hasChanged = true;
    }

    public function getOriginalFileContent(): string
    {
        return $this->originalFileContent;
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }

    public function resetHasChanged(): void
    {
        $this->hasChanged = false;
    }

    public function setFileDiff(FileDiff $fileDiff): void
    {
        $this->fileDiff = $fileDiff;
    }

    public function getFileDiff(): ?FileDiff
    {
        return $this->fileDiff;
    }

    /**
     * @param Stmt[] $newStmts
     * @param Stmt[] $oldStmts
     * @param array<int, array{int, string, int}|string> $oldTokens
     */
    public function hydrateStmtsAndTokens(array $newStmts, array $oldStmts, array $oldTokens): void
    {
        if ($this->oldStmts !== []) {
            throw new ShouldNotHappenException('Double stmts override');
        }

        $this->oldStmts = $oldStmts;
        $this->newStmts = $newStmts;
        $this->oldTokens = $oldTokens;
    }

    /**
     * @return Stmt[]
     */
    public function getOldStmts(): array
    {
        return $this->oldStmts;
    }

    /**
     * @return Stmt[]
     */
    public function getNewStmts(): array
    {
        return $this->newStmts;
    }

    /**
     * @return array<int, array{int, string, int}|string>
     */
    public function getOldTokens(): array
    {
        return $this->oldTokens;
    }

    /**
     * @param Node[] $newStmts
     */
    public function changeNewStmts(array $newStmts): void
    {
        $this->newStmts = $newStmts;
    }

    public function addRectorClassWithLine(RectorWithLineChange $rectorWithLineChange): void
    {
        $this->rectorWithLineChanges[] = $rectorWithLineChange;
    }

    /**
     * @return RectorWithLineChange[]
     */
    public function getRectorWithLineChanges(): array
    {
        return $this->rectorWithLineChanges;
    }
}
