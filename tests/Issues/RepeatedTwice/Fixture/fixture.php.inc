<?php

namespace Rector\Tests\Issues\RepeatedTwice\Fixture;

final class Fixture
{
    private string $name;

    public function change($name)
    {
        $this->name = $name;

        return $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\RepeatedTwice\Fixture;

final class Fixture
{
    private string $name;

    public function change(string $name): string
    {
        $this->name = $name;

        return $name;
    }
}

?>
