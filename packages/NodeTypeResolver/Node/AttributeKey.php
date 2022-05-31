<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\Node;

/**
 * @enum
 */
final class AttributeKey
{
    /**
     * Internal php-parser key for String_, LNumber and DNumber nodes to hold original value (with "_" separators etc.)
     * @var string
     */
    public const RAW_VALUE = 'rawValue';

    /**
     * @var string
     */
    public const VIRTUAL_NODE = 'virtual_node';

    /**
     * @var string
     */
    public const SCOPE = 'scope';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     *
     * @var string
     */
    public const ORIGINAL_NODE = 'origNode';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     *
     * @var string
     */
    public const COMMENTS = 'comments';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     *
     * @var string
     */
    public const ORIGINAL_NAME = 'originalName';

    /**
     * Internal php-parser name. @see \PhpParser\NodeVisitor\NameResolver
     * Do not change this even if you want!
     *
     * @var string
     */
    public const RESOLVED_NAME = 'resolvedName';

    /**
     * @internal of php-parser, do not change
     * @see https://github.com/nikic/PHP-Parser/pull/681/files
     * @var string
     */
    public const PARENT_NODE = 'parent';

    /**
     * @internal of php-parser, do not change
     * @see https://github.com/nikic/PHP-Parser/pull/681/files
     * @var string
     */
    public const PREVIOUS_NODE = 'previous';

    /**
     * @internal of php-parser, do not change
     * @see https://github.com/nikic/PHP-Parser/pull/681/files
     * @var string
     */
    public const NEXT_NODE = 'next';

    /**
     * @deprecated Instead of tree climbing without context, hook into parent node that contains the stmts directly.
     * E.g. FunctionLike, If_, While_ etc.
     * @var string
     */
    public const PREVIOUS_STATEMENT = 'previousExpression';

    /**
     * @deprecated Instead of tree climbing without context, hook into parent node that contains the stmts directly.
     * E.g. FunctionLike, If_, While_ etc.
     * Use @see \Rector\Core\PhpParser\Node\BetterNodeFinder::resolveCurrentStatement() instead if actually needed
     * @var string
     */
    public const CURRENT_STATEMENT = 'currentExpression';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     *
     * @var string
     */
    public const NAMESPACED_NAME = 'namespacedName';

    /**
     * Internal php-parser name.
     * Do not change this even if you want!
     *
     * @var string
     */
    public const DOC_INDENTATION = 'docIndentation';

    /**
     * @var string
     * Use often in php-parser
     */
    public const KIND = 'kind';

    /**
     * @var string
     */
    public const IS_UNREACHABLE = 'isUnreachable';

    /**
     * @var string
     */
    public const PHP_DOC_INFO = 'php_doc_info';

    /**
     * @var string
     */
    public const IS_REGULAR_PATTERN = 'is_regular_pattern';

    /**
     * @var string
     */
    public const DO_NOT_CHANGE = 'do_not_change';

    /**
     * @var string
     */
    public const PARAMETER_POSITION = 'parameter_position';

    /**
     * @var string
     */
    public const ARGUMENT_POSITION = 'argument_position';

    /**
     * @var string
     */
    public const FUNC_ARGS_TRAILING_COMMA = 'trailing_comma';

    /**
     * Contains current file object
     * @see \Rector\Core\ValueObject\Application\File
     *
     * @var string
     */
    public const FILE = 'file';

    /**
     * Helps with infinite loop detection
     * @var string
     */
    public const CREATED_BY_RULE = 'created_by_rule';

    /**
     * @var string
     */
    public const WRAPPED_IN_PARENTHESES = 'wrapped_in_parentheses';

    /**
     * @var string
     */
    public const COMMENT_CLOSURE_RETURN_MIRRORED = 'comment_closure_return_mirrored';
}
