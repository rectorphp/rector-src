<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PhpParser\Node\Expr\Cast;
use Rector\Validation\RectorAssert;
use Webmozart\Assert\Assert;

final readonly class RenameCast
{
    public function __construct(
        /** @var class-string<Cast> */
        private string $fromCastExprClass,
        private int $fromCastKind,
        private int $toCastKind,
    ) {
        RectorAssert::className($fromCastExprClass);
        Assert::subclassOf($fromCastExprClass, Cast::class);
    }

    /**
     * @return class-string<Cast>
     */
    public function getFromCastExprClass(): string
    {
        return $this->fromCastExprClass;
    }

    public function getFromCastKind(): int
    {
        return $this->fromCastKind;
    }

    public function getToCastKind(): int
    {
        return $this->toCastKind;
    }
}
