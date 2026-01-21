<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;

#[Document]
class SkipDoctrineOdmMongodbDocument
{
    private int $amount;
}
