<div>
    some text
</div>

<?php

require(__DIR__ . '/../../vendor/autoload.php');

$dotenv = \Dotenv\Dotenv::createUnsafeMutable(__DIR__ . '/../../');
$dotenv->load();