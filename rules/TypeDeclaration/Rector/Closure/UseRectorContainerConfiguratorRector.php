<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Closure;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Closure\UseRectorContainerConfiguratorRector\UseRectorContainerConfiguratorRectorTest
 */
final class UseRectorContainerConfiguratorRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition("Use RectorContainerConfigurator instead Symfony's one", [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
};
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Rector\Core\DependencyInjection\Loader\Configurator\RectorContainerConfigurator;

return static function (RectorContainerConfigurator $containerConfigurator): void {
};
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Closure::class];
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $firstParam = $node->getParams()[0] ?? null;

        if (! $firstParam instanceof Param) {
            return null;
        }

        if (! $this->nodeTypeResolver->isObjectType(
            $firstParam,
            new ObjectType('Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator')
        )) {
            return null;
        }

        $firstParamName = $this->nodeNameResolver->getName($firstParam->var->name);

        if ($firstParamName === null) {
            return null;
        }

        $node->params[0] = $this->nodeFactory->createParamFromNameAndType(
            $firstParamName,
            new ObjectType('Rector\Core\Config\RectorContainerConfigurator')
        );

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }

    private function shouldSkip(Closure $node): bool
    {
        return ! $node->static || $this->betterNodeFinder->findParentType($node, Return_::class) === null;
    }
}
