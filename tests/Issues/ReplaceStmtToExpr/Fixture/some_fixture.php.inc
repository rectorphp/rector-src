<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ReplaceStmtToExpr\Fixture;

use Rector\Tests\Issues\ReplaceStmtToExpr\Source\SomeUser;

final class SomeFixture
{
    public function getUser(?SomeUser $user = null): ?SomeUser
    {
        return $user ?? (Auth::check() ? Auth::user() : null);
    }

    public function doFoo(
        ?SomeUser $user = null
    ): bool {
        $user = $this->getUser($user);

        if (!$user) {
            return false;
        }

        return $user->isFoo() || $user->isBar();
    }

}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ReplaceStmtToExpr\Fixture;

use Rector\Tests\Issues\ReplaceStmtToExpr\Source\SomeUser;

final class SomeFixture
{
    public function getUser(?SomeUser $user = null): ?SomeUser
    {
        return $user ?? (Auth::check() ? Auth::user() : null);
    }

    public function doFoo(
        ?SomeUser $user = null
    ): bool {
        $user = $this->getUser($user);

        if (!$user instanceof \Rector\Core\Tests\Issues\ReplaceStmtToExpr\Source\SomeUser) {
            return false;
        }
        if ($user->isFoo()) {
            return true;
        }
        return (bool) $user->isBar();
    }

}

?>
