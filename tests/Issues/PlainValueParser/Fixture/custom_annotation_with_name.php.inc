<?php

namespace Rector\Tests\Issues\PlainValueParser\Fixture;

use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotationWithName;

final class SomeFixture
{
    /**
     * @CustomAnnotationWithName(
     *     description="Multiline
     * description",
     *     name="Possible values"
     * )
     */
    public function test()
    {}
}

?>
-----
<?php

namespace Rector\Tests\Issues\PlainValueParser\Fixture;

use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotationWithName;

final class SomeFixture
{
    #[CustomAnnotationWithName(description: 'Multiline
description', name: 'Possible values')]
    public function test()
    {}
}

?>
