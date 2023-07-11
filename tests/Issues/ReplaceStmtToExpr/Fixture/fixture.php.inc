<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReplaceStmtToExpr\Fixture;

class Fixture
{
    public function getUser(?User $user = null): ?User
    {
        return $user ?? (Auth::check() ? Auth::user() : null);
    }

    public function doFoo(
        ?User $user = null
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

namespace Rector\Core\Tests\Issues\ReplaceStmtToExpr\Fixture;

class Fixture
{
    public function getUser(?User $user = null): ?User
    {
        return $user ?? (Auth::check() ? Auth::user() : null);
    }

    public function doFoo(
        ?User $user = null
    ): bool {
        $user = $this->getUser($user);

        if ($user === null) {
            return false;
        }
        if ($user->isFoo()) {
            return true;
        }
        return (bool) $user->isBar();
    }

}

?>
