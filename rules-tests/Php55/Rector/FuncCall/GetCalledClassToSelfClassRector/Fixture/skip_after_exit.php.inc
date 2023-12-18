<?php

exit('blocked');

function my_function () {
    // It seems like this call below can be any function,
    // so long as it returns something
	return implode(',', []);
}

echo my_function();
