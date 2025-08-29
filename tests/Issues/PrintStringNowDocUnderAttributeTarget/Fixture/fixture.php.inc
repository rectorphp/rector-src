<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\PrintStringNowDocUnderAttributeTarget\Fixture;

final readonly class Fixture
{
    public function __construct(
        #[\SomeAttribute(
            <<<'SOME_VALUE_TEXT'
                execute ($id: ID!) {
                    run(id: $id) {
                        data {
                            id
                        }
                    }
                }
                SOME_VALUE_TEXT,
        )]
        private object $query,
    ) {}
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\PrintStringNowDocUnderAttributeTarget\Fixture;

final readonly class Fixture
{
    public function __construct(
        #[\SomeNewAttribute(
            <<<'SOME_VALUE_TEXT'
                execute ($id: ID!) {
                    run(id: $id) {
                        data {
                            id
                        }
                    }
                }
                SOME_VALUE_TEXT,
        )]
        private object $query,
    ) {}
}

?>