<?php

declare(strict_types=1);

// see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
namespace PHPSTORM_META;

use Psr\Container\ContainerInterface;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeAttributes;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;

// $container->get(Type::class) → instance of "Type"
override((new ContainerInterface())->get(0), type(0));

expectedArguments(
    (new Node())->getAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);

expectedArguments(
    (new NodeAttributes())->getAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);

expectedArguments(
    (new Node())->hasAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);


// PhpStorm 2019.1 - add argument autocomplete
// https://blog.jetbrains.com/phpstorm/2019/02/new-phpstorm-meta-php-features/
expectedArguments(
    (new \PhpParser\Node())->getAttribute(),
    0,
    AttributeKey::SCOPE,
    AttributeKey::REPRINT_RAW_VALUE,
    AttributeKey::ORIGINAL_NODE,
    AttributeKey::IS_UNREACHABLE,
    AttributeKey::PHP_DOC_INFO,
    AttributeKey::KIND,
    AttributeKey::IS_REGULAR_PATTERN,
    AttributeKey::ORIGINAL_NAME,
    AttributeKey::COMMENTS,
    AttributeKey::RAW_VALUE,
);

expectedArguments(
    (new \PhpParser\Node())->setAttribute(),
    0,
    AttributeKey::SCOPE,
    AttributeKey::REPRINT_RAW_VALUE,
    AttributeKey::ORIGINAL_NODE,
    AttributeKey::IS_UNREACHABLE,
    AttributeKey::PHP_DOC_INFO,
    AttributeKey::KIND,
    AttributeKey::IS_REGULAR_PATTERN,
    AttributeKey::ORIGINAL_NAME,
    AttributeKey::COMMENTS,
    AttributeKey::RAW_VALUE,
);
