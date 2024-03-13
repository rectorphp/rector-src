<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use RuntimeException;
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
        $this->askQuestionAndDumpNode((bool) $input->getOption('loop'));

        return self::SUCCESS;
    }


    private function askQuestionAndDumpNode(bool $loop): void
    {
        $question = new Question('Write short PHP code snippet', '!q');
        $question->setMultiline(true);
        $question->setValidator(static function ($answer) use ($loop) : string {
            if ($loop && trim($answer) === '') {
                throw new RuntimeException('Invalid input; please provide valid PHP code or type "!q" to quit');
            }
            return $answer;
        });

        do {
            $phpContents = $this->symfonyStyle->askQuestion($question);

            if (str_starts_with((string) $phpContents, '!q')) {
                $this->symfonyStyle->success('Goodbye');
                return;
            }

            try {
                $nodes = $this->simplePhpParser->parseString($phpContents);
            } catch (Throwable) {
                $this->symfonyStyle->warning('Invalid input; please provide valid PHP code');
                continue;
            }

            $this->nodePrinter->printNodes($nodes);
        } while ($loop);
    }
}
