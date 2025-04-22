<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\FixturePhp74;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\MissingValue;
use Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\XmlResource2;

class FollowParentEmptyConstructOnPhp74 extends XmlResource2
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
use Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\XmlResource2;

class FollowParentEmptyConstructOnPhp74 extends XmlResource2
{
    private \Illuminate\Foundation\Application $application;
    public function __construct(\Illuminate\Foundation\Application $application)
    {
        parent::__construct();
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
