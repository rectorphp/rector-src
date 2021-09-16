<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject\Application;

use PhpParser\Node\Stmt;
use Rector\ChangesReporting\ValueObject\RectorWithLineChange;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Symplify\SmartFileSystem\SmartFileInfo;

/**
 * @see \Rector\Core\ValueObjectFactory\Application\FileFactory
 */
final class File
{
    private bool $hasChanged = false;

    private string $originalFileContent;

    private ?FileDiff $fileDiff = null;

    /**
     * @var Stmt[]
     */
    private array $oldStmts = [];

    /**
     * @var Stmt[]
     */
    private array $newStmts = [];

    /**
     * @var mixed[]
     */
    private array $oldTokens = [];

    /**
     * @var RectorWithLineChange[]
     */
    private array $rectorWithLineChanges = [];

    /**
     * @var RectorError[]
     */
    private array $rectorErrors = [];

    public function __construct(
        private SmartFileInfo $smartFileInfo,
        private string $fileContent
    ) {
        $this->originalFileContent = $fileContent;
    }

    public function getSmartFileInfo(): SmartFileInfo
    {
        return $this->smartFileInfo;
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

    public function hasContentChanged(): bool
    {
        return $this->fileContent !== $this->originalFileContent;
    }

    public function getOriginalFileContent(): string
    {
        return $this->originalFileContent;
    }

    public function hasChanged(): bool
    {
        return $this->hasChanged;
    }

    public function changeHasChanged(bool $status): void
    {
        $this->hasChanged = $status;
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
     * @param mixed[] $oldTokens
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
     * @return mixed[]
     */
    public function getOldTokens(): array
    {
        return $this->oldTokens;
    }

    /**
     * @param Stmt[] $newStmts
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

    public function addRectorError(RectorError $rectorError): void
    {
        $this->rectorErrors[] = $rectorError;
    }

    public function hasErrors(): bool
    {
        return $this->rectorErrors !== [];
    }

    /**
     * @return RectorError[]
     */
    public function getErrors(): array
    {
        return $this->rectorErrors;
    }
}
