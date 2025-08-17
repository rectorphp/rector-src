<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

use OndraM\CiDetector\CiDetector;

final class SkipConstantWildcard
{
    /**
     * @return CiDetector::CI_*|bool|null
     */
    public function resolveCurrentCI(): string|bool|null
    {
        if (file_exists(getcwd() . '/.github/workflows/ci.yml')) {
            return CiDetector::CI_GITHUB_ACTIONS;
        }

        if (file_exists(getcwd() . '/.gitlab-ci.yml')) {
            return CiDetector::CI_GITLAB;
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

use OndraM\CiDetector\CiDetector;

final class SkipConstantWildcard
{
    /**
     * @return CiDetector::CI_*|bool|null
     */
    public function resolveCurrentCI(): ?string
    {
        if (file_exists(getcwd() . '/.github/workflows/ci.yml')) {
            return CiDetector::CI_GITHUB_ACTIONS;
        }

        if (file_exists(getcwd() . '/.gitlab-ci.yml')) {
            return CiDetector::CI_GITLAB;
        }

        return null;
    }
}

?>
