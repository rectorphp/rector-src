<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

final class DoNotRemoveDifferentTypeDoc
{
   /**
    * Hook into page attachments.
    *
    * @param mixed $e
    * @param int $a
    */
   public function onPageAttachments($e, int $a): void {}
}

?>
-----
<?php

final class DoNotRemoveDifferentTypeDoc
{
   /**
    * Hook into page attachments.
    *
    * @param int $a
    */
   public function onPageAttachments(mixed $e, int $a): void {}
}

?>
