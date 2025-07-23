<?php

namespace
{
    class GlobalNamespace
    {
        public function create()
        {
            return new \DateTime();
        }
    }
}

?>
-----
<?php

namespace
{
    class GlobalNamespace
    {
        public function create()
        {
            return new DateTime();
        }
    }
}

?>
