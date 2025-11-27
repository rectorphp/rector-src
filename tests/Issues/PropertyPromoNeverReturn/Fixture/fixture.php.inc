<?php

namespace Rector\Tests\Issues\PropertyPromoNeverReturn\Fixture;

class Fixture
{
    private $value;
    private array $rules;

    public function __construct($value)
    {
        $this->value = $value;

        $this->rules = [
            'chave' => function ($value) {
                return is_string($value) ? true : 'not string';
            },
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\PropertyPromoNeverReturn\Fixture;

class Fixture
{
    private array $rules;

    public function __construct(private $value)
    {
        $this->rules = [
            'chave' => function ($value) {
                return is_string($value) ? true : 'not string';
            },
        ];
    }
}

?>
