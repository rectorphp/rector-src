<?php

namespace App\Bar;

use const A, B, C;
use const D, E, F;
use const G, H, I;
use const J, K, L;
use const AA, BB, CC;

use const M, N;
use const O, P;
use const Q, R;
use const X, Y;

var_dump(A);
var_dump(E);
var_dump(I);
var_dump(AA);
var_dump(BB);
var_dump(CC);

var_dump(M);
var_dump(P);
var_dump(X);
var_dump(Y);

?>
-----
<?php

namespace App\Bar;

use const A;
use const E;
use const I;
use const AA, BB, CC;

use const M;
use const P;
use const X, Y;

var_dump(A);
var_dump(E);
var_dump(I);
var_dump(AA);
var_dump(BB);
var_dump(CC);

var_dump(M);
var_dump(P);
var_dump(X);
var_dump(Y);

?>
