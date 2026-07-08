<?php

(print "foo") and (print "bar");

?>
-----
<?php

(print "foo") && print "bar";

?>
