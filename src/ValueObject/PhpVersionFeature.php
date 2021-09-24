<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

final class PhpVersionFeature
{
    /**
     * @var int
     */
    public const PROPERTY_MODIFIER = PhpVersion::PHP_52;

    /**
     * @var int
     */
    public const CONTINUE_TO_BREAK = PhpVersion::PHP_52;

    /**
     * @var int
     */
    public const NO_REFERENCE_IN_NEW = PhpVersion::PHP_53;

    /**
     * @var int
     */
    public const SERVER_VAR = PhpVersion::PHP_53;

    /**
     * @var int
     */
    public const DIR_CONSTANT = PhpVersion::PHP_53;

    /**
     * @var int
     */
    public const ELVIS_OPERATOR = PhpVersion::PHP_53;

    /**
     * @var int
     */
    public const NO_ZERO_BREAK = PhpVersion::PHP_54;

    /**
     * @var int
     */
    public const NO_REFERENCE_IN_ARG = PhpVersion::PHP_54;

    /**
     * @var int
     */
    public const DATE_TIME_INTERFACE = PhpVersion::PHP_55;

    /**
     * @see https://wiki.php.net/rfc/class_name_scalars
     * @var int
     */
    public const CLASSNAME_CONSTANT = PhpVersion::PHP_55;

    /*
    * @var int
    */
   public const PREG_REPLACE_CALLBACK_E_MODIFIER = PhpVersion::PHP_55;

    /**
     * @var int
     */
    public const EXP_OPERATOR = PhpVersion::PHP_56;

    /**
     * @var int
     */
    public const REQUIRE_DEFAULT_VALUE = PhpVersion::PHP_56;

    /**
     * @var int
     */
    public const SCALAR_TYPES = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const NULL_COALESCE = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const LIST_SWAP_ORDER = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const SPACESHIP = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const DIRNAME_LEVELS = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const CSPRNG_FUNCTIONS = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const THROWABLE_TYPE = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const NO_LIST_SPLIT_STRING = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const NO_BREAK_OUTSIDE_LOOP = PhpVersion::PHP_70;

    /**
     * @var int
     */
    public const ITERABLE_TYPE = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const VOID_TYPE = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const CONSTANT_VISIBILITY = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const ARRAY_DESTRUCT = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const MULTI_EXCEPTION_CATCH = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const OBJECT_TYPE = PhpVersion::PHP_72;

    /**
     * @var int
     */
    public const IS_COUNTABLE = PhpVersion::PHP_73;

    /**
     * @var int
     */
    public const ARRAY_KEY_FIRST_LAST = PhpVersion::PHP_73;

    /**
     * @var int
     */
    public const JSON_EXCEPTION = PhpVersion::PHP_73;

    /**
     * @var int
     */
    public const SETCOOKIE_ACCEPT_ARRAY_OPTIONS = PhpVersion::PHP_73;

    /**
     * @var int
     */
    public const ARROW_FUNCTION = PhpVersion::PHP_74;

    /**
     * @var int
     */
    public const LITERAL_SEPARATOR = PhpVersion::PHP_74;

    /**
     * @var int
     */
    public const NULL_COALESCE_ASSIGN = PhpVersion::PHP_74;

    /**
     * @var int
     */
    public const TYPED_PROPERTIES = PhpVersion::PHP_74;

    /**
     * @see https://wiki.php.net/rfc/covariant-returns-and-contravariant-parameters
     * @var int
     */
    public const COVARIANT_RETURN = PhpVersion::PHP_74;

    /**
     * @var int
     */
    public const ARRAY_SPREAD = PhpVersion::PHP_74;

    /**
     * @var int
     */
    public const UNION_TYPES = PhpVersion::PHP_80;

    /**
     * @var int
     */
    public const CLASS_ON_OBJECT = PhpVersion::PHP_80;

    /**
     * @var int
     */
    public const STATIC_RETURN_TYPE = PhpVersion::PHP_80;

    /**
     * @var int
     */
    public const IS_ITERABLE = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const NULLABLE_TYPE = PhpVersion::PHP_71;

    /**
     * @var int
     */
    public const PARENT_VISIBILITY_OVERRIDE = PhpVersion::PHP_72;

    /**
     * @var int
     */
    public const COUNT_ON_NULL = PhpVersion::PHP_71;

    /**
     * @see https://wiki.php.net/rfc/constructor_promotion
     * @var int
     */
    public const PROPERTY_PROMOTION = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/attributes_v2
     * @var int
     */
    public const ATTRIBUTES = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/noreturn_type
     * @var int
     */
    public const NEVER_TYPE = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/variadics
     * @var int
     */
    public const VARIADIC_PARAM = PhpVersion::PHP_56;

    /**
     * @see https://wiki.php.net/rfc/readonly_and_immutable_properties
     * @var int
     */
    public const READONLY_PROPERTY = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/final_class_const
     * @var int
     */
    public const FINAL_CLASS_CONSTANTS = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/enumerations
     * @var int
     */
    public const ENUM = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/match_expression_v2
     * @var int
     */
    public const MATCH_EXPRESSION = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/non-capturing_catches
     * @var int
     */
    public const NON_CAPTURING_CATCH = PhpVersion::PHP_80;
}
