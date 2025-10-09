<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

use App\Support\Benchmarking\Contracts\Metric;
use LogicException;

class AddPrefixBackslash extends LogicException
{
    public function __construct(
        /** @var array<string, array<int, class-string<Metric>>> */
        protected array $duplicateNames,
    ) {
        parent::__construct(
            'Some solutions have duplicate names.',
        );
    }

    public function getDuplicateNames(): array
    {
        return $this->duplicateNames;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

use App\Support\Benchmarking\Contracts\Metric;
use LogicException;

class AddPrefixBackslash extends LogicException
{
    public function __construct(
        /** @var array<string, array<int, class-string<Metric>>> */
        protected array $duplicateNames,
    ) {
        parent::__construct(
            'Some solutions have duplicate names.',
        );
    }

    /**
     * @return array<string, class-string<\App\Support\Benchmarking\Contracts\Metric>[]>
     */
    public function getDuplicateNames(): array
    {
        return $this->duplicateNames;
    }
}

?>
