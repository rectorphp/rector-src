<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayFindRector\Fixture;

class SkipVariableReUseAfterForeach
{
    /**
    * @param array<Url> $urls
    */
    public function create(array $urls, string $hash): ?Url
    {
        $url = null;
        foreach ($urls as $urlToCheck) {
            if ($urlToCheck->getPathHash() === $hash) {
                $url = $urlToCheck;
                break;
            }
        }

        if (isset($urlToCheck)) {
            echo 'hit';
        }

        return $url;
    }
}