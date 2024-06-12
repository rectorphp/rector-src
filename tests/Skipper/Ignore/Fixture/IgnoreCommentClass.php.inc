<?php

namespace Rector\Tests\Skipper\Ignore\Fixture;

use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

final class IgnoreCommentClass
{
    private int $myVariable;

    // @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector
    public function getMyVariable()
    {
        return $this->myVariable;
    }

    /* @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector */
    public function getMyVariable2()
    {
        return $this->myVariable;
    }

    /**
     * @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector
     */
    public function getMyVariable3()
    {
        return $this->myVariable;
    }

    /** @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector */
    public function getMyVariable4()
    {
        return $this->myVariable;
    }

    // @rector-ignore-next-line
    public function getMyVariable5()
    {
        return $this->myVariable;
    }

    /* @rector-ignore-next-line */
    public function getMyVariable6()
    {
        return $this->myVariable;
    }

    /**
     * @rector-ignore-next-line
     */
    public function getMyVariable7()
    {
        return $this->myVariable;
    }

    /** @rector-ignore-next-line */
    public function getMyVariable8()
    {
        return $this->myVariable;
    }

    // @rector-ignore Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector
    public function getMyVariable9()
    {
        return $this->myVariable;
    }

    public function getMyVariable10(): int
    {
        return /* @rector-ignore Rector\DeadCode\Rector\Cast\RecastingRemovalRector */ (int) $this->myVariable;
    }

    public function getMyVariable11()
    {
        return $this->myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector, AddVoidReturnTypeWhereNoReturnRector
    public function setMyVariable($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector
    // @rector-ignore AddVoidReturnTypeWhereNoReturnRector
    public function setMyVariable2($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    /**
     * @rector-ignore AddParamTypeFromPropertyTypeRector
     * @rector-ignore AddVoidReturnTypeWhereNoReturnRector
     */
    public function setMyVariable3($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore-next-line
    public function setMyVariable4($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector
    public function setMyVariable5($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    public function setMyVariable6($myVariable)
    {
        $this->myVariable = $myVariable;
    }
}

?>
-----
<?php

namespace Rector\Tests\Skipper\Ignore\Fixture;

use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

final class IgnoreCommentClass
{
    private int $myVariable;

    // @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector
    public function getMyVariable()
    {
        return $this->myVariable;
    }

    /* @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector */
    public function getMyVariable2()
    {
        return $this->myVariable;
    }

    /**
     * @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector
     */
    public function getMyVariable3()
    {
        return $this->myVariable;
    }

    /** @rector-ignore NumericReturnTypeFromStrictScalarReturnsRector */
    public function getMyVariable4()
    {
        return $this->myVariable;
    }

    // @rector-ignore-next-line
    public function getMyVariable5()
    {
        return $this->myVariable;
    }

    /* @rector-ignore-next-line */
    public function getMyVariable6()
    {
        return $this->myVariable;
    }

    /**
     * @rector-ignore-next-line
     */
    public function getMyVariable7()
    {
        return $this->myVariable;
    }

    /** @rector-ignore-next-line */
    public function getMyVariable8()
    {
        return $this->myVariable;
    }

    // @rector-ignore Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector
    public function getMyVariable9()
    {
        return $this->myVariable;
    }

    public function getMyVariable10(): int
    {
        return /* @rector-ignore Rector\DeadCode\Rector\Cast\RecastingRemovalRector */ (int) $this->myVariable;
    }

    public function getMyVariable11(): int
    {
        return $this->myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector, AddVoidReturnTypeWhereNoReturnRector
    public function setMyVariable($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector
    // @rector-ignore AddVoidReturnTypeWhereNoReturnRector
    public function setMyVariable2($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    /**
     * @rector-ignore AddParamTypeFromPropertyTypeRector
     * @rector-ignore AddVoidReturnTypeWhereNoReturnRector
     */
    public function setMyVariable3($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore-next-line
    public function setMyVariable4($myVariable)
    {
        $this->myVariable = $myVariable;
    }

    // @rector-ignore AddParamTypeFromPropertyTypeRector
    public function setMyVariable5($myVariable): void
    {
        $this->myVariable = $myVariable;
    }

    public function setMyVariable6(int $myVariable): void
    {
        $this->myVariable = $myVariable;
    }
}

?>
