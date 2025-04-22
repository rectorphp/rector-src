<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\FixturePhp74;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\JsonResource2;

class FollowParentConstructOnPhp74 extends JsonResource2
{
    public function toArray(
        Request $request,
    ): array {
        return [
            'user_id' => $this->user_id ?? App::get(MissingValue::class),
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\FixturePhp74;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\JsonResource2;

class FollowParentConstructOnPhp74 extends JsonResource2
{
    private \Illuminate\Foundation\Application $application;
    public function __construct($resource, \Illuminate\Foundation\Application $application)
    {
        parent::__construct($resource);
        $this->application = $application;
    }
    public function toArray(
        Request $request,
    ): array {
        return [
            'user_id' => $this->user_id ?? $this->application->get(MissingValue::class),
        ];
    }
}

?>
