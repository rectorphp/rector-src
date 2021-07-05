<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

final class TaggableListener
{
    public function getSubscribedEvents() : array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) : void
    {
        $metadata = $eventArgs->getClassMetadata();

        $metadata->enableAssociationCache('tags', [
            'usage' => ClassMetadataInfo::CACHE_USAGE_NONSTRICT_READ_WRITE,
            'region' => 'tags',
        ]);
    }
}
