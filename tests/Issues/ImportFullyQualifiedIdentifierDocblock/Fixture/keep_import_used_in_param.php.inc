<?php

declare(strict_types=1);

namespace CodeIgniter\HTTP;

use Config\App;

final class Response
{
    public $property;

    /**
     * @param App $config
     *
     * @deprecated The param $config is no longer used.
     */
    public function __construct($config)
    {
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace CodeIgniter\HTTP;

use DateTime;
use Config\App;

final class Response
{
    /**
     * @var DateTime
     */
    public $property;

    /**
     * @param App $config
     *
     * @deprecated The param $config is no longer used.
     */
    public function __construct($config)
    {
    }
}

?>
