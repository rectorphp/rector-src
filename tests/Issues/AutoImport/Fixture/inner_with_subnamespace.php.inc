<?php

namespace helpers;

/**
 * @see https://3v4l.org/WMUjV0
 * it read with "helpers" as prefix: helpers\Sub\VarDumper
 */
class InnerWithSubnamespace extends Sub\VarDumper
{
}

?>
-----
<?php

namespace helpers;

use helpers\Sub\VarDumper;

/**
 * @see https://3v4l.org/WMUjV0
 * it read with "helpers" as prefix: helpers\Sub\VarDumper
 */
class InnerWithSubnamespace extends VarDumper
{
}

?>
