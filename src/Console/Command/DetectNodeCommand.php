<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use Rector\CustomRules\NodeVisitor\NodeClassNodeVisitor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class DetectNodeCommand extends Command
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('detect-node');
        $this->setDescription('[CUSTOM] Detect a node for provided PHP content to help out with custom rule building');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $question = new Question('Give a PHP content you want to detect node for (simpler the better)');
        $phpContents = $this->symfonyStyle->askQuestion($question);

        //        if ($nodes === null) {
        //            $this->symfonyStyle->warning('We could not resolve the node. Try simpler syntax or add ;');
        //
        //            return self::FAILURE;
        //        }

        if (! str_starts_with($phpContents, '<?php')) {
            // prepend with PHP opening tag to make parse PHP code
            $phpContents = '<?php ' . $phpContents;
        }

        $parserFactory = new ParserFactory();
        $parser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        try {
            $nodes = $parser->parse($phpContents);
        } catch (Throwable $throwable) {
            // try few restore approaches
        }

        $nodeTraverser = new NodeTraverser();
        $nodeClassNodeVisitor = new NodeClassNodeVisitor();
        $nodeTraverser->addVisitor($nodeClassNodeVisitor);
        $nodeTraverser->traverse($nodes);

        dump($nodeClassNodeVisitor->getFoundNodeClasses());

        die;
    }
}
