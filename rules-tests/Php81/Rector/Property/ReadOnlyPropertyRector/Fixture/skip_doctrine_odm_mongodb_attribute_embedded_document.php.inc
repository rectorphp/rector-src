<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Doctrine\ODM\MongoDB\Mapping\Attribute\EmbeddedDocument;

#[EmbeddedDocument]
class SkipDoctrineOdmMongodbEmbeddedDocument
{
    private int $amount;
}
