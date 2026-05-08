<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node\Stmt\ClassLike;

class WithUseAfterAnonymousClass
{
	public function collectDependencies(ClassLike $node): array
    {
        $nodeTraverser = new NodeTraverser();
        $visitor       = new class extends NodeVisitorAbstract {
            public array $names = [];

            public function enterNode(Node $node): null
            {
                if ($node instanceof FullyQualified) {
                    $this->names[] = implode('\\', $node->getParts());
                }

                return null;
            }
        };

        $nodeTraverser->addVisitor($visitor);
        $nodeTraverser->traverse([$node]);
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node\Stmt\ClassLike;

class WithUseAfterAnonymousClass
{
	public function collectDependencies(ClassLike $classLike): array
    {
        $nodeTraverser = new NodeTraverser();
        $visitor       = new class extends NodeVisitorAbstract {
            public array $names = [];

            public function enterNode(Node $node): null
            {
                if ($node instanceof FullyQualified) {
                    $this->names[] = implode('\\', $node->getParts());
                }

                return null;
            }
        };

        $nodeTraverser->addVisitor($visitor);
        $nodeTraverser->traverse([$classLike]);
    }
}

?>
