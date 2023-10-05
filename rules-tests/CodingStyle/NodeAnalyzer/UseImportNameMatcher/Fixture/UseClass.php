<?php

namespace Rector\Tests\CodingStyle\NodeAnalyzer\UseImportNameMatcher\Fixture;


use Rector\Tests\CodingStyle\NodeAnalyzer\UseImportNameMatcher\Fixture\FixtureAnnotation;
use Rector\Tests\CodingStyle\NodeAnalyzer\UseImportNameMatcher\Fixture\FixtureAnnotation as Test;

class UseClass
{
    /**
     * @var float
     *
     * @FixtureAnnotation\CustomAnnotation()
     * @Test\CustomAnnotation()
     */
    private $property;
}