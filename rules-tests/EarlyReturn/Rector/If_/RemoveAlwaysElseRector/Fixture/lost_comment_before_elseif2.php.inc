<?php

/**
 * Encode/Decode strings and arrays from one charset to another charset
 */
namespace Rector\EarlyReturn\Tests\Rector\If_\RemoveAlwaysElseRector\Fixture;

class StringConverter
{


    /**
     * @param mixed|mixed[] $data
     *
     * @return mixed|mixed[]
     */
    public function convert($data)
    {
        if (is_array($data)) {

            return $res;
        } elseif ($data instanceof EncodedString) {
            
           
        } elseif (is_object($data)) {
            // work on a copy so we dont change the passed in parameter

            return $res;
        }        
        // this comment will be lost :-(
        elseif (is_string($data) ) {
            return $data;
        }

        return $data;
    }
}
?>
-----
<?php

namespace Rector\EarlyReturn\Tests\Rector\If_\RemoveAlwaysElseRector\Fixture;

// what is expected code?
// should remain the same? delete part bellow ----- (included)

?>
