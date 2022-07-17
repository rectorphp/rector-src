<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(columns: ['public_id', 'tenant_id'])]
trait SkipAttributeOnTrait
{
}