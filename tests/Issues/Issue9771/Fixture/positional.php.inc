<?php

namespace Rector\Tests\Issues\Issue9771\Fixture;

use Rector\Tests\Issues\Issue9771\Source\Table;

return function (): void {
    $table = new Table();

    $styles = [
        'cant-split' => 'dummy',
    ];

    $table->addRow(null, $styles['cant-split']);
};
-----
<?php

namespace Rector\Tests\Issues\Issue9771\Fixture;

use Rector\Tests\Issues\Issue9771\Source\Table;

return function (): void {
    $table = new Table();

    $styles = [
        'cant-split' => 'dummy',
    ];

    $table->addRow(style: $styles['cant-split']);
};
