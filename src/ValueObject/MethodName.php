<?php

declare(strict_types=1);

namespace Rector\ValueObject;

/**
 * @api
 * @enum
 */
final class MethodName
{
    public const string __SET = '__set';

    public const string __GET = '__get';

    public const string CONSTRUCT = '__construct';

    public const string DESTRUCT = '__destruct';

    public const string CLONE = '__clone';

    /**
     * Mostly used in unit tests
     * @see https://phpunit.readthedocs.io/en/9.3/fixtures.html#more-setup-than-teardown
     */
    public const string SET_UP = 'setUp';

    public const string SET_STATE = '__set_state';

    /**
     * @see https://phpunit.readthedocs.io/en/9.3/fixtures.html#fixtures-sharing-fixture-examples-databasetest-php
     */
    public const string SET_UP_BEFORE_CLASS = 'setUpBeforeClass';

    public const string CALL = '__call';

    public const string CALL_STATIC = '__callStatic';

    public const string TO_STRING = '__toString';

    public const string INVOKE = '__invoke';

    public const string ISSET = '__isset';

    public const string UNSET = '__unset';
}
