<?php

declare(strict_types=1);

namespace Rector\CustomRules;

use PhpParser\Node;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

/**
 * Inspired by @see \PhpParser\NodeDumper
 */
final class SimpleNodeDumper
{
    /**
     * @param Node[]|Node|mixed[] $node
     */
    public static function dump(array|Node $node, bool $rootNode = true): string
    {
        // display single root node directly to avoid useless nesting in output
        if (is_array($node) && count($node) === 1 && $rootNode) {
            $node = $node[0];
        }

        if ($node instanceof Node) {
            $result = get_class($node);

            if ($node instanceof Node\Expr\Variable && is_string($node->name)) {
                $result .= '( name: "' . $node->name . '" )';
            } elseif ($node instanceof Node\Identifier) {
                $result .= '( name: "' . $node->name . '" )';
            } elseif ($node instanceof Node\Name) {
                $result .= '( parts: ' . json_encode($node->parts) . ' )';
            }

            // simple nodes
            elseif ($node instanceof Node\Scalar && $node->getSubNodeNames() === ['value']) {
                if (is_string($node->value)) {
                    $result .= '( value: "' . $node->value . '" )';
                } else {
                    $result .= '( value: ' . $node->value . ' )';
                }
            } else {
                $result .= '(';

                foreach ($node->getSubNodeNames() as $key) {
                    $result .= "\n    " . $key . ': ';

                    $value = $node->{$key};
                    if ($value === null) {
                        $result .= 'null';
                    } elseif ($value === false) {
                        $result .= 'false';
                    } elseif ($value === true) {
                        $result .= 'true';
                    } elseif (is_scalar($value)) {
                        if ($key === 'flags' || $key === 'newModifier') {
                            $result .= self::dumpFlags($value);
                        } elseif ($key === 'type' && $node instanceof Include_) {
                            $result .= self::dumpIncludeType($value);
                        } elseif ($key === 'type'
                            && ($node instanceof Use_ || $node instanceof UseUse || $node instanceof GroupUse)) {
                            $result .= self::dumpUseType($value);
                        } elseif (is_string($value)) {
                            $result .= '"' . $value . '"';
                        } else {
                            $result .= $value;
                        }
                    } else {
                        $result .= str_replace("\n", "\n    ", self::dump($value, false));
                    }
                }

                $result .= "\n)";
            }
        } else {
            if (self::isStringList($node)) {
                $result = json_encode($node);
            } else {
                $result = '[';

                $valuesCount = count($node);

                foreach ($node as $key => $value) {
                    $result .= "\n    " . $key . ': ';

                    if ($value === null) {
                        $result .= 'null';
                    } elseif ($value === false) {
                        $result .= 'false';
                    } elseif ($value === true) {
                        $result .= 'true';
                    } elseif (is_string($value)) {
                        $result .= '"' . $value . '"';
                    } elseif (is_scalar($value)) {
                        $result .= $value;
                    } else {
                        $result .= str_replace("\n", "\n    ", self::dump($value, false));
                    }
                }

                if (count($node) === 0) {
                    $result .= ']';
                } else {
                    $result .= "\n]";
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed[] $items
     */
    public static function isStringList(array $items): bool
    {
        foreach ($items as $value) {
            if (! is_string($value)) {
                return false;
            }
        }

        return true;
    }

    private static function dumpFlags(mixed $flags): string
    {
        $strs = [];
        if ($flags & Class_::MODIFIER_PUBLIC) {
            $strs[] = 'MODIFIER_PUBLIC';
        }
        if ($flags & Class_::MODIFIER_PROTECTED) {
            $strs[] = 'MODIFIER_PROTECTED';
        }
        if ($flags & Class_::MODIFIER_PRIVATE) {
            $strs[] = 'MODIFIER_PRIVATE';
        }
        if ($flags & Class_::MODIFIER_ABSTRACT) {
            $strs[] = 'MODIFIER_ABSTRACT';
        }
        if ($flags & Class_::MODIFIER_STATIC) {
            $strs[] = 'MODIFIER_STATIC';
        }
        if ($flags & Class_::MODIFIER_FINAL) {
            $strs[] = 'MODIFIER_FINAL';
        }
        if ($flags & Class_::MODIFIER_READONLY) {
            $strs[] = 'MODIFIER_READONLY';
        }

        if ($strs !== []) {
            return implode(' | ', $strs) . ' (' . $flags . ')';
        }

        return (string) $flags;
    }

    private static function dumpIncludeType(int|float|string $type): string
    {
        $map = [
            Include_::TYPE_INCLUDE => 'TYPE_INCLUDE',
            Include_::TYPE_INCLUDE_ONCE => 'TYPE_INCLUDE_ONCE',
            Include_::TYPE_REQUIRE => 'TYPE_REQUIRE',
            Include_::TYPE_REQUIRE_ONCE => 'TYPE_REQUIRE_ONCE',
        ];

        if (! isset($map[$type])) {
            return (string) $type;
        }

        return $map[$type] . ' (' . $type . ')';
    }

    private static function dumpUseType(mixed $type): string
    {
        $map = [
            Use_::TYPE_UNKNOWN => 'TYPE_UNKNOWN',
            Use_::TYPE_NORMAL => 'TYPE_NORMAL',
            Use_::TYPE_FUNCTION => 'TYPE_FUNCTION',
            Use_::TYPE_CONSTANT => 'TYPE_CONSTANT',
        ];

        if (! isset($map[$type])) {
            return (string) $type;
        }

        return $map[$type] . ' (' . $type . ')';
    }
}
