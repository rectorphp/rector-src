<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Throwable;
use Nette\Utils\Strings;
use Rector\CustomRules\SimpleNodeDumper;
use Rector\PhpParser\Parser\SimplePhpParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

final class DetectNodeCommand extends Command
{
    /**
     * @var string
     * @see https://regex101.com/r/Fe8n73/1
     */
    private const CLASS_NAME_REGEX = '#(?<class_name>PhpParser(.*?))\(#ms';

    /**
     * @var string
     * @see https://regex101.com/r/uQFuvL/1
     */
    private const PROPERTY_KEY_REGEX = '#(?<key>[\w\d]+)\:#';

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly SimplePhpParser $simplePhpParser,
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

    private function addConsoleColors(string $contents): string
    {
        // decorate class names
        $colorContents = Strings::replace($contents, self::CLASS_NAME_REGEX, static fn(array $match): string => '<fg=green>' . $match['class_name'] . '</>(');

        // decorate keys
        return Strings::replace($colorContents, self::PROPERTY_KEY_REGEX, static fn(array $match): string => '<fg=yellow>' . $match['key'] . '</>:');
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

        $dumpedNodesContents = SimpleNodeDumper::dump($nodes);

        // colorize
        $colorContents = $this->addConsoleColors($dumpedNodesContents);
        $this->symfonyStyle->writeln($colorContents);

        $this->symfonyStyle->newLine();
    }
}
