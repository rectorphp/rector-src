<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

use Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Source\TranslationEntityInterface;

final class SkipMagicMethod
{
    public function run(TranslationEntityInterface $entity): bool
    {
        return $entity->isPublished(false);
    }
}
