<?php

declare(strict_types=1);

namespace Rector\ValueObject;

/**
 * @api
 */
final class PhpVersionFeature
{
    public const int PROPERTY_MODIFIER = PhpVersion::PHP_52;

    public const int CONTINUE_TO_BREAK = PhpVersion::PHP_52;

    public const int NO_REFERENCE_IN_NEW = PhpVersion::PHP_53;

    public const int SERVER_VAR = PhpVersion::PHP_53;

    public const int DIR_CONSTANT = PhpVersion::PHP_53;

    public const int ELVIS_OPERATOR = PhpVersion::PHP_53;

    public const int ANONYMOUS_FUNCTION_PARAM_TYPE = PhpVersion::PHP_53;

    public const int NO_ZERO_BREAK = PhpVersion::PHP_54;

    public const int NO_REFERENCE_IN_ARG = PhpVersion::PHP_54;

    public const int SHORT_ARRAY = PhpVersion::PHP_54;

    public const int DATE_TIME_INTERFACE = PhpVersion::PHP_55;

    /**
     * @see https://wiki.php.net/rfc/class_name_scalars
     */
    public const int CLASSNAME_CONSTANT = PhpVersion::PHP_55;

    public const int PREG_REPLACE_CALLBACK_E_MODIFIER = PhpVersion::PHP_55;

    public const int EXP_OPERATOR = PhpVersion::PHP_56;

    public const int REQUIRE_DEFAULT_VALUE = PhpVersion::PHP_56;

    public const int SCALAR_TYPES = PhpVersion::PHP_70;

    public const int HAS_RETURN_TYPE = PhpVersion::PHP_70;

    public const int NULL_COALESCE = PhpVersion::PHP_70;

    public const int LIST_SWAP_ORDER = PhpVersion::PHP_70;

    public const int SPACESHIP = PhpVersion::PHP_70;

    public const int DIRNAME_LEVELS = PhpVersion::PHP_70;

    public const int CSPRNG_FUNCTIONS = PhpVersion::PHP_70;

    public const int THROWABLE_TYPE = PhpVersion::PHP_70;

    public const int NO_LIST_SPLIT_STRING = PhpVersion::PHP_70;

    public const int NO_BREAK_OUTSIDE_LOOP = PhpVersion::PHP_70;

    public const int NO_PHP4_CONSTRUCTOR = PhpVersion::PHP_70;

    public const int NO_CALL_USER_METHOD = PhpVersion::PHP_70;

    public const int NO_EREG_FUNCTION = PhpVersion::PHP_70;

    public const int VARIABLE_ON_FUNC_CALL = PhpVersion::PHP_70;

    public const int NO_MKTIME_WITHOUT_ARG = PhpVersion::PHP_70;

    public const int NO_EMPTY_LIST = PhpVersion::PHP_70;

    /**
     * @see https://php.watch/versions/8.0/non-static-static-call-fatal-error
     * Deprecated since PHP 7.0
     */
    public const int STATIC_CALL_ON_NON_STATIC = PhpVersion::PHP_70;

    public const int INSTANCE_CALL = PhpVersion::PHP_70;

    public const int NO_MULTIPLE_DEFAULT_SWITCH = PhpVersion::PHP_70;

    public const int WRAP_VARIABLE_VARIABLE = PhpVersion::PHP_70;

    public const int ANONYMOUS_FUNCTION_RETURN_TYPE = PhpVersion::PHP_70;

    public const int ITERABLE_TYPE = PhpVersion::PHP_71;

    public const int VOID_TYPE = PhpVersion::PHP_71;

    public const int CONSTANT_VISIBILITY = PhpVersion::PHP_71;

    public const int ARRAY_DESTRUCT = PhpVersion::PHP_71;

    public const int MULTI_EXCEPTION_CATCH = PhpVersion::PHP_71;

    public const int NO_ASSIGN_ARRAY_TO_STRING = PhpVersion::PHP_71;

    public const int BINARY_OP_NUMBER_STRING = PhpVersion::PHP_71;

    public const int NO_EXTRA_PARAMETERS = PhpVersion::PHP_71;

    public const int RESERVED_OBJECT_KEYWORD = PhpVersion::PHP_71;

    public const int DEPRECATE_EACH = PhpVersion::PHP_72;

    public const int OBJECT_TYPE = PhpVersion::PHP_72;

