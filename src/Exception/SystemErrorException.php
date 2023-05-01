<?php

declare(strict_types=1);

namespace Rector\Core\Exception;

use Exception;
use Rector\Core\ValueObject\Error\SystemError;

final class SystemErrorException extends Exception
{
    public function __construct(private readonly SystemError $systemError)
    {
        parent::__construct();
    }

    public function getSystemError(): SystemError
    {
        return $this->systemError;
    }
}
