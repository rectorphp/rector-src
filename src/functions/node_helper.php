<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Console\Output\OutputInterface;

if (! function_exists('print_node')) {
    /**
     * @param Node|Node[] $node
     */
    function print_node(Node | array $node): void
    {
        $standard = new Standard();

        $nodes = is_array($node) ? $node : [$node];

        foreach ($nodes as $node) {
            $printedContent = $standard->prettyPrint([$node]);
            var_dump($printedContent);
        }
    }
}

if (! function_exists('dump_node')) {
    /**
     * @param Node|Node[] $node
     */
    function dump_node(Node|array $node)
    {
        $styler = \Illuminate\Container\Container::getInstance()
            ->make(\Rector\Console\Style\SymfonyStyleFactory::class)
            ->create();

        // we turn up the verbosity so it's visible in tests overriding the
        // default which is to be quite during tests
        $styler->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);

        $styler->newLine();

        (new \Rector\Util\PrintNodes($styler))->outputNodes($node);
    }
}
