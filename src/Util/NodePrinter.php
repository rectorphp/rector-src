<?php

declare(strict_types=1);

namespace Rector\Util;

use Nette\Utils\Strings;
use PhpParser\Node;
use Rector\Console\Style\RectorStyle;
use Rector\CustomRules\SimpleNodeDumper;

final readonly class NodePrinter
{
    /**
     * @see https://regex101.com/r/Fe8n73/1
     */
    private const string CLASS_NAME_REGEX = '#(?<class_name>PhpParser(.*?))\(#ms';

    /**
     * @see https://regex101.com/r/uQFuvL/1
     */
    private const string PROPERTY_KEY_REGEX = '#(?<key>[\w\d]+)\:#';

    public function __construct(
        private RectorStyle $rectorStyle
    ) {
    }

    /**
     * @param Node|Node[] $nodes
     */
    public function printNodes(Node|array $nodes): void
    {
        $dumpedNodesContents = SimpleNodeDumper::dump($nodes);

        // colorize
        $colorContents = $this->addConsoleColors($dumpedNodesContents);
        $this->rectorStyle->writeln($colorContents);

        $this->rectorStyle->newLine();
    }

    private function addConsoleColors(string $contents): string
    {
        // decorate class names
        $colorContents = Strings::replace(
            $contents,
            self::CLASS_NAME_REGEX,
            static fn (array $match): string => '<fg=green>' . $match['class_name'] . '</>('
        );

        // decorate keys
        return Strings::replace(
            $colorContents,
            self::PROPERTY_KEY_REGEX,
            static fn (array $match): string => '<fg=yellow>' . $match['key'] . '</>:'
        );
    }
}
