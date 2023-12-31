<?php

namespace Rector\Tests\Issues\PlainValueParser\Fixture;

use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotation;

final class SomeFixture
{
    /**
     * @CustomAnnotation(description="List of value:
     *  - <b>TRY</b>: To try
     *  - <b>TEST</b>: to test (Default if no parameters given)")
     */
    public function test()
    {}
}

?>
-----
<?php

namespace Rector\Tests\Issues\PlainValueParser\Fixture;

use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotation;

final class SomeFixture
{
    #[CustomAnnotation(description: 'List of value :
  - < b > TRY < /b>: To try
  - < b > TEST < /b>: to test ( Default if no parameters given )')]
    public function test()
    {}
}

?>
