<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class SkipReturnVoid
{
   public function run(array $name = [])
   {
        call_user_func_array(function () use ($name) {
            return $this->execute();
        }, []);
   }

   public function execute(): void
   {
   }
}