    public const int NO_EACH_OUTSIDE_LOOP = PhpVersion::PHP_72;

    public const int DEPRECATE_CREATE_FUNCTION = PhpVersion::PHP_72;

    public const int NO_NULL_ON_GET_CLASS = PhpVersion::PHP_72;

    public const int INVERTED_BOOL_IS_OBJECT_INCOMPLETE_CLASS = PhpVersion::PHP_72;

    public const int RESULT_ARG_IN_PARSE_STR = PhpVersion::PHP_72;

    public const int STRING_IN_FIRST_DEFINE_ARG = PhpVersion::PHP_72;

    public const int STRING_IN_ASSERT_ARG = PhpVersion::PHP_72;

    public const int NO_UNSET_CAST = PhpVersion::PHP_72;

    public const int IS_COUNTABLE = PhpVersion::PHP_73;

    public const int ARRAY_KEY_FIRST_LAST = PhpVersion::PHP_73;

    /**
     * @see https://php.watch/versions/8.5/array_first-array_last
     */
    public const int ARRAY_FIRST_LAST = PhpVersion::PHP_85;

    public const int JSON_EXCEPTION = PhpVersion::PHP_73;

    public const int SETCOOKIE_ACCEPT_ARRAY_OPTIONS = PhpVersion::PHP_73;

    public const int DEPRECATE_INSENSITIVE_CONSTANT_NAME = PhpVersion::PHP_73;

    public const int ESCAPE_DASH_IN_REGEX = PhpVersion::PHP_73;

    public const int DEPRECATE_INSENSITIVE_CONSTANT_DEFINE = PhpVersion::PHP_73;

    public const int DEPRECATE_INT_IN_STR_NEEDLES = PhpVersion::PHP_73;

    public const int SENSITIVE_HERE_NOW_DOC = PhpVersion::PHP_73;

    public const int ARROW_FUNCTION = PhpVersion::PHP_74;

    public const int LITERAL_SEPARATOR = PhpVersion::PHP_74;

    public const int NULL_COALESCE_ASSIGN = PhpVersion::PHP_74;

    public const int TYPED_PROPERTIES = PhpVersion::PHP_74;

    /**
     * @see https://wiki.php.net/rfc/covariant-returns-and-contravariant-parameters
     */
    public const int COVARIANT_RETURN = PhpVersion::PHP_74;

    public const int ARRAY_SPREAD = PhpVersion::PHP_74;

    public const int DEPRECATE_CURLY_BRACKET_ARRAY_STRING = PhpVersion::PHP_74;

    public const int DEPRECATE_REAL = PhpVersion::PHP_74;

    public const int DEPRECATE_MONEY_FORMAT = PhpVersion::PHP_74;

    public const int ARRAY_KEY_EXISTS_TO_PROPERTY_EXISTS = PhpVersion::PHP_74;

    public const int FILTER_VAR_TO_ADD_SLASHES = PhpVersion::PHP_74;

    public const int CHANGE_MB_STRPOS_ARG_POSITION = PhpVersion::PHP_74;

    public const int RESERVED_FN_FUNCTION_NAME = PhpVersion::PHP_74;

    public const int REFLECTION_TYPE_GETNAME = PhpVersion::PHP_74;

    public const int EXPORT_TO_REFLECTION_FUNCTION = PhpVersion::PHP_74;

    public const int DEPRECATE_NESTED_TERNARY = PhpVersion::PHP_74;

    public const int DEPRECATE_RESTORE_INCLUDE_PATH = PhpVersion::PHP_74;

    public const int DEPRECATE_HEBREVC = PhpVersion::PHP_74;

    public const int UNION_TYPES = PhpVersion::PHP_80;

    public const int CLASS_ON_OBJECT = PhpVersion::PHP_80;

    public const int STATIC_RETURN_TYPE = PhpVersion::PHP_80;

    public const int NO_FINAL_PRIVATE = PhpVersion::PHP_80;

    public const int DEPRECATE_REQUIRED_PARAMETER_AFTER_OPTIONAL = PhpVersion::PHP_80;

    public const int STATIC_VISIBILITY_SET_STATE = PhpVersion::PHP_80;

    public const int NULLSAFE_OPERATOR = PhpVersion::PHP_80;

    public const int IS_ITERABLE = PhpVersion::PHP_71;

