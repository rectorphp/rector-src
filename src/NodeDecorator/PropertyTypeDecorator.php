<?php

declare(strict_types=1);

namespace Rector\Core\NodeDecorator;

use PhpParser\Node\Stmt\Property;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class PropertyTypeDecorator
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly PhpVersionProvider $phpVersionProvider,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
    ) {
    }

    public function decorate(Property $property, ?Type $type): void
    {
        if (! $type instanceof Type) {
            return;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

        if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            $phpParserType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PROPERTY);

            if ($phpParserType !== null) {
                $property->type = $phpParserType;

                if ($type instanceof GenericObjectType) {
                    $this->phpDocTypeChanger->changeVarType($property, $phpDocInfo, $type);
                }

                return;
            }
        }

        $this->phpDocTypeChanger->changeVarType($property, $phpDocInfo, $type);
    }
}
