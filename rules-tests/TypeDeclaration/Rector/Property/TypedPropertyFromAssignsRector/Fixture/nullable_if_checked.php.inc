<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\SomeConnection;

final class NullableIfChecked
{
    /**
     * @var SomeConnection
     */
    private $connection;

    public function __construct()
    {
        if ($this->connection == null) {
            $this->initConnection();
        }
    }

    private function initConnection(): void
    {
        $this->connection = new SomeConnection();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\SomeConnection;

final class NullableIfChecked
{
    private ?\Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\SomeConnection $connection = null;

    public function __construct()
    {
        if ($this->connection == null) {
            $this->initConnection();
        }
    }

    private function initConnection(): void
    {
        $this->connection = new SomeConnection();
    }
}

?>
