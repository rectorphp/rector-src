<?php

declare(strict_types=1);

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\Scalar\ScalarValueToConstFetchRector\Source\ClassWithConst;
use Rector\Transform\Rector\Scalar\ScalarValueToConstFetchRector;
use Rector\Transform\ValueObject\ScalarValueToConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(
            ScalarValueToConstFetchRector::class,
            [
                new ScalarValueToConstFetch(
                    new LNumber(10),
                    new ClassConstFetch(new FullyQualified(ClassWithConst::class), new Identifier('FOOBAR_INT'))
                ),
                new ScalarValueToConstFetch(
                    new DNumber(10.1),
                    new ClassConstFetch(new FullyQualified(ClassWithConst::class), new Identifier('FOOBAR_FLOAT'))
                ),
                new ScalarValueToConstFetch(
                    new String_('ABC'),
                    new ClassConstFetch(new FullyQualified(ClassWithConst::class), new Identifier('FOOBAR_STRING'))
                ),
            ]
        );
};
