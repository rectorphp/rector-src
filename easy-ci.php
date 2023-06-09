<?php

declare(strict_types=1);

use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\CodingStyle\Contract\ClassNameImport\ClassNameImportSkipVoterInterface;
use Rector\Core\Contract\Console\OutputStyleInterface;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Contract\PHPStan\Reflection\TypeToCallReflectionResolver\TypeToCallReflectionResolverInterface;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\NodeManipulator\MethodCallManipulator;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\DependencyInjection\PHPStanServicesFactory;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\NodeTypeResolver\Reflection\BetterReflection\RectorBetterReflectionSourceLocatorFactory;
use Rector\Php80\Contract\AttributeDecoratorInterface;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\ReadWrite\Contract\ParentNodeReadAnalyzerInterface;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;
use Rector\Set\Contract\SetListInterface;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Rector\TypeDeclaration\Contract\PHPStan\TypeWithClassTypeSpecifierInterface;
use Symfony\Component\Console\Application;
use Symplify\EasyCI\Config\EasyCIConfig;

return static function (EasyCIConfig $easyCiConfig): void {
    $easyCiConfig->typesToSkip([
        AttributeDecoratorInterface::class,
        ArrayItemNode::class,
        PhpDocNodeDecoratorInterface::class,
        Application::class,
        RectorInterface::class,
        TypeToCallReflectionResolverInterface::class,
        FileProcessorInterface::class,
        ClassNameImportSkipVoterInterface::class,
        PhpDocTypeMapperInterface::class,
        PhpParserNodeMapperInterface::class,
        TypeMapperInterface::class,
        AbstractPhpDocNodeVisitor::class,
        NodeNameResolverInterface::class,
        NodeTypeResolverInterface::class,
        ReadNodeAnalyzerInterface::class,
        SetListInterface::class,
        RectorBetterReflectionSourceLocatorFactory::class,
        AbstractTestCase::class,
        PHPStanServicesFactory::class,
        OutputStyleInterface::class,
        MethodCallManipulator::class,
        // fix later - rector-symfony
        // used in tests
        FileInfoParser::class,
        TypeWithClassTypeSpecifierInterface::class,
        ParentNodeReadAnalyzerInterface::class,
        StmtsAwareInterface::class,

        ScopeResolverNodeVisitorInterface::class,
    ]);
};
