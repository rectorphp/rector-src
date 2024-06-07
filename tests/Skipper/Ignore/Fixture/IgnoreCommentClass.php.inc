<?php

namespace Rector\Tests\Skipper\Ignore\Fixture;

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

    public function getMyVariable9()
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

    public function getMyVariable9(): int
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
