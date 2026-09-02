<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Rector\Tests\Issues\NamespacedUseAutoImport\Source\SomeClass;

/**
 * @ORM\DiscriminatorMap({
 *     SomeClass::TOTP = "totp",
 *     SomeClass::EMAIL = "email"
 * })
 */
class SkipAnnotationArrayKeyConst
{
}
