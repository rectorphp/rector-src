<?php

// inspired at https://github.com/phpstan/phpstan-src/commit/87897c2a4980d68efa1c46049ac2eefe767ec946#diff-e897e523125a694bd8ea69bf83374c206803c98720c46d7401b7a7cf53915a26

declare(strict_types=1);

use Nette\Utils\Strings;
use Rector\Core\Console\Style\SymfonyStyleFactory;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;

$possiblePaths = [
    // rector-src
    __DIR__ . '/../vendor/autoload.php',
    // rector package dependency
    __DIR__ . '/../../../../vendor/autoload.php',
];

foreach ($possiblePaths as $possiblePath) {
    if (! file_exists($possiblePath)) {
        continue;
    }

    require $possiblePath;
    break;
}


$buildDirectory = $argv[1];

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle = $symfonyStyleFactory->create();

if (! is_string($buildDirectory)) {
    $errorMessage = 'Provide build directory path as an argument, e.g. "php build-preload.php rector-build-directory"';
    $symfonyStyle->error($errorMessage);
    exit(Command::FAILURE);
}

$preloadBuilder = new PreloadBuilder();
$preloadBuilder->buildPreloadScript($buildDirectory, $buildDirectory . '/preload.php');
$preloadBuilder->buildPreloadScript($buildDirectory, $buildDirectory . '/preload-split-package.php');

final class PreloadBuilder
{
    /**
     * @var string
     */
    private const PRELOAD_FILE_TEMPLATE = <<<'CODE_SAMPLE'
<?php

declare(strict_types=1);


CODE_SAMPLE;

    /**
     * @var int
     */
    private const PRIORITY_LESS_FILE_POSITION = -1;

    /**
     * These files are parent to another files, so they have to be included first
     * See https://github.com/rectorphp/rector/issues/6709 for more
     *
     * @var string[]
     */
    private const HIGH_PRIORITY_FILES = [
        // nikic/php-parser
        'Node.php',
        'NodeAbstract.php',
        'Expr.php',
        'NodeVisitor.php',
        'NodeVisitorAbstract.php',
        'Lexer.php',
        'TokenEmulator.php',
        'KeywordEmulator.php',
        'Comment.php',
        'PrettyPrinterAbstract.php',
        'Parser.php',
        'ParserAbstract.php',
        'ErrorHandler.php',
        'Stmt.php',
        'FunctionLike.php',
        'ClassLike.php',
        'Builder.php',
        'TraitUseAdaptation.php',
        'ComplexType.php',
        'CallLike.php',
        'AssignOp.php',
        'BinaryOp.php',
        'Name.php',
        'Scalar.php',
        'MagicConst.php',
        'NodeTraverserInterface.php',
        'Declaration.php',
        'Builder/FunctionLike.php',
        'Stmt/FunctionLike.php',

        // phpstan/phpdoc-parser
        'NodeAttributes.php',
        'ConstExprNode.php',
        'PhpDocTagValueNode.php',
        'TypeNode.php',
    ];

    public function buildPreloadScript(string $buildDirectory, string $preloadFile): void
    {
        $this->buildPreloadPhpParser($buildDirectory, $preloadFile);
        $this->buildPreloadPhpDocParser($buildDirectory, $preloadFile);
    }

    private function buildPreloadPhpDocParser(string $buildDirectory, string $preloadFile): void
    {
        $vendorDir = $buildDirectory . '/vendor';
        if (! is_dir($vendorDir . '/phpstan/phpdoc-parser')) {
            return;
        }

        // 1. fine phpdoc-parser file infos
        $fileInfos = $this->findPhpDocParserFilesAndSortThem($vendorDir);

        // 2. create preload.php from provided files
        $preloadFileContent = $this->createPreloadFileContent($fileInfos, true);

        file_put_contents($preloadFile, $preloadFileContent, FILE_APPEND);
    }

    private function buildPreloadPhpParser(string $buildDirectory, string $preloadFile): void
    {
        $vendorDir = $buildDirectory . '/vendor';
        if (! is_dir($vendorDir . '/nikic/php-parser/lib/PhpParser')) {
            return;
        }

        // 1. fine php-parser file infos
        $fileInfos = $this->findPhpParserFilesAndSortThem($vendorDir);

        // 2. create preload.php from provided files
        $preloadFileContent = $this->createPreloadFileContent($fileInfos);

        file_put_contents($preloadFile, $preloadFileContent);
    }

