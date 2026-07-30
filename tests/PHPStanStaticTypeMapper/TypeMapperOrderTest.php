<?php

declare(strict_types=1);

namespace Rector\Tests\PHPStanStaticTypeMapper;

use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class TypeMapperOrderTest extends AbstractLazyTestCase
{
    /**
     * The mappers are matched with is_a() in registration order, so a mapper for a child type
     * must come before the mapper for its parent type. Otherwise the parent one always wins
     * and the child one is never reached.
     */
    public function testChildTypeMapperIsRegisteredBeforeItsParent(): void
    {
        $typeMappers = $this->resolveTypeMappers();

        foreach ($typeMappers as $position => $typeMapper) {
            $nodeClass = $typeMapper->getNodeClass();

            for ($earlierPosition = 0; $earlierPosition < $position; ++$earlierPosition) {
                $earlierNodeClass = $typeMappers[$earlierPosition]->getNodeClass();

                $this->assertFalse(is_a($nodeClass, $earlierNodeClass, true), sprintf(
                    'The "%s" is registered after "%s", but "%s" is a "%s". It can never be reached, register it earlier.',
                    $typeMapper::class,
                    $typeMappers[$earlierPosition]::class,
                    $nodeClass,
                    $earlierNodeClass
                ));
            }
        }
    }

    /**
     * @return TypeMapperInterface[]
     */
    private function resolveTypeMappers(): array
    {
        $typeMappers = iterator_to_array(self::getContainer()->tagged(TypeMapperInterface::class));

        $this->assertNotEmpty($typeMappers);

        return array_values($typeMappers);
    }
}
