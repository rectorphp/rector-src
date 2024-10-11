<?php

namespace Apple\Orange;

use const apple\Orange\Banana;
use const apple\orange\banana;
use const Apple\orange\Ananas;
const Banana = 'banana';
const Ananas = 'ananas';


var_dump(Banana);
var_dump(Ananas);

?>
-----
<?php

namespace Apple\Orange;

use const apple\Orange\Banana;
use const Apple\orange\Ananas;
const Banana = 'banana';
const Ananas = 'ananas';


var_dump(Banana);
var_dump(Ananas);

?>
