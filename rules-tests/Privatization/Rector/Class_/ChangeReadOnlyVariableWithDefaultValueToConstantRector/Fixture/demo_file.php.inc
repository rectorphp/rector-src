<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class DemoFile
{
    public function run(int $id): array
    {
        $data                = [
            'url' => "/$id.pdf",
        ];

        return $data;
    }
}
?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class DemoFile
{
    public function run(int $id): array
    {
        $data                = [
            'url' => "/$id.pdf",
        ];

        return $data;
    }
}
?>

## What is expected

In fact, the rule `ChangeReadOnlyVariableWithDefaultValueToConstantRector` should not fix this sample file at all. 

`ChangeReadOnlyVariableWithDefaultValueToConstantRector` is for detecting a read-only variable like in the example here below but, in the submitted source here above, no, `$data` is using a parameter given to the function so, it should not be considered as a constant. The rule should not change the code here.

The origin of the error is for sure the use of the `$id` inside double-quotes and not f.i. using a `sprintf()` function.

```php
public function run(int $id): array
{
    $data                = [
        'url' => "a_file.pdf",
    ];

    return $data;
}
```
