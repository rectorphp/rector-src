<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureForeachToArrayParam;

use App\Entity\Url;

class Fixture
{
    /**
     * @param array<Url> $urls
     */
    public function create(array $urls, string $hash): ?Url
    {
        if (0 === \count($urls)) {
            $url = new Url();
        } else {
            $url = null;
            foreach ($urls as $urlToCheck) {
                if ($urlToCheck->getPathHash() === $hash) {
                    $url = $urlToCheck;
                    break;
                }
            }
        }

        return $url;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureForeachToArrayParam;

use App\Entity\Url;

class Fixture
{
    /**
     * @param array<Url> $urls
     */
    public function create(array $urls, string $hash): ?Url
    {
        if (0 === \count($urls)) {
            $url = new Url();
        } else {
            $url = array_find($urls, fn($urlToCheck) => $urlToCheck->getPathHash() === $hash);
        }

        return $url;
    }
}

?>