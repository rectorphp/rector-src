<?php

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source;

class NewDynamicNewExtends
{
    public function run()
    {
        new class extends \Symfony\Bundle\TwigBundle\Loader\FilesystemLoader {};
    }
}
