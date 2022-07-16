<?php

declare(strict_types=1);

namespace Rector\Core\Validation;

use Rector\Core\Util\StringUtils;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @see \Rector\Core\Tests\Validation\RectorAssertTest
 */
final class RectorAssert
{
    /**
     * @see https://stackoverflow.com/a/12011255/1348344
     * @see https://regex101.com/r/PYQaPF/1
     * @var string
     */
    private const CLASS_NAME_REGEX = '#^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*$#';

    /**
     * @see https://www.php.net/manual/en/language.variables.basics.php
     * @see https://regex101.com/r/hFw17T/1
     *
     * @var string
     */
    private const PROPERTY_NAME_REGEX = '#^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$#';

    /**
     * @see https://regex101.com/r/uh5B0S/1
     * @see https://www.php.net/manual/en/functions.user-defined.php
     *
     * @var string
     */
    private const METHOD_NAME_REGEX = '#^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$#';

    /**
     * Assert value is valid class name
     */
    public static function className(string $name): void
    {
        if (StringUtils::isMatch($name, self::CLASS_NAME_REGEX)) {
            return;
        }

        $errorMessage = sprintf('"%s" is not a valid class name', $name);
        throw new InvalidArgumentException($errorMessage);
    }

    public static function propertyName(string $name): void
    {
        if (StringUtils::isMatch($name, self::PROPERTY_NAME_REGEX)) {
            return;
        }

        $errorMessage = sprintf('"%s" is not a valid property name', $name);
        throw new InvalidArgumentException($errorMessage);
    }

    public static function methodName(string $name): void
    {
        if (StringUtils::isMatch($name, self::METHOD_NAME_REGEX)) {
            return;
        }

        $errorMessage = sprintf('"%s" is not a valid method name', $name);
        throw new InvalidArgumentException($errorMessage);
    }
}
