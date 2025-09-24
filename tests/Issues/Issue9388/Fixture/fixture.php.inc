<?php

namespace Rector\Tests\Issues\Issue9388\Fixture;

final class MyClass
{
    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';
}
-----
<?php

namespace Rector\Tests\Issues\Issue9388\Fixture;

final class MyClass
{
    /**
     * @var string
     */
    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    protected $name = '';
}
