<?php

namespace Rector\Tests\Issues\CustomPhpDocTagClassReference\Fixture;

use Rector\Tests\Issues\CustomPhpDocTagClassReference\Source\NotificationHandler;

/**
 * @throwable-from NotificationHandler::createPayload
 */
final class SkipCustomTagWithClassReference extends \Exception
{
}
