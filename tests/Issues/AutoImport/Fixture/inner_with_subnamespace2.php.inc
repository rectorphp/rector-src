<?php

namespace helpers;

// sub sub, @see https://3v4l.org/O7RnT
class InnerWithSubnamespace extends helpers\Sub\VarDumper
{
}

?>
-----
<?php

namespace helpers;

use helpers\helpers\Sub\VarDumper;

// sub sub, @see https://3v4l.org/O7RnT
class InnerWithSubnamespace extends VarDumper
{
}

?>
