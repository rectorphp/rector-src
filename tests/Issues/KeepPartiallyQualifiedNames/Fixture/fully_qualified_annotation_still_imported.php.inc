<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

/**
 * @\Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping\Table(columns={@\Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping\Column(name="x")})
 */
final class FullyQualifiedAnnotationStillImported
{
    /**
     * @var \Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Query\Parameter
     */
    public $parameter;
}

?>
-----
<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping\Table;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping\Column;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Query\Parameter;

/**
 * @Table(columns={@Column(name="x")})
 */
final class FullyQualifiedAnnotationStillImported
{
    /**
     * @var Parameter
     */
    public $parameter;
}

?>
