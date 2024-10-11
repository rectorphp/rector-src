<?php

namespace App\Bar;

use A, B, C;
use D, E, F;
use G, H, I;
use J, K, L;
use AA, BB, CC;

use M, N;
use O, P;
use Q, R;
use X, Y;

var_dump(A::class);
var_dump(E::class);
var_dump(I::class);
var_dump(AA::class);
var_dump(BB::class);
var_dump(CC::class);

var_dump(M::class);
var_dump(P::class);
var_dump(X::class);
var_dump(Y::class);

?>
-----
<?php

namespace App\Bar;

use A;
use E;
use I;
use AA, BB, CC;

use M;
use P;
use X, Y;

var_dump(A::class);
var_dump(E::class);
var_dump(I::class);
var_dump(AA::class);
var_dump(BB::class);
var_dump(CC::class);

var_dump(M::class);
var_dump(P::class);
var_dump(X::class);
var_dump(Y::class);

?>
