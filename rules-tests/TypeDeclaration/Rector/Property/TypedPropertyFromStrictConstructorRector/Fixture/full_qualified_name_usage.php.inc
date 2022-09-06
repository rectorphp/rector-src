<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

class FullQualifiedNameUsage
{
    /** @var \Magento\Checkout\Model\Session */
    protected $checkoutSession;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(\Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

class FullQualifiedNameUsage
{
    /** @var \Magento\Checkout\Model\Session */
    protected \Magento\Checkout\Model\Session $checkoutSession;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(\Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }
}

?>
