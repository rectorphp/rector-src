<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class ExactTypes
{
    /**
     * @return 'Gitlab'|'GitHub'|bool|int|null
     */
    public function foodd(): string|bool|int|null
    {
        if (! $__ENV['CI']) {
            return false;
        }

        if (file_exists(getcwd() . '/.gitlab-ci.yml')) {
            return 'Gitlab';
        }

        if (file_exists(getcwd() . '/.github/workflows')) {
            return 'GitHub';
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class ExactTypes
{
    /**
     * @return 'GitHub'|'Gitlab'|bool|null
     */
    public function foodd(): string|bool|null
    {
        if (! $__ENV['CI']) {
            return false;
        }

        if (file_exists(getcwd() . '/.gitlab-ci.yml')) {
            return 'Gitlab';
        }

        if (file_exists(getcwd() . '/.github/workflows')) {
            return 'GitHub';
        }

        return null;
    }
}

?>
