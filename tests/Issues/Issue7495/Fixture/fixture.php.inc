<?php

namespace Rector\Core\Tests\Issues\Issue7495\Fixture;

class Fixture
{
    private UrlInterface $urlInterface;
    private InvoicesHelper $invoicesHelper;
    private Registry $coreRegistry;

    public function __construct(
        UrlInterface $urlInterface,
        Template\Context $context,
        GetInvoicesHelper $invoicesHelper,
        Registry $registry,
    ) {
        $this->urlInterface   = $urlInterface;
        $this->invoicesHelper = $invoicesHelper;
        $this->coreRegistry   = $registry;

        parent::__construct($context);
    }

    public function getInvoices(): InvoicesHelper
    {
        return $this->invoicesHelper;
    }

    public function getOrder(): Registry
    {
        return $this->coreRegistry;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7495\Fixture;

class Fixture
{
    private InvoicesHelper $invoicesHelper;
    private Registry $coreRegistry;

    public function __construct(
        UrlInterface $urlInterface,
        Template\Context $context,
        GetInvoicesHelper $invoicesHelper,
        Registry $registry,
    ) {
        $this->invoicesHelper = $invoicesHelper;
        $this->coreRegistry   = $registry;

        parent::__construct($context);
    }

    public function getInvoices(): InvoicesHelper
    {
        return $this->invoicesHelper;
    }

    public function getOrder(): Registry
    {
        return $this->coreRegistry;
    }
}

?>
