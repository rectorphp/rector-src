<?php

declare(strict_types=1);

namespace Domain\MyDomain\Exception;

class MyException extends RuntimeException
{
}
?>
-----
<?php

declare(strict_types=1);

namespace Domain\MyDomain\Exception;

final class MyException extends RuntimeException
{
}
?>