    public const int NULLABLE_TYPE = PhpVersion::PHP_71;

    public const int PARENT_VISIBILITY_OVERRIDE = PhpVersion::PHP_72;

    public const int COUNT_ON_NULL = PhpVersion::PHP_71;

    /**
     * @see https://wiki.php.net/rfc/constructor_promotion
     */
    public const int PROPERTY_PROMOTION = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/attributes_v2
     */
    public const int ATTRIBUTES = PhpVersion::PHP_80;

    public const int STRINGABLE = PhpVersion::PHP_80;

    public const int PHP_TOKEN = PhpVersion::PHP_80;

    public const int STR_ENDS_WITH = PhpVersion::PHP_80;

    public const int STR_STARTS_WITH = PhpVersion::PHP_80;

    public const int STR_CONTAINS = PhpVersion::PHP_80;

    public const int GET_DEBUG_TYPE = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/noreturn_type
     */
    public const int NEVER_TYPE = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/variadics
     */
    public const int VARIADIC_PARAM = PhpVersion::PHP_56;

    /**
     * @see https://wiki.php.net/rfc/readonly_and_immutable_properties
     */
    public const int READONLY_PROPERTY = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/final_class_const
     */
    public const int FINAL_CLASS_CONSTANTS = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/enumerations
     */
    public const int ENUM = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/match_expression_v2
     */
    public const int MATCH_EXPRESSION = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/non-capturing_catches
     */
    public const int NON_CAPTURING_CATCH = PhpVersion::PHP_80;

    /**
     * @see https://www.php.net/manual/en/migration80.incompatible.php#migration80.incompatible.resource2object
     */
    public const int PHP8_RESOURCE_TO_OBJECT = PhpVersion::PHP_80;

    /**
     * @see https://wiki.php.net/rfc/lsp_errors
     */
    public const int FATAL_ERROR_ON_INCOMPATIBLE_METHOD_SIGNATURE = PhpVersion::PHP_80;

    /**
     * @see https://www.php.net/manual/en/migration81.incompatible.php#migration81.incompatible.resource2object
     */
    public const int PHP81_RESOURCE_TO_OBJECT = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/new_in_initializers
     */
    public const int NEW_INITIALIZERS = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/pure-intersection-types
     */
    public const int INTERSECTION_TYPES = PhpVersion::PHP_81;

    /**
     * @see https://php.watch/versions/8.2/dnf-types
     */
    public const int UNION_INTERSECTION_TYPES = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/array_unpacking_string_keys
     */
    public const int ARRAY_SPREAD_STRING_KEYS = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/internal_method_return_types
     */
    public const int RETURN_TYPE_WILL_CHANGE_ATTRIBUTE = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/first_class_callable_syntax
     */
    public const int FIRST_CLASS_CALLABLE_SYNTAX = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/deprecate_dynamic_properties
     */
    public const int DEPRECATE_DYNAMIC_PROPERTIES = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/readonly_classes
     */
    public const int READONLY_CLASS = PhpVersion::PHP_82;

    /**
     * @see https://www.php.net/manual/en/migration83.new-features.php#migration83.new-features.core.readonly-modifier-improvements
     */
    public const int READONLY_ANONYMOUS_CLASS = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/json_validate
     */
    public const int JSON_VALIDATE = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/mixed_type_v2
     */
    public const int MIXED_TYPE = PhpVersion::PHP_80;

    /**
     * @see https://3v4l.org/OWtO5
     */
    public const int ARRAY_ON_ARRAY_MERGE = PhpVersion::PHP_80;

    public const int DEPRECATE_NULL_ARG_IN_STRING_FUNCTION = PhpVersion::PHP_81;

    /**
     * @see https://wiki.php.net/rfc/remove_utf8_decode_and_utf8_encode
     */
    public const int DEPRECATE_UTF8_DECODE_ENCODE_FUNCTION = PhpVersion::PHP_82;

    /**
     * @see https://www.php.net/manual/en/filesystemiterator.construct
     */
    public const int FILESYSTEM_ITERATOR_SKIP_DOTS = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/null-false-standalone-types
     * @see https://wiki.php.net/rfc/true-type
     */
    public const int NULL_FALSE_TRUE_STANDALONE_TYPE = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/redact_parameters_in_back_traces
     */
    public const int SENSITIVE_PARAMETER_ATTRIBUTE = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation
     */
    public const int DEPRECATE_VARIABLE_IN_STRING_INTERPOLATION = PhpVersion::PHP_82;

