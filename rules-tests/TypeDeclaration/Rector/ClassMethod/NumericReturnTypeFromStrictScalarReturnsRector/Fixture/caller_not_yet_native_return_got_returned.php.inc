<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class CallerNotYetNativeReturnGotReturned
{
    public function some()
    {
       try {
          return $this->example();
       } catch (\Exception $exception) {
          // some logging
          throw $exception;
      }
   }

    private function example()
    {
        return 100 + 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class CallerNotYetNativeReturnGotReturned
{
    public function some(): int
    {
       try {
          return $this->example();
       } catch (\Exception $exception) {
          // some logging
          throw $exception;
      }
   }

    private function example(): int
    {
        return 100 + 1;
    }
}

?>