    /**
     * @return SplFileInfo[]
     */
    private function findPhpParserFiles(string $vendorDir): array
    {
        $finder = (new Finder())
            ->files()
            ->name('*.php')
            ->in($vendorDir . '/nikic/php-parser/lib/PhpParser')
            ->notPath('#\/tests\/#')
            ->notPath('#\/config\/#')
            ->notPath('#\/set\/#')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @return SplFileInfo[]
     */
    private function findPhpDocParserFiles(string $vendorDir): array
    {
        $finder = (new Finder())
            ->files()
            ->name('*.php')
            ->in($vendorDir . '/phpstan/phpdoc-parser')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }

    /**
     * @param SplFileInfo[] $fileInfos
     */
    private function createPreloadFileContent(array $fileInfos, bool $append = false): string
    {
        $preloadFileContent = $append ? '' : self::PRELOAD_FILE_TEMPLATE;

        foreach ($fileInfos as $fileInfo) {
            $realPath = $fileInfo->getRealPath();
            if ($realPath === false) {
                continue;
            }

            $preloadFileContent .= $this->createRequireOnceFilePathLine($realPath);
        }

        return $preloadFileContent;
    }

    /**
     * @param SplFileInfo[] $fileInfos
     */
    private function createPreloadFileContentForSplitPackage(array $fileInfos): string
    {
        $preloadFileContent = self::PRELOAD_FILE_TEMPLATE;

        foreach ($fileInfos as $fileInfo) {
            $realPath = $fileInfo->getRealPath();
            if ($realPath === false) {
                continue;
            }

            $preloadFileContent .= $this->createRequireOnceFilePathLineForSplitPackage($realPath);
        }

        return $preloadFileContent;
    }

    private function createRequireOnceFilePathLineForSplitPackage(string $realPath): string
    {
        if (! str_contains($realPath, 'vendor')) {
            $filePath = '/src/' . Strings::after($realPath, '/src/');
            return "require_once __DIR__ . '" . $filePath . "';" . PHP_EOL;
        }

        $filePath = '/../../../vendor/' . Strings::after($realPath, 'vendor/');
        return "require_once __DIR__ . '" . $filePath . "';" . PHP_EOL;
    }

    private function createRequireOnceFilePathLine(string $realPath): string
    {
        if (! str_contains($realPath, 'vendor')) {
            $filePath = '/src/' . Strings::after($realPath, '/src/');
            return "require_once __DIR__ . '" . $filePath . "';" . PHP_EOL;
        }

        $filePath = '/vendor/' . Strings::after($realPath, 'vendor/');
        return "require_once __DIR__ . '" . $filePath . "';" . PHP_EOL;
    }

    private function matchFilePriorityPosition(SplFileInfo $splFileInfo): int
    {
        // to make <=> operator work
        $highPriorityFiles = array_reverse(self::HIGH_PRIORITY_FILES);

        $fileRealPath = $splFileInfo->getRealPath();

        // file not found, e.g. in rector-src dev dependency
        if ($fileRealPath === false) {
            return 0;
        }

        foreach ($highPriorityFiles as $position => $highPriorityFile) {
            if (str_ends_with($fileRealPath, '/' . $highPriorityFile)) {
                return $position;
            }
        }

        return self::PRIORITY_LESS_FILE_POSITION;
    }

    private function findPhpDocParserFilesAndSortThem(string $vendorDir): array
    {
        // 1. fine php-parser file infos
        $fileInfos = $this->findPhpDocParserFiles($vendorDir);

        // 2. put first-class usages first
        usort($fileInfos, function (SplFileInfo $firstFileInfo, SplFileInfo $secondFileInfo): int {
            $firstFilePosition = $this->matchFilePriorityPosition($firstFileInfo);
            $secondFilePosition = $this->matchFilePriorityPosition($secondFileInfo);

            return $secondFilePosition <=> $firstFilePosition;
        });

        return $fileInfos;
    }

    /**
     * @return SplFileInfo[]
     */
    private function findPhpParserFilesAndSortThem(string $vendorDir): array
    {
        // 1. fine php-parser file infos
        $fileInfos = $this->findPhpParserFiles($vendorDir);

        // 2. put first-class usages first
        usort($fileInfos, function (SplFileInfo $firstFileInfo, SplFileInfo $secondFileInfo): int {
            $firstFilePosition = $this->matchFilePriorityPosition($firstFileInfo);
            $secondFilePosition = $this->matchFilePriorityPosition($secondFileInfo);

            return $secondFilePosition <=> $firstFilePosition;
        });

        $stmtsAwareInterface = new SplFileInfo(__DIR__ . '/../src/Contract/PhpParser/Node/StmtsAwareInterface.php');
        array_splice($fileInfos, 1, 0, [$stmtsAwareInterface]);

        return $fileInfos;
    }
}