    /**
     * @see https://wiki.php.net/rfc/marking_overriden_methods
     */
    public const int OVERRIDE_ATTRIBUTE = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/typed_class_constants
     */
    public const int TYPED_CLASS_CONSTANTS = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/dynamic_class_constant_fetch
     */
    public const int DYNAMIC_CLASS_CONST_FETCH = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/deprecate-implicitly-nullable-types
     */
    public const int DEPRECATE_IMPLICIT_NULLABLE_PARAM_TYPE = PhpVersion::PHP_84;

    /**
     * @see https://wiki.php.net/rfc/new_without_parentheses
     */
    public const int NEW_METHOD_CALL_WITHOUT_PARENTHESES = PhpVersion::PHP_84;

    /**
     * @see https://wiki.php.net/rfc/correctly_name_the_rounding_mode_and_make_it_an_enum
     */
    public const int ROUNDING_MODES = PhpVersion::PHP_84;

    /**
     * @see https://php.watch/versions/8.4/csv-functions-escape-parameter
     */
    public const int REQUIRED_ESCAPE_PARAMETER = PhpVersion::PHP_84;

    /**
     * @see https://www.php.net/manual/en/migration83.deprecated.php#migration83.deprecated.ldap
     */
    public const int DEPRECATE_HOST_PORT_SEPARATE_ARGS = PhpVersion::PHP_83;

    /**
     * @see https://www.php.net/manual/en/migration83.deprecated.php#migration83.deprecated.core.get-class
     * @see https://php.watch/versions/8.3/get_class-get_parent_class-parameterless-deprecated
     */
    public const int DEPRECATE_GET_CLASS_WITHOUT_ARGS = PhpVersion::PHP_83;

    /**
     * @see https://wiki.php.net/rfc/deprecated_attribute
     */
    public const int DEPRECATED_ATTRIBUTE = PhpVersion::PHP_84;

    /**
     * @see https://php.watch/versions/8.4/array_find-array_find_key-array_any-array_all
     */
    public const int ARRAY_FIND = PhpVersion::PHP_84;

    /**
     * @see https://php.watch/versions/8.4/array_find-array_find_key-array_any-array_all
     */
    public const int ARRAY_FIND_KEY = PhpVersion::PHP_84;

    /**
     * @see https://php.watch/versions/8.4/array_find-array_find_key-array_any-array_all
     */
    public const int ARRAY_ALL = PhpVersion::PHP_84;

    /**
     * @see https://php.watch/versions/8.4/array_find-array_find_key-array_any-array_all
     */
    public const int ARRAY_ANY = PhpVersion::PHP_84;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_context_parameter_for_finfo_buffer
     */
    public const int DEPRECATE_FINFO_BUFFER_CONTEXT = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_debuginfo_returning_null
     */
    public const int DEPRECATED_NULL_DEBUG_INFO_RETURN = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_semicolon_after_case_in_switch_statement
     */
    public const int COLON_AFTER_SWITCH_CASE = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_using_values_null_as_an_array_offset_and_when_calling_array_key_exists
     */
    public const int DEPRECATE_NULL_ARG_IN_ARRAY_KEY_EXISTS_FUNCTION = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#eprecate_passing_integers_outside_the_interval_0_255_to_chr
     */
    public const int DEPRECATE_OUTSIDE_INTERVEL_VAL_IN_CHR_FUNCTION = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_sleep_and_wakeup_magic_methods
     */
    public const int DEPRECATED_METHOD_SLEEP = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_sleep_and_wakeup_magic_methods
     */
    public const int DEPRECATED_METHOD_WAKEUP = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_passing_string_which_are_not_one_byte_long_to_ord
     */
    public const int DEPRECATE_ORD_WITH_MULTIBYTE_STRING = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/property-hooks
     */
    public const int PROPERTY_HOOKS = PhpVersion::PHP_84;

    /**
     * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_backticks_as_an_alias_for_shell_exec
     */
    public const int DEPRECATE_BACKTICKS = PhpVersion::PHP_85;

    /**
     * @see https://wiki.php.net/rfc/pipe-operator-v3
     */
    public const int PIPE_OPERATOER = PhpVersion::PHP_85;
}
