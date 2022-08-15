<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

final class SkipRemoveMultiLineParamDoc
{
   /**
    * Hook into page attachments.
    *
    * @param \Exception $e
    *   Page attachments event.
    */
   public function onPageAttachments(\Exception $e): void {}
}
