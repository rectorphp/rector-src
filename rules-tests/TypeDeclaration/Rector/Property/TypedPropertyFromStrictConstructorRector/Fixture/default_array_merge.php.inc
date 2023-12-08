<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

final class DefaultArrayMerge
{
    private $options = [
        'key' => 'value'
    ];

    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

final class DefaultArrayMerge
{
    private array $options = [
        'key' => 'value'
    ];

    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
}

?>
