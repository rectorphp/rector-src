<?php

declare(strict_types=1);

/**
 * Benchmark for parsing and printing docblocks: PhpDocInfoFactory::createFromNode() and
 * PhpDocInfoPrinter::printFormatPreserving().
 *
 * Measured per docblock:
 *   - parse:          createFromNode(), incl. the node visitors run on every docblock
 *   - print @return:  change the @return type, then print
 *   - print add tag:  add a @throws tag, then print
 *
 * Run on both branches to compare:
 *   php bench/phpdoc_info_printer_bench.php
 *
 * Reports CPU time (user+sys) per docblock in µs, median of REPEAT runs.
 */

use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\DependencyInjection\LazyContainerFactory;

require __DIR__ . '/../vendor/autoload.php';

const ITERATIONS = 300;
const REPEAT = 7;

$createDocWithTags = static function (int $count): string {
    $lines = ['/**', ' * Large docblock.', ' *'];
    for ($i = 0; $i < $count; ++$i) {
        $lines[] = sprintf(' * @param array<string, int|Foo%d>|null $param%d Description %d.', $i, $i, $i);
    }

    $lines[] = ' * @return array<int, string>';
    $lines[] = ' */';

    return implode("\n", $lines);
};

$docs = [
    'simple' => "/**\n * @param int \$a\n * @return string\n */",
    'generics' => "/**\n * @param array<string, list<int>> \$map\n * @param Collection<int, Foo>|null \$items\n * @return iterable<int, Foo>\n */",
    'array shape' => "/**\n * @param array{id: int, name?: string, tags: list<string>} \$row\n * @return array{id: int, name?: string}\n */",
    'callable' => "/**\n * @param callable(int, string): void \$cb\n * @param Closure(Foo \$foo, int ...\$rest): (int|null) \$fn\n * @return callable(): int\n */",
    'description' => "/**\n * Fetches the records.\n *\n * This method reads all records\n * and returns them as a list.\n *\n * @param int \$limit Max number of rows. When it is 0,\n *                   all rows are returned.\n * @return list<Record>\n */",
    'doctrine' => "/**\n * @ORM\\Column(type=\"string\", length=255, nullable=true)\n * @ORM\\ManyToOne(targetEntity=\"App\\Entity\\User\", inversedBy=\"posts\")\n * @return string\n */",
    '10 tags' => $createDocWithTags(10),
    '30 tags' => $createDocWithTags(30),
    '60 tags' => $createDocWithTags(60),
];

$rectorConfig = (new LazyContainerFactory())->create();
$rectorConfig->boot();

$phpDocInfoFactory = $rectorConfig->make(PhpDocInfoFactory::class);
$phpDocInfoPrinter = $rectorConfig->make(PhpDocInfoPrinter::class);

// the factory caches every PhpDocInfo by node object id; clear it before each run to limit memory
$phpDocInfosByObjectId = new ReflectionProperty(PhpDocInfoFactory::class, 'phpDocInfosByObjectId');

$cpuSeconds = static function (): float {
    $usage = getrusage();
    return $usage['ru_utime.tv_sec'] + $usage['ru_utime.tv_usec'] / 1_000_000
        + $usage['ru_stime.tv_sec'] + $usage['ru_stime.tv_usec'] / 1_000_000;
};

// a new node per docblock, as PhpDocInfoFactory caches by node
$createPhpDocInfo = static function (string $doc) use ($phpDocInfoFactory): PhpDocInfo {
    $classMethod = new ClassMethod('run');
    $classMethod->setDocComment(new Doc($doc));

    $phpDocInfo = $phpDocInfoFactory->createFromNode($classMethod);
    assert($phpDocInfo instanceof PhpDocInfo);

    return $phpDocInfo;
};

$change = static function (PhpDocInfo $phpDocInfo, string $case): void {
    if ($case === 'print @return') {
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        assert($returnTagValueNode instanceof ReturnTagValueNode);
        $returnTagValueNode->type = new IdentifierTypeNode('Bar');
        return;
    }

    $phpDocInfo->addPhpDocTagNode(
        new PhpDocTagNode('@throws', new ThrowsTagValueNode(new IdentifierTypeNode('Foo'), ''))
    );
};

/**
 * @return float µs per docblock
 */
$measure = static function (string $doc, string $case) use (
    $createPhpDocInfo,
    $change,
    $phpDocInfoPrinter,
    $cpuSeconds,
    $phpDocInfoFactory,
    $phpDocInfosByObjectId
): float {
    $phpDocInfosByObjectId->setValue($phpDocInfoFactory, []);

    $phpDocInfos = [];
    for ($i = 0; $i < ITERATIONS; ++$i) {
        $phpDocInfos[] = $case === 'parse' ? null : $createPhpDocInfo($doc);
    }

    if ($case !== 'parse') {
        foreach ($phpDocInfos as $phpDocInfo) {
            $change($phpDocInfo, $case);
        }
    }

    $start = $cpuSeconds();
    foreach ($phpDocInfos as $phpDocInfo) {
        if ($phpDocInfo === null) {
            $createPhpDocInfo($doc);
        } else {
            $phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        }
    }

    return ($cpuSeconds() - $start) / ITERATIONS * 1_000_000;
};

printf("%-12s %10s %15s %15s\n", 'docblock', 'parse', 'print @return', 'print add tag');

foreach ($docs as $name => $doc) {
    $results = [];
    foreach (['parse', 'print @return', 'print add tag'] as $case) {
        // warmup
        $measure($doc, $case);

        $times = [];
        for ($repeat = 0; $repeat < REPEAT; ++$repeat) {
            $times[] = $measure($doc, $case);
        }

        sort($times);
        $results[] = $times[intdiv(REPEAT, 2)];
    }

    printf("%-12s %10.1f %15.1f %15.1f\n", $name, ...$results);
}
