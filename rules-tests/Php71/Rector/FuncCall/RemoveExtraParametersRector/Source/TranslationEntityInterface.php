<?php

declare(strict_types=1);

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Source;

/**
 * @method int|null getId()
 * @method bool     isPublished()
 */
interface TranslationEntityInterface
{
    public function getLanguage(): ?string;
}
