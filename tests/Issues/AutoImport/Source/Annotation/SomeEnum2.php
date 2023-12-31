<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\AutoImport\Source\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class SomeEnum2
{

}
