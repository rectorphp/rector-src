<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Nette\Utils\Strings;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\PhpParser\Parser\RectorParser;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\ValueObject\Application\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @experimental since 1.1.2
 */
final class BladeCommand extends Command
{
    /**
     * @var string
     */
    private const INNER_CONTENT_REGEX = '#{{\s+(?<content>.*?)\s+}}#';

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly RectorNodeTraverser $rectorNodeTraverser,
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly RectorParser $rectorParser,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('blade');

        $this->addArgument('paths', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'Paths to Blade templates');

        $this->addOption(
            Option::DRY_RUN,
            Option::DRY_RUN_SHORT,
            InputOption::VALUE_NONE,
            'Only see the diff of changes, do not change the files.'
        );

        $this->setDescription('Upgrade Blade templates');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // skip rather global rules, not useful here
        SimpleParameterProvider::addParameter(Option::SKIP, DeclareStrictTypesRector::class);

        $paths = $input->getArgument('paths');

        // find all files in templates directory
        $bladeFileInfos = $this->findBladeTemplates($paths);

        if ($bladeFileInfos === []) {
            $this->symfonyStyle->warning('Unable to find any Blade templates');
            return self::FAILURE;
        }

        $this->symfonyStyle->note(
            sprintf('Processing %d Blade file%s', count($bladeFileInfos), count($bladeFileInfos) > 1 ? 's' : '')
        );

        foreach ($bladeFileInfos as $bladeFileInfo) {
            $this->currentFileProvider->setFile(new File($bladeFileInfo->getRealPath(), $bladeFileInfo->getContents()));

            $fileContents = $bladeFileInfo->getContents();

            // 1. code inside {{ ... }}
            $variableMatches = Strings::replace($fileContents, self::INNER_CONTENT_REGEX, function (array $match) {
                $originalContent = $match['content'];
                $originalStmts = $this->rectorParser->parseString('<?php ' . $originalContent . ';');

                $changedStmts = $this->rectorNodeTraverser->traverse($originalStmts);
                $printedContent = $this->betterStandardPrinter->print($changedStmts);

                dump($originalContent);
                dump(rtrim($printedContent, ';'));

                // 2. apply file process on the stmts
                // @todo change functio nname or extract the trnaslation function
                //                dump($originalStmts);

                // 3. print back
                // @todo
            });

            // 2. code inside @php ... @endphp
            die;

            dump($variableMatches);
            dump($bladeFileInfo->getRealPath());
        }

        return Command::SUCCESS;
    }

    /**
     * @param string[] $paths
     * @return SplFileInfo[]
     */
    private function findBladeTemplates(array $paths): array
    {
        $finder = Finder::create()
            ->files()
            ->in($paths)
            ->name('*.blade.php')
            ->sortByName();

        return iterator_to_array($finder->getIterator());
    }
}
