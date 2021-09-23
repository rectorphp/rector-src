<?php

namespace Rector\Core\Tests\Issues\Issue6701\Fixture;

final class UnusedPrivateProperty
{
    public \stdClass $dataProperty;

    private string $unusedPropertyName;

    public function updateData() {
        $this->dataProperty->unusedPropertyName = 'some data';
    }
}
?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue6701\Fixture;

final class UnusedPrivateProperty
{
    public \stdClass $dataProperty;

    public function updateData() {
        $this->dataProperty->unusedPropertyName = 'some data';
    }
}
?>
