<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\Enum;

final class TypeKind
{
    public const string PROPERTY = 'property';

    public const string RETURN = 'return';

    public const string PARAM = 'param';

    public const string UNION = 'union';
}
