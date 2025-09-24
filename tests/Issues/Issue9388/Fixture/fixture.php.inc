<?php

namespace Rector\Tests\Issues\Issue9388\Fixture;

final class MyClass
{
    /**
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $thisWorks = '';
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

    #[\TYPO3\CMS\Extbase\Annotation\Validate(['validator' => 'NotEmpty'])]
    protected $thisWorks = '';
}
