<?php

declare(strict_types=1);

// BC layer for "Rector\\Core\\" to "Rector\\" rename

class_alias('Rector\Application\ApplicationFileProcessor', 'Rector\Core\Application\ApplicationFileProcessor', false);
class_alias('Rector\Application\ChangedNodeScopeRefresher', 'Rector\Core\Application\ChangedNodeScopeRefresher', false);
class_alias('Rector\Application\FileProcessor', 'Rector\Core\Application\FileProcessor', false);
class_alias('Rector\Application\VersionResolver', 'Rector\Core\Application\VersionResolver', false);
class_alias('Rector\Autoloading\AdditionalAutoloader', 'Rector\Core\Autoloading\AdditionalAutoloader', false);
class_alias('Rector\Autoloading\BootstrapFilesIncluder', 'Rector\Core\Autoloading\BootstrapFilesIncluder', false);
class_alias('Rector\Bootstrap\ExtensionConfigResolver', 'Rector\Core\Bootstrap\ExtensionConfigResolver', false);
class_alias('Rector\Bootstrap\RectorConfigsResolver', 'Rector\Core\Bootstrap\RectorConfigsResolver', false);
class_alias('Rector\Configuration\ConfigInitializer', 'Rector\Core\Configuration\ConfigInitializer', false);
class_alias('Rector\Configuration\ConfigurationFactory', 'Rector\Core\Configuration\ConfigurationFactory', false);
class_alias('Rector\Configuration\Option', 'Rector\Core\Configuration\Option', false);
class_alias(
    'Rector\Configuration\Parameter\SimpleParameterProvider',
    'Rector\Core\Configuration\Parameter\SimpleParameterProvider',
    false
);
class_alias('Rector\Console\Command\ListRulesCommand', 'Rector\Core\Console\Command\ListRulesCommand', false);
class_alias('Rector\Console\Command\ProcessCommand', 'Rector\Core\Console\Command\ProcessCommand', false);
class_alias('Rector\Console\Command\SetupCICommand', 'Rector\Core\Console\Command\SetupCICommand', false);
class_alias('Rector\Console\Command\WorkerCommand', 'Rector\Core\Console\Command\WorkerCommand', false);
class_alias('Rector\Console\ConsoleApplication', 'Rector\Core\Console\ConsoleApplication', false);
class_alias('Rector\Console\ExitCode', 'Rector\Core\Console\ExitCode', false);
class_alias(
    'Rector\Console\Formatter\ColorConsoleDiffFormatter',
    'Rector\Core\Console\Formatter\ColorConsoleDiffFormatter',
    false
);
class_alias(
    'Rector\Console\Formatter\CompleteUnifiedDiffOutputBuilderFactory',
    'Rector\Core\Console\Formatter\CompleteUnifiedDiffOutputBuilderFactory',
    false
);
class_alias('Rector\Console\Formatter\ConsoleDiffer', 'Rector\Core\Console\Formatter\ConsoleDiffer', false);
class_alias('Rector\Console\Output\OutputFormatterCollector', 'Rector\Core\Console\Output\OutputFormatterCollector', false);
class_alias('Rector\Console\ProcessConfigureDecorator', 'Rector\Core\Console\ProcessConfigureDecorator', false);
class_alias('Rector\Console\Style\RectorStyle', 'Rector\Core\Console\Style\RectorStyle', false);
class_alias('Rector\Console\Style\SymfonyStyleFactory', 'Rector\Core\Console\Style\SymfonyStyleFactory', false);
class_alias(
    'Rector\Contract\DependencyInjection\ResetableInterface',
    'Rector\Core\Contract\DependencyInjection\ResetableInterface',
    false
);
class_alias(
    'Rector\Contract\PhpParser\Node\StmtsAwareInterface',
    'Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface',
    false
);
class_alias(
    'Rector\Contract\Rector\ConfigurableRectorInterface',
    'Rector\Core\Contract\Rector\ConfigurableRectorInterface',
    false
);
class_alias('Rector\Contract\Rector\RectorInterface', 'Rector\Core\Contract\Rector\RectorInterface', false);
class_alias(
    'Rector\Contract\Rector\ScopeAwareRectorInterface',
    'Rector\Core\Contract\Rector\ScopeAwareRectorInterface',
    false
);
class_alias(
    'Rector\DependencyInjection\Laravel\ContainerMemento',
    'Rector\Core\DependencyInjection\Laravel\ContainerMemento',
    false
);
class_alias('Rector\DependencyInjection\LazyContainerFactory', 'Rector\Core\DependencyInjection\LazyContainerFactory', false);
class_alias(
    'Rector\DependencyInjection\RectorContainerFactory',
    'Rector\Core\DependencyInjection\RectorContainerFactory',
    false
);
class_alias('Rector\Differ\DefaultDiffer', 'Rector\Core\Differ\DefaultDiffer', false);
class_alias('Rector\Enum\ObjectReference', 'Rector\Core\Enum\ObjectReference', false);
class_alias('Rector\Error\ExceptionCorrector', 'Rector\Core\Error\ExceptionCorrector', false);
class_alias('Rector\Exception\Cache\CachingException', 'Rector\Core\Exception\Cache\CachingException', false);
class_alias(
    'Rector\Exception\Configuration\InvalidConfigurationException',
    'Rector\Core\Exception\Configuration\InvalidConfigurationException',
    false
);
class_alias('Rector\Exception\NotImplementedYetException', 'Rector\Core\Exception\NotImplementedYetException', false);
class_alias(
    'Rector\Exception\Reflection\MissingPrivatePropertyException',
    'Rector\Core\Exception\Reflection\MissingPrivatePropertyException',
    false
);
class_alias('Rector\Exception\ShouldNotHappenException', 'Rector\Core\Exception\ShouldNotHappenException', false);
class_alias('Rector\Exception\VersionException', 'Rector\Core\Exception\VersionException', false);
class_alias('Rector\FileSystem\FileAndDirectoryFilter', 'Rector\Core\FileSystem\FileAndDirectoryFilter', false);
class_alias('Rector\FileSystem\FilePathHelper', 'Rector\Core\FileSystem\FilePathHelper', false);
class_alias('Rector\FileSystem\FilesFinder', 'Rector\Core\FileSystem\FilesFinder', false);
class_alias('Rector\FileSystem\FilesystemTweaker', 'Rector\Core\FileSystem\FilesystemTweaker', false);
class_alias('Rector\FileSystem\InitFilePathsResolver', 'Rector\Core\FileSystem\InitFilePathsResolver', false);
class_alias('Rector\NodeAnalyzer\ArgsAnalyzer', 'Rector\Core\NodeAnalyzer\ArgsAnalyzer', false);
class_alias('Rector\NodeAnalyzer\BinaryOpAnalyzer', 'Rector\Core\NodeAnalyzer\BinaryOpAnalyzer', false);
class_alias('Rector\NodeAnalyzer\CallAnalyzer', 'Rector\Core\NodeAnalyzer\CallAnalyzer', false);
class_alias('Rector\NodeAnalyzer\ClassAnalyzer', 'Rector\Core\NodeAnalyzer\ClassAnalyzer', false);
class_alias('Rector\NodeAnalyzer\CompactFuncCallAnalyzer', 'Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer', false);
class_alias('Rector\NodeAnalyzer\ConstFetchAnalyzer', 'Rector\Core\NodeAnalyzer\ConstFetchAnalyzer', false);
class_alias('Rector\NodeAnalyzer\DoctrineEntityAnalyzer', 'Rector\Core\NodeAnalyzer\DoctrineEntityAnalyzer', false);
class_alias('Rector\NodeAnalyzer\ExprAnalyzer', 'Rector\Core\NodeAnalyzer\ExprAnalyzer', false);
class_alias('Rector\NodeAnalyzer\MagicClassMethodAnalyzer', 'Rector\Core\NodeAnalyzer\MagicClassMethodAnalyzer', false);
class_alias('Rector\NodeAnalyzer\ParamAnalyzer', 'Rector\Core\NodeAnalyzer\ParamAnalyzer', false);
class_alias('Rector\NodeAnalyzer\PropertyAnalyzer', 'Rector\Core\NodeAnalyzer\PropertyAnalyzer', false);
class_alias('Rector\NodeAnalyzer\PropertyFetchAnalyzer', 'Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer', false);
class_alias('Rector\NodeAnalyzer\PropertyPresenceChecker', 'Rector\Core\NodeAnalyzer\PropertyPresenceChecker', false);
class_alias('Rector\NodeAnalyzer\ScopeAnalyzer', 'Rector\Core\NodeAnalyzer\ScopeAnalyzer', false);
class_alias('Rector\NodeAnalyzer\TerminatedNodeAnalyzer', 'Rector\Core\NodeAnalyzer\TerminatedNodeAnalyzer', false);
class_alias('Rector\NodeAnalyzer\VariableAnalyzer', 'Rector\Core\NodeAnalyzer\VariableAnalyzer', false);
class_alias('Rector\NodeAnalyzer\VariadicAnalyzer', 'Rector\Core\NodeAnalyzer\VariadicAnalyzer', false);
class_alias('Rector\NodeDecorator\CreatedByRuleDecorator', 'Rector\Core\NodeDecorator\CreatedByRuleDecorator', false);
class_alias('Rector\NodeDecorator\PropertyTypeDecorator', 'Rector\Core\NodeDecorator\PropertyTypeDecorator', false);
class_alias('Rector\NodeManipulator\AssignManipulator', 'Rector\Core\NodeManipulator\AssignManipulator', false);
class_alias('Rector\NodeManipulator\BinaryOpManipulator', 'Rector\Core\NodeManipulator\BinaryOpManipulator', false);
class_alias('Rector\NodeManipulator\ClassConstManipulator', 'Rector\Core\NodeManipulator\ClassConstManipulator', false);
class_alias(
    'Rector\NodeManipulator\ClassDependencyManipulator',
    'Rector\Core\NodeManipulator\ClassDependencyManipulator',
    false
);
class_alias('Rector\NodeManipulator\ClassInsertManipulator', 'Rector\Core\NodeManipulator\ClassInsertManipulator', false);
class_alias('Rector\NodeManipulator\ClassManipulator', 'Rector\Core\NodeManipulator\ClassManipulator', false);
class_alias(
    'Rector\NodeManipulator\ClassMethodAssignManipulator',
    'Rector\Core\NodeManipulator\ClassMethodAssignManipulator',
    false
);
class_alias('Rector\NodeManipulator\ClassMethodManipulator', 'Rector\Core\NodeManipulator\ClassMethodManipulator', false);
class_alias(
    'Rector\NodeManipulator\ClassMethodPropertyFetchManipulator',
    'Rector\Core\NodeManipulator\ClassMethodPropertyFetchManipulator',
    false
);
class_alias('Rector\NodeManipulator\FuncCallManipulator', 'Rector\Core\NodeManipulator\FuncCallManipulator', false);
class_alias('Rector\NodeManipulator\FunctionLikeManipulator', 'Rector\Core\NodeManipulator\FunctionLikeManipulator', false);
class_alias('Rector\NodeManipulator\IfManipulator', 'Rector\Core\NodeManipulator\IfManipulator', false);
class_alias(
    'Rector\NodeManipulator\PropertyFetchAssignManipulator',
    'Rector\Core\NodeManipulator\PropertyFetchAssignManipulator',
    false
);
class_alias('Rector\NodeManipulator\PropertyManipulator', 'Rector\Core\NodeManipulator\PropertyManipulator', false);
class_alias('Rector\NodeManipulator\StmtsManipulator', 'Rector\Core\NodeManipulator\StmtsManipulator', false);
class_alias(
    'Rector\PHPStan\NodeVisitor\ExprScopeFromStmtNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\ExprScopeFromStmtNodeVisitor',
    false
);
class_alias(
    'Rector\PHPStan\NodeVisitor\UnreachableStatementNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\UnreachableStatementNodeVisitor',
    false
);
class_alias(
    'Rector\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor',
    false
);
class_alias('Rector\Php\PhpVersionProvider', 'Rector\Core\Php\PhpVersionProvider', false);
class_alias(
    'Rector\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver',
    'Rector\Core\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver',
    false
);
class_alias('Rector\Php\PolyfillPackagesProvider', 'Rector\Core\Php\PolyfillPackagesProvider', false);
class_alias('Rector\Php\ReservedKeywordAnalyzer', 'Rector\Core\Php\ReservedKeywordAnalyzer', false);
class_alias('Rector\PhpParser\AstResolver', 'Rector\Core\PhpParser\AstResolver', false);
class_alias('Rector\PhpParser\Comparing\NodeComparator', 'Rector\Core\PhpParser\Comparing\NodeComparator', false);
class_alias('Rector\PhpParser\Node\AssignAndBinaryMap', 'Rector\Core\PhpParser\Node\AssignAndBinaryMap', false);
class_alias('Rector\PhpParser\Node\BetterNodeFinder', 'Rector\Core\PhpParser\Node\BetterNodeFinder', false);
class_alias(
    'Rector\PhpParser\Node\CustomNode\FileWithoutNamespace',
    'Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace',
    false
);
class_alias('Rector\PhpParser\Node\NodeFactory', 'Rector\Core\PhpParser\Node\NodeFactory', false);
class_alias('Rector\PhpParser\Node\Value\ValueResolver', 'Rector\Core\PhpParser\Node\Value\ValueResolver', false);
class_alias(
    'Rector\PhpParser\NodeFinder\LocalMethodCallFinder',
    'Rector\Core\PhpParser\NodeFinder\LocalMethodCallFinder',
    false
);
class_alias('Rector\PhpParser\NodeFinder\PropertyFetchFinder', 'Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder', false);
class_alias('Rector\PhpParser\NodeTransformer', 'Rector\Core\PhpParser\NodeTransformer', false);
class_alias(
    'Rector\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser',
    'Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser',
    false
);
class_alias(
    'Rector\PhpParser\NodeTraverser\RectorNodeTraverser',
    'Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser',
    false
);
class_alias('Rector\PhpParser\Parser\InlineCodeParser', 'Rector\Core\PhpParser\Parser\InlineCodeParser', false);
class_alias('Rector\PhpParser\Parser\RectorParser', 'Rector\Core\PhpParser\Parser\RectorParser', false);
class_alias('Rector\PhpParser\Parser\SimplePhpParser', 'Rector\Core\PhpParser\Parser\SimplePhpParser', false);
class_alias('Rector\PhpParser\Printer\BetterStandardPrinter', 'Rector\Core\PhpParser\Printer\BetterStandardPrinter', false);
class_alias(
    'Rector\PhpParser\Printer\FormatPerservingPrinter',
    'Rector\Core\PhpParser\Printer\FormatPerservingPrinter',
    false
);
class_alias('Rector\PhpParser\ValueObject\StmtsAndTokens', 'Rector\Core\PhpParser\ValueObject\StmtsAndTokens', false);
class_alias('Rector\ProcessAnalyzer\RectifiedAnalyzer', 'Rector\Core\ProcessAnalyzer\RectifiedAnalyzer', false);
class_alias('Rector\Provider\CurrentFileProvider', 'Rector\Core\Provider\CurrentFileProvider', false);
class_alias('Rector\Rector\AbstractRector', 'Rector\Core\Rector\AbstractRector', false);
class_alias('Rector\Rector\AbstractScopeAwareRector', 'Rector\Core\Rector\AbstractScopeAwareRector', false);
class_alias('Rector\Reflection\ClassModifierChecker', 'Rector\Core\Reflection\ClassModifierChecker', false);
class_alias('Rector\Reflection\ClassReflectionAnalyzer', 'Rector\Core\Reflection\ClassReflectionAnalyzer', false);
class_alias('Rector\Reflection\MethodReflectionResolver', 'Rector\Core\Reflection\MethodReflectionResolver', false);
class_alias('Rector\Reflection\ReflectionResolver', 'Rector\Core\Reflection\ReflectionResolver', false);
class_alias(
    'Rector\StaticReflection\DynamicSourceLocatorDecorator',
    'Rector\Core\StaticReflection\DynamicSourceLocatorDecorator',
    false
);
class_alias('Rector\Util\ArrayChecker', 'Rector\Core\Util\ArrayChecker', false);
class_alias('Rector\Util\ArrayParametersMerger', 'Rector\Core\Util\ArrayParametersMerger', false);
class_alias('Rector\Util\FileHasher', 'Rector\Core\Util\FileHasher', false);
class_alias('Rector\Util\MemoryLimiter', 'Rector\Core\Util\MemoryLimiter', false);
class_alias('Rector\Util\PhpVersionFactory', 'Rector\Core\Util\PhpVersionFactory', false);
class_alias('Rector\Util\Reflection\PrivatesAccessor', 'Rector\Core\Util\Reflection\PrivatesAccessor', false);
class_alias('Rector\Util\StringUtils', 'Rector\Core\Util\StringUtils', false);
class_alias('Rector\Validation\RectorAssert', 'Rector\Core\Validation\RectorAssert', false);
class_alias('Rector\ValueObject\Application\File', 'Rector\Core\ValueObject\Application\File', false);
class_alias('Rector\ValueObject\Bootstrap\BootstrapConfigs', 'Rector\Core\ValueObject\Bootstrap\BootstrapConfigs', false);
class_alias('Rector\ValueObject\Configuration', 'Rector\Core\ValueObject\Configuration', false);
class_alias('Rector\ValueObject\Error\SystemError', 'Rector\Core\ValueObject\Error\SystemError', false);
class_alias('Rector\ValueObject\FileProcessResult', 'Rector\Core\ValueObject\FileProcessResult', false);
class_alias('Rector\ValueObject\FuncCallAndExpr', 'Rector\Core\ValueObject\FuncCallAndExpr', false);
class_alias('Rector\ValueObject\MethodName', 'Rector\Core\ValueObject\MethodName', false);
class_alias('Rector\ValueObject\PhpVersion', 'Rector\Core\ValueObject\PhpVersion', false);
class_alias('Rector\ValueObject\PhpVersionFeature', 'Rector\Core\ValueObject\PhpVersionFeature', false);
class_alias('Rector\ValueObject\PolyfillPackage', 'Rector\Core\ValueObject\PolyfillPackage', false);
class_alias('Rector\ValueObject\ProcessResult', 'Rector\Core\ValueObject\ProcessResult', false);
class_alias('Rector\ValueObject\Reporting\FileDiff', 'Rector\Core\ValueObject\Reporting\FileDiff', false);
class_alias('Rector\ValueObject\SprintfStringAndArgs', 'Rector\Core\ValueObject\SprintfStringAndArgs', false);
class_alias('Rector\ValueObject\Visibility', 'Rector\Core\ValueObject\Visibility', false);
class_alias(
    'Rector\ValueObjectFactory\Application\FileFactory',
    'Rector\Core\ValueObjectFactory\Application\FileFactory',
    false
);
