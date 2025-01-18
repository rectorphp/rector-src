<?php

namespace helpers;

/**
 * @see https://3v4l.org/PsIsB
 * it read as double "helpers" as sub sub: helpers\helpers\Sub\VarDumper
 */
class InnerWithSubnamespace extends helpers\Sub\VarDumper
{
}

?>
-----
<?php

namespace helpers;

use helpers\helpers\Sub\VarDumper;

/**
 * @see https://3v4l.org/PsIsB
 * it read as double "helpers" as sub sub: helpers\helpers\Sub\VarDumper
 */
class InnerWithSubnamespace extends VarDumper
{
}

?>
