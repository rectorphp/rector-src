<?php

declare(strict_types=1);

namespace Rector\Core\Template;

use Rector\Core\Contract\Template\TemplateResolverInterface;

final class DefaultResolver implements TemplateResolverInterface
{
    /**
     * @var string
     */
    public const TYPE = 'default';

    public function __toString(): string
    {
        return self::TYPE;
    }

    public function provide(): string
    {
        return __DIR__ . '/../../templates/rector.php.dist';
    }

    public function supports(string $type): bool
    {
        return $type === self::TYPE || $type === '';
    }
}
