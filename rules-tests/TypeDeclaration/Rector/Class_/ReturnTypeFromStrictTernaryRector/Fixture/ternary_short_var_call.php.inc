<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInShortTernaryVar
{
    public function getValue()
    {
        return $this->get() ?: null;
    }

    public function get(): CallInShortTernaryVar|false {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class CallInShortTernaryVar
{
    public function getValue(): ?\Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture\CallInShortTernaryVar
    {
        return $this->get() ?: null;
    }

    public function get(): CallInShortTernaryVar|false {}
}

?>
