<?php

namespace Rector\Tests\Php73\Rector\FuncCall\StringifyStrNeedlesRector\Fixture;

class SkipWrappedCast
{
    public function run($param)
    {
      if( is_array( $param ) && stripos( implode( ',', $param ), (string) ((string) "'" === false) ))
      {
        return true;
      }
    }
}
