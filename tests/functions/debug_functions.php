<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;
use Tracy\Dumper;

function dump_with_depth(mixed $value, int $depth = 2): void
{
    Dumper::dump($value, [
        Dumper::DEPTH => $depth,
    ]);
}

/**
 * @param Node|Node[] $node
 */
function print_node(Node | array $node): void
{
    $standard = new Standard();

    $nodes = is_array($node) ? $node : [$node];

    foreach ($nodes as $node) {
        $printedContent = $standard->prettyPrint([$node]);
        Dumper::dump($printedContent);
    }
}
