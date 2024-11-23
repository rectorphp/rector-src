<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\SimplifyEmpty\Fixture;

class Fixture {

    private ?string $string;
    private ?string $string2;

    public function getString(): ?string
    {
        return $this->string;
    }

    public function getString2(): ?string
    {
        return $this->string2;
    }

    public function check(): bool
    {
        if (empty($this->getString()) || (empty($this->getString2()) && !is_numeric($this->getString2()))) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\SimplifyEmpty\Fixture;

class Fixture {

    private ?string $string;
    private ?string $string2;

    public function getString(): ?string
    {
        return $this->string;
    }

    public function getString2(): ?string
    {
        return $this->string2;
    }

    public function check(): bool
    {
        return !in_array($this->getString(), [null, '', '0'], true) && !(in_array($this->getString2(), [null, '', '0'], true) && !is_numeric($this->getString2()));
    }
}

?>
