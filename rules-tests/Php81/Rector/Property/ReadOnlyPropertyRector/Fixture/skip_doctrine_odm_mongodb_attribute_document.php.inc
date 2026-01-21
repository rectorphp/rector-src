<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ODM\MongoDB\Mapping\Attribute\Document;

#[Document]
class SkipDoctrineOdmMongodbDocument
{
    private int $amount;
}
