<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\CodingStyle\ClassNameImport\ClassNameImportSkipper;
use Rector\CodingStyle\Node\NameImporter;
use Rector\Core\Configuration\Option;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockNameImporter;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NameImportingPostRector extends AbstractPostRector
{
    public function __construct(
        private ParameterProvider $parameterProvider,
        private NameImporter $nameImporter,
        private DocBlockNameImporter $docBlockNameImporter,
        private ClassNameImportSkipper $classNameImportSkipper,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private ReflectionProvider $reflectionProvider,
        private CurrentFileProvider $currentFileProvider,
        private BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $this->parameterProvider->provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return null;
        }

        $file = $this->currentFileProvider->getFile();

        if ($node instanceof Name) {
            if (! $file instanceof File) {
                return null;
            }

            if (! $this->shouldApply($file)) {
                return null;
            }

            return $this->processNodeName($node, $file);
        }

        if (! $this->parameterProvider->provideBoolParameter(Option::IMPORT_DOC_BLOCKS)) {
            return null;
        }

        if ($file instanceof File && ! $this->shouldApply($file)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $this->docBlockNameImporter->importNames($phpDocInfo->getPhpDocNode(), $node);

        return $node;
    }

    public function getPriority(): int
    {
        // this must run after NodeRemovingPostRector, sine renamed use imports can block next import
        return 600;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Imports fully qualified names', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(App\AnotherClass $anotherClass)
    {
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
use App\AnotherClass;

class SomeClass
{
    public function run(AnotherClass $anotherClass)
    {
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function processNodeName(Name $name, File $file): ?Node
    {
        if ($name->isSpecialClassName()) {
            return $name;
        }

        // @todo test if old stmts or new stmts! or both? :)
        /** @var Use_[] $currentUses */
        $currentUses = $this->betterNodeFinder->findInstanceOf($file->getNewStmts(), Use_::class);

        if ($this->shouldImportName($name, $file, $currentUses)) {
            return $this->nameImporter->importName($name, $file, $currentUses);
        }

        return null;
    }

    /**
     * @param Use_[] $currentUses
     */
    private function shouldImportName(Name $name, File $file, array $currentUses): bool
    {
        if (substr_count($name->toCodeString(), '\\') <= 1) {
            return true;
        }

        if (! $this->classNameImportSkipper->isFoundInUse($name, $currentUses)) {
            return true;
        }

        if ($this->classNameImportSkipper->isAlreadyImported($name, $currentUses)) {
            return true;
        }

        return $this->reflectionProvider->hasFunction(new Name($name->getLast()), null);
    }

    private function shouldApply(File $file): bool
    {
        if ($file->hasContentChanged()) {
            return true;
        }

        return ! $this->parameterProvider->provideBoolParameter(Option::APPLY_AUTO_IMPORT_NAMES_ON_CHANGED_FILES_ONLY);
    }
}
