<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Node;

use PHPStan\Analyser\Scope;

/**
 * @enum
 */
final class AttributeKey
{
    /**
     * Internal php-parser key for String_, Int_ and Float_ nodes to hold original value (with "_" separators etc.)
     */
    public const string RAW_VALUE = 'rawValue';

    /**
     * @see Scope
     */
    public const string SCOPE = 'scope';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     */
    public const string ORIGINAL_NODE = 'origNode';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     */
    public const string COMMENTS = 'comments';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     */
    public const string ORIGINAL_NAME = 'originalName';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     */
    public const string NAMESPACED_NAME = 'namespacedName';

    /**
     * @api
     *
     * Internal php-parser name.
     * Do not change this even if you want!
     */
    public const string DOC_INDENTATION = 'docIndentation';

    /**
     * @var string
     * Use often in php-parser
     */
    public const string KIND = 'kind';

    public const string PHP_DOC_INFO = 'php_doc_info';

    public const string IS_REGULAR_PATTERN = 'is_regular_pattern';

    /**
     * Helps with infinite loop detection
     */
    public const string CREATED_BY_RULE = 'created_by_rule';

    public const string WRAPPED_IN_PARENTHESES = 'wrapped_in_parentheses';

    /**
     * To pass PHP 8.0 attribute FQN names
     */
    public const string PHP_ATTRIBUTE_NAME = 'php_attribute_name';

    public const string EXTRA_USE_IMPORT = 'extra_use_import';

    /**
     * Used internally by php-parser
     */
    public const string DOC_LABEL = 'docLabel';

    /**
     * Prints array in newlined fastion, one item per line
     */
    public const string NEWLINED_ARRAY_PRINT = 'newlined_array_print';

    public const string IS_ASSIGNED_TO = 'is_assigned_to';

    public const string IS_GLOBAL_VAR = 'is_global_var';

    public const string IS_STATIC_VAR = 'is_static_var';

    public const string IS_BYREF_VAR = 'is_byref_var';

    public const string IS_BYREF_RETURN = 'is_byref_return';

    public const string IS_BEING_ASSIGNED = 'is_being_assigned';

    public const string IS_ASSIGN_OP_VAR = 'is_assign_op_var';

    public const string IS_ASSIGN_REF_EXPR = 'is_assign_ref_expr';

    public const string IS_MULTI_ASSIGN = 'is_multi_assign';

    public const string IS_IN_LOOP_OR_SWITCH = 'is_in_loop';

    public const string IS_VARIABLE_LOOP = 'is_variable_loop';

    public const string IS_IN_IF = 'is_in_if';

    public const string IS_UNSET_VAR = 'is_unset_var';

    public const string IS_ISSET_VAR = 'is_isset_var';

    public const string IS_ARRAY_IN_ATTRIBUTE = 'is_array_in_attribute';

    public const string IS_CLOSURE_IN_ATTRIBUTE = 'is_closure_in_attribute';

    public const string IS_STATICCALL_CLASS_NAME = 'is_staticcall_class_name';

    public const string IS_FUNCCALL_NAME = 'is_funccall_name';

    public const string IS_CONSTFETCH_NAME = 'is_constfetch_name';

    public const string IS_NEW_INSTANCE_NAME = 'is_new_instance_name';

    public const string IS_ARG_VALUE = 'is_arg_value';

    public const string IS_PARAM_VAR = 'is_param_var';

    public const string IS_PARAM_DEFAULT = 'is_param_default';

    public const string IS_INCREMENT_OR_DECREMENT = 'is_increment_or_decrement';

    public const string IS_RIGHT_AND = 'is_right_and';

    public const string IS_CLASS_EXTENDS = 'is_class_extends';

    public const string IS_CLASS_IMPLEMENT = 'is_class_implement';

    public const string FROM_FUNC_CALL_NAME = 'from_func_call_name';

    public const string INSIDE_ARRAY_DIM_FETCH = 'inside_array_dim_fetch';

    public const string IS_USED_AS_ARG_BY_REF_VALUE = 'is_used_as_arg_by_ref_value';

    public const string IS_FIRST_LEVEL_STATEMENT = 'first_level_stmt';

    public const string IS_DEFAULT_PROPERTY_VALUE = 'is_default_property_value';

    public const string IS_CLASS_CONST_VALUE = 'is_default_class_const_value';

    public const string IS_INSIDE_SYMFONY_PHP_CLOSURE = 'is_inside_symfony_php_closure';

    public const string IS_INSIDE_BYREF_FUNCTION_LIKE = 'is_inside_byref_function_like';

    public const string CLASS_CONST_FETCH_NAME = 'class_const_fetch_name';

    public const string PHP_VERSION_CONDITIONED = 'php_version_conditioned';

    public const string IS_CLOSURE_USES_THIS = 'has_this_closure';

    public const string HAS_CLOSURE_WITH_VARIADIC_ARGS = 'has_closure_with_variadic_args';

    public const string IS_IN_TRY_BLOCK = 'is_in_try_block';

    public const string NEWLINE_ON_FLUENT_CALL = 'newline_on_fluent_call';
}
