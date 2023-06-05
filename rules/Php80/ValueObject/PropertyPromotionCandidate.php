<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class PropertyPromotionCandidate
{
    public function __construct(
        private readonly Property $property,
        private readonly Param $param,
        private readonly Expression $expression,
    ) {
    }

    public function getProperty(): Property
    {
        return $this->property;
    }

    public function getParam(): Param
    {
        return $this->param;
    }

    public function getStmtPosition(): int
    {
        return $this->expression->getAttribute(AttributeKey::STMT_KEY);
    }
}
