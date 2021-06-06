<?php

declare(strict_types=1);

namespace Rector\Defluent\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Defluent\Contract\ValueObject\FirstCallFactoryAwareInterface;
use Rector\Defluent\Contract\ValueObject\RootExprAwareInterface;

final class AssignAndRootExpr extends AbstractRootExpr implements RootExprAwareInterface, FirstCallFactoryAwareInterface
{
    public function __construct(
        Expr $assignExpr,
        Expr $rootExpr,
        private ?Variable $variable = null,
        bool $isFirstCallFactory = false
    ) {
        $this->assignExpr = $assignExpr;
        $this->rootExpr = $rootExpr;
        $this->isFirstCallFactory = $isFirstCallFactory;
    }

    public function getAssignExpr(): Expr
    {
        return $this->assignExpr;
    }

    public function getRootExpr(): Expr
    {
        return $this->rootExpr;
    }

    public function getSilentVariable(): ?Variable
    {
        return $this->variable;
    }

    public function getReturnSilentVariable(): Return_
    {
        if (! $this->variable instanceof Variable) {
            throw new ShouldNotHappenException();
        }

        return new Return_($this->variable);
    }

    public function getCallerExpr(): Expr
    {
        if ($this->variable !== null) {
            return $this->variable;
        }

        return $this->assignExpr;
    }

    public function isFirstCallFactory(): bool
    {
        return $this->isFirstCallFactory;
    }

    public function getFactoryAssignVariable(): Expr
    {
        $firstAssign = $this->getFirstAssign();
        if (! $firstAssign instanceof Assign) {
            return $this->getCallerExpr();
        }

        return $firstAssign->var;
    }
}
