<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class MyClxProductNet_Translate {
    /**
     * @phpstan-pure
     */
    public static function create(): self
    {
        return new self();
    }
}

/** @var MyActionController $this */
/** @var MyClxProductNet_Translate $translator */
$translator = MyClxProductNet_Translate::create();
$url = $this->bulkConsumerRegistration ?? "abc";

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class MyClxProductNet_Translate {
    /**
     * @phpstan-pure
     */
    public static function create(): self
    {
        return new self();
    }
}

/** @var MyActionController $this */
$translator = MyClxProductNet_Translate::create();
$url = $this->bulkConsumerRegistration ?? "abc";

?>
