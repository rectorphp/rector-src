<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Rector\PhpParser\Parser\SimplePhpParser;
use Rector\Util\NodePrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class DetectNodeCommand extends Command
{
    public function __construct(
        private readonly SimplePhpParser $simplePhpParser,
        private readonly NodePrinter $nodePrinter,
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('detect-node');
        $this->setDescription('Detects node for provided PHP content');
        $this->addOption('loop', null, InputOption::VALUE_NONE, 'Keep open so you can try multiple inputs');

        $this->setAliases(['dump-node']);

        // @todo invoke https://github.com/matthiasnoback/php-ast-inspector/
        // $this->addOption('file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ((bool) $input->getOption('loop')) {
            while (true) {
                $this->askQuestionAndDumpNode();
            }
        }

        $this->askQuestionAndDumpNode();

        return self::SUCCESS;
    }

    private function askQuestionAndDumpNode(): void
    {
        $question = new Question('Write short PHP code snippet');
        $phpContents = $this->symfonyStyle->askQuestion($question);

        try {
            $nodes = $this->simplePhpParser->parseString($phpContents);
        } catch (Throwable) {
            $this->symfonyStyle->warning('Provide valid PHP code');
            return;
        }

        $this->nodePrinter->printNodes($nodes);
    }
}
