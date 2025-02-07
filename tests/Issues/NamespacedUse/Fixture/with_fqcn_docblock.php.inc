<?php

namespace Test;

use OtherClass;

class MyClass
{
    /**
     * @param \ThirdClass $user
     */
    public function __construct($user)
    {
    }
}

?>
-----
<?php

namespace Test;

class MyClass
{
    /**
     * @param \ThirdClass $user
     */
    public function __construct($user)
    {
    }
}

?>
