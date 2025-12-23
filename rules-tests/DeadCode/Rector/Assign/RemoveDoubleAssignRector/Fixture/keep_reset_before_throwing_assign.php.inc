<?php

declare(strict_types=1);

/** @throws Exception */
function get_body(): string {
    static $i = 0;

    var_dump($i);

    return $i++ === 0 ? "foo" : throw new Exception("Failed to get body");
}

foreach ([1, 2, 3] as $value) {
    try {
        $body = null;
        $body = get_body();
        var_dump($body);
    } catch (Exception) {
        var_dump($body);
    }

}
?>
