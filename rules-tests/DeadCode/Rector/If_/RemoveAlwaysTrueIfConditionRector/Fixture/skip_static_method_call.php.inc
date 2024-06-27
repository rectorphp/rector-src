<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class ApiException extends \Exception
{
    public function __construct(
        string $message,
        ?string $code = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->code = $code;
    }
}

class SkipMethodCall {
    private const WEBSITE_ALREADY_EXISTS = 'website_already_exists';

    public function run()
    {
        try {
            return self::someApiAction();
        } catch (ApiException $ex) {
            if ($ex->getCode() !== self::WEBSITE_ALREADY_EXISTS) {
                throw $ex;
            }
        }
    }

    public static function someApiAction()
    {
        if (rand() % 2) {
            throw new ApiException('API Action failed', 'website_already_exists');
        }

        return 1;
    }
}
