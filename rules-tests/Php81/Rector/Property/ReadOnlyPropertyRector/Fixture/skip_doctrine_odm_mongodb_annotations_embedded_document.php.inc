<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ODM\MongoDB\Mapping\Annotations\EmbeddedDocument;

#[EmbeddedDocument]
class SkipDoctrineOdmMongodbEmbeddedDocument
{
    private int $amount;
}
