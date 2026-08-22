<?php

declare(strict_types=1);

namespace Rector\Exception\DependencyInjection;

use Exception;

/**
 * Names the service that failed to build, keeping the underlying cause as the previous exception.
 * Mirrors the diagnostic the previous container produced, so the failing factory stays visible.
 */
final class ServiceCreationFailedException extends Exception
{
}
