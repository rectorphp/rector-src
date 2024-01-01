<?php

declare(strict_types=1);

use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

// @deprecated, use dump() or dd() instead
if (! function_exists('dump_node')) {
    function dump_node(mixed $variable, int $depth = 2): never
    {
        trigger_error(
            'This function is deprecated, to avoid enforcing of Rector debug package. Use your own favorite debugging package instead'
        );
        exit;
    }
}

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
