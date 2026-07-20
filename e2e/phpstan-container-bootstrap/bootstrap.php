<?php

if (!isset($container)) {
    echo 'missing global $container variable';
} elseif (get_class($container) === 'PHPStan\DependencyInjection\MemoizingContainer') {
    echo "working as expected\n";
    exit(0);
} else {
    echo '$container has wrong class ' . get_class($container);
}

exit(1);
