<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class View
{
    public function __construct(private string $template, private array $data)
    {
    }

    public static function make(string $template, array $data)
    {
        return new self($template, $data);
    }

    public function render(): string
    {
        return $this->template;
    }
}

class SkipOnAnonymousFunctions implements \Stringable
{
    public function __construct(private array $items)
    {
    }

    public function __toString(): string
    {
        return View::make('index', [
            'only_uppercase' => array_filter(
                $this->items,
                static function (string $item) {
                    return mb_strtoupper($item) === $item;
                }
            ),
        ])->render();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

class View
{
    public function __construct(private string $template, private array $data)
    {
    }

    public static function make(string $template, array $data)
    {
        return new self($template, $data);
    }

    public function render(): string
    {
        return $this->template;
    }
}

class SkipOnAnonymousFunctions implements \Stringable
{
    public function __construct(private array $items)
    {
    }

    public function __toString(): string
    {
        return (string) View::make('index', [
            'only_uppercase' => array_filter(
                $this->items,
                static function (string $item) {
                    return mb_strtoupper($item) === $item;
                }
            ),
        ])->render();
    }
}

?>
