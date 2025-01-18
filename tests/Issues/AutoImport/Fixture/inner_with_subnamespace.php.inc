<?php

namespace helpers;

class InnerWithSubnamespace extends helpers\Sub\VarDumper
{
}

?>
-----
<?php

namespace helpers;

use helpers\Sub\VarDumper;

class InnerWithSubnamespace extends VarDumper
{
}

?>
