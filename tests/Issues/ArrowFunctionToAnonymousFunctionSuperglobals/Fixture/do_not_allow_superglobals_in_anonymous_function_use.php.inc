<?php

namespace Rector\Tests\Issues\ArrowFunctionToAnonymousFunctionSuperglobals\Fixture;

final class DoNotAllowSuperglobalsInAnonymousFunctionUse
{
    public function usingServer()
    {
        PostStringyKey::retrieved(fn ($model) => $_SERVER['destroy']['retrieved'][] = $model->my_id);
    }
}
?>
-----
<?php

namespace Rector\Tests\Issues\ArrowFunctionToAnonymousFunctionSuperglobals\Fixture;

final class DoNotAllowSuperglobalsInAnonymousFunctionUse
{
    public function usingServer()
    {
        PostStringyKey::retrieved(function ($model) {
            return $_SERVER['destroy']['retrieved'][] = $model->my_id;
        });
    }
}
?>
