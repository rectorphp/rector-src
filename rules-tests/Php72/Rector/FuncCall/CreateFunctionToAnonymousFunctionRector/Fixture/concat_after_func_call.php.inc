<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class ConcatAfterFuncCall
{
    function validate($values, $operator = null)
    {
        $operator = $this->_findOperator($operator);
        if ('===' != $operator && '!==' != $operator) {
            $compareFn = create_function('$a, $b', 'return floatval($a) ' . $operator . ' floatval($b);');
        } else {
            $compareFn = create_function('$a, $b', 'return strval($a) ' . $operator . ' strval($b);');
        }

        return $compareFn($values[0], $values[1]);
    }

}

?>
-----
<?php

namespace Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\Fixture;

class ConcatAfterFuncCall
{
    function validate($values, $operator = null)
    {
        $operator = $this->_findOperator($operator);
        if ('===' != $operator && '!==' != $operator) {
            $compareFn = function ($a, $b) use ($operator) {
                return floatval($a) . $operator . floatval($b);
            };
        } else {
            $compareFn = function ($a, $b) use ($operator) {
                return strval($a) . $operator . strval($b);
            };
        }

        return $compareFn($values[0], $values[1]);
    }

}

?>