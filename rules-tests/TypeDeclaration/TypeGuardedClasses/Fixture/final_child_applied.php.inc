<?php

namespace Rector\Tests\TypeDeclaration\TypeGuardedClasses\Fixture;

use Rector\Tests\TypeDeclaration\TypeGuardedClasses\Source\GuardedRepository;

final class FinalChildApplied extends GuardedRepository
{
    public function getData()
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\TypeGuardedClasses\Fixture;

use Rector\Tests\TypeDeclaration\TypeGuardedClasses\Source\GuardedRepository;

final class FinalChildApplied extends GuardedRepository
{
    public function getData(): array
    {
        return [];
    }
}

?>
