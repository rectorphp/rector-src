<?php

namespace Rector\Core\Tests\Issues\PartialValueDocblockUpdate\Fixture;

use Rector\Tests\Transform\Rector\StaticCall\StaticCallToNewRector\Source\SomeJsonResponse;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Symfony\Component\Routing\Annotation\Route;

final class AddDefaultValueToRoute
{
    /**
     * @Since("6.4.0.1")
     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"})
     *
     * @return SomeJsonResponse
     */
    public function resetExcludedSearchTerm()
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\PartialValueDocblockUpdate\Fixture;

use Rector\Tests\Transform\Rector\StaticCall\StaticCallToNewRector\Source\SomeJsonResponse;
use Shopware\Core\Framework\Routing\Annotation\Since;
use Symfony\Component\Routing\Annotation\Route;

final class AddDefaultValueToRoute
{
    /**
     * @Since("6.4.0.1")
     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"}, defaults={})
     *
     * @return SomeJsonResponse
     */
    public function resetExcludedSearchTerm()
    {
    }
}

?>
