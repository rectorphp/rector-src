<?php

declare(strict_types=1);


namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\StaticDoctrineAnnotationParser\Source;

class StringAnnotationValue
{
    /**
     * @CustomAnnotation(description="List of value:
     *  - <b>TRY</b>: To try
     *  - <b>TEST</b>: to test (Default if no parameters given)")
     */
    public function test()
    {}
}
