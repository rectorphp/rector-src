<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Rules\Rule;
use PHPStan\Type\TypeWithClassName;
use Rector\Contract\Rector\RectorInterface;
use TomasVotruba\CognitiveComplexity\AstCognitiveComplexityAnalyzer;

/**
 * @implements Rule<InClassNode>
 * This rule helps to find overly complex rules, that usually have little value, but are costrly to run.
 */
final readonly class LongAndDependentComplexRectorRule implements Rule
{
    /**
     * @var int
     */
    private const ALLOWED_TRANSITIONAL_COMPLEXITY = 140;

    private Parser $phpParser;

    private NodeFinder $nodeFinder;

    public function __construct(
        private AstCognitiveComplexityAnalyzer $astCognitiveComplexityAnalyzer,
    ) {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $this->nodeFinder = new NodeFinder();
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        // check only rector rules
        $classReflection = $node->getClassReflection();
        if (! $classReflection->isSubclassOf(RectorInterface::class)) {
            return [];
        }

        // not much complex
        if (! $classReflection->hasConstructor()) {
            return [];
        }

        $extendedMethodReflection = $classReflection->getConstructor();
        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle(
            $extendedMethodReflection->getVariants()
        );

        $originalClassLike = $node->getOriginalNode();
        if (! $originalClassLike instanceof Class_) {
            return [];
        }

        $currentClassLikeComplexity = $this->astCognitiveComplexityAnalyzer->analyzeClassLike($originalClassLike);
        $totalTransitionalComplexity = $currentClassLikeComplexity;

        foreach ($parametersAcceptorWithPhpDocs->getParameters() as $parameterReflectionWithPhpDoc) {
            $parameterType = $parameterReflectionWithPhpDoc->getType();
            if (! $parameterType instanceof TypeWithClassName) {
                continue;
            }

            $parameterClassReflection = $parameterType->getClassReflection();
            if (! $parameterClassReflection instanceof ClassReflection) {
                continue;
            }

            $dependencyClass = $this->parseClassReflectionToClassNode($parameterClassReflection);
            if (! $dependencyClass instanceof Class_) {
                continue;
            }

            $dependencyComplexity = $this->astCognitiveComplexityAnalyzer->analyzeClassLike($dependencyClass);
            $totalTransitionalComplexity += $dependencyComplexity;
        }

        if ($totalTransitionalComplexity < self::ALLOWED_TRANSITIONAL_COMPLEXITY) {
            return [];
        }

        return [sprintf(
            'Transitional dependency complexity %d is over %d, please consider splitting it up.',
            $totalTransitionalComplexity,
            self::ALLOWED_TRANSITIONAL_COMPLEXITY
        )];
    }

    private function parseClassReflectionToClassNode(ClassReflection $classReflection): ?Class_
    {
        $fileName = $classReflection->getFileName();
        if (! is_string($fileName)) {
            return null;
        }

        $fileContents = FileSystem::read($fileName);

        $stmts = $this->phpParser->parse($fileContents);
        if ($stmts === null) {
            return null;
        }

        $foundNode = $this->nodeFinder->findFirstInstanceOf($stmts, Class_::class);
        if (! $foundNode instanceof Class_) {
            return null;
        }

        return $foundNode;
    }
}
