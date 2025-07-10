<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class Fixture
{
    /**
     * Update the specified resource in storage.
     *
     * @deprecated use Internal\Account\TerminalGroupController::update instead
     */
    public function update() {}
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class Fixture
{
    /**
     * Update the specified resource in storage.
     */
    #[\Deprecated(message: 'use Internal\Account\TerminalGroupController::update instead')]
    public function update() {}
}

?>
