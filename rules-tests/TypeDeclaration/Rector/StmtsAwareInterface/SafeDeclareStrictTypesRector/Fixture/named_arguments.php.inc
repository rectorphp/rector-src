<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function acceptsNamedArgs(string $name, int $age): void
{
}

function callWithNamedArgs(): void
{
    acceptsNamedArgs(age: 25, name: 'John');
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Fixture;

function acceptsNamedArgs(string $name, int $age): void
{
}

function callWithNamedArgs(): void
{
    acceptsNamedArgs(age: 25, name: 'John');
}
