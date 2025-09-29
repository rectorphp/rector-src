<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MultipleNodeTypes extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class, ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function refactor(Node $node)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class MultipleNodeTypes
{
    /**
     * @return array<int, array<string, int|string>>
     */
    private static function getExpectedAllOwners(): array
    {
        return [
            [
                'key1' => 100,
                'key2' => '-25.5%',
            ],
            [
                'key3' => 'Yes',
                'key4' => 200,
            ],
        ];
    }
}

?>
