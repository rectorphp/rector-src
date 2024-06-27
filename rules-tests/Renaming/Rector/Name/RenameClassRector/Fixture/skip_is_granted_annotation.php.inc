<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * IsGranted annotation is not changed, as the real use case is to change to attribute first
 *
 * @see https://github.com/rectorphp/rector-src/blob/290f2a03d53d0b8da35beb973d724f95a77983cb/tests/Issues/AnnotationToAttributeRenameAutoImport/config/configured_rule.php#L13-L22
 * @see https://github.com/rectorphp/rector-symfony/issues/535
 *
 * @IsGranted
 */
class SkipIsGrantedAnnotation
{
}
