<?php

declare(strict_types=1);

// BC layer for "Rector\\Core\\" to "Rector\\" rename

class_alias('Rector\Application\ApplicationFileProcessor', 'Rector\Core\Application\ApplicationFileProcessor');
class_alias('Rector\Application\ChangedNodeScopeRefresher', 'Rector\Core\Application\ChangedNodeScopeRefresher');
class_alias(
    'Rector\Application\Collector\CollectorNodeVisitor',
    'Rector\Core\Application\Collector\CollectorNodeVisitor'
);
class_alias('Rector\Application\FileProcessor', 'Rector\Core\Application\FileProcessor');
class_alias('Rector\Application\VersionResolver', 'Rector\Core\Application\VersionResolver');
class_alias('Rector\Autoloading\AdditionalAutoloader', 'Rector\Core\Autoloading\AdditionalAutoloader');
class_alias('Rector\Autoloading\BootstrapFilesIncluder', 'Rector\Core\Autoloading\BootstrapFilesIncluder');
class_alias('Rector\Bootstrap\ExtensionConfigResolver', 'Rector\Core\Bootstrap\ExtensionConfigResolver');
class_alias('Rector\Bootstrap\RectorConfigsResolver', 'Rector\Core\Bootstrap\RectorConfigsResolver');
class_alias('Rector\Configuration\ConfigInitializer', 'Rector\Core\Configuration\ConfigInitializer');
class_alias('Rector\Configuration\ConfigurationFactory', 'Rector\Core\Configuration\ConfigurationFactory');
class_alias('Rector\Configuration\Option', 'Rector\Core\Configuration\Option');
class_alias(
    'Rector\Configuration\Parameter\SimpleParameterProvider',
    'Rector\Core\Configuration\Parameter\SimpleParameterProvider'
);
class_alias('Rector\Console\Command\ListRulesCommand', 'Rector\Core\Console\Command\ListRulesCommand');
class_alias('Rector\Console\Command\ProcessCommand', 'Rector\Core\Console\Command\ProcessCommand');
class_alias('Rector\Console\Command\SetupCICommand', 'Rector\Core\Console\Command\SetupCICommand');
class_alias('Rector\Console\Command\WorkerCommand', 'Rector\Core\Console\Command\WorkerCommand');
class_alias('Rector\Console\ConsoleApplication', 'Rector\Core\Console\ConsoleApplication');
class_alias('Rector\Console\ExitCode', 'Rector\Core\Console\ExitCode');
class_alias(
    'Rector\Console\Formatter\ColorConsoleDiffFormatter',
    'Rector\Core\Console\Formatter\ColorConsoleDiffFormatter'
);
class_alias(
    'Rector\Console\Formatter\CompleteUnifiedDiffOutputBuilderFactory',
    'Rector\Core\Console\Formatter\CompleteUnifiedDiffOutputBuilderFactory'
);
class_alias('Rector\Console\Formatter\ConsoleDiffer', 'Rector\Core\Console\Formatter\ConsoleDiffer');
class_alias('Rector\Console\Output\OutputFormatterCollector', 'Rector\Core\Console\Output\OutputFormatterCollector');
class_alias('Rector\Console\ProcessConfigureDecorator', 'Rector\Core\Console\ProcessConfigureDecorator');
class_alias('Rector\Console\Style\RectorStyle', 'Rector\Core\Console\Style\RectorStyle');
class_alias('Rector\Console\Style\SymfonyStyleFactory', 'Rector\Core\Console\Style\SymfonyStyleFactory');
class_alias(
    'Rector\Contract\DependencyInjection\ResetableInterface',
    'Rector\Core\Contract\DependencyInjection\ResetableInterface'
);
class_alias(
    'Rector\Contract\PhpParser\Node\StmtsAwareInterface',
    'Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface'
);
class_alias('Rector\Contract\Rector\CollectorRectorInterface', 'Rector\Core\Contract\Rector\CollectorRectorInterface');
class_alias(
    'Rector\Contract\Rector\ConfigurableRectorInterface',
    'Rector\Core\Contract\Rector\ConfigurableRectorInterface'
);
class_alias('Rector\Contract\Rector\RectorInterface', 'Rector\Core\Contract\Rector\RectorInterface');
class_alias(
    'Rector\Contract\Rector\ScopeAwareRectorInterface',
    'Rector\Core\Contract\Rector\ScopeAwareRectorInterface'
);
class_alias(
    'Rector\DependencyInjection\Laravel\ContainerMemento',
    'Rector\Core\DependencyInjection\Laravel\ContainerMemento'
);
class_alias('Rector\DependencyInjection\LazyContainerFactory', 'Rector\Core\DependencyInjection\LazyContainerFactory');
class_alias(
    'Rector\DependencyInjection\RectorContainerFactory',
    'Rector\Core\DependencyInjection\RectorContainerFactory'
);
class_alias('Rector\Differ\DefaultDiffer', 'Rector\Core\Differ\DefaultDiffer');
class_alias('Rector\Enum\ObjectReference', 'Rector\Core\Enum\ObjectReference');
class_alias('Rector\Error\ExceptionCorrector', 'Rector\Core\Error\ExceptionCorrector');
class_alias('Rector\Exception\Cache\CachingException', 'Rector\Core\Exception\Cache\CachingException');
class_alias(
    'Rector\Exception\Configuration\InvalidConfigurationException',
    'Rector\Core\Exception\Configuration\InvalidConfigurationException'
);
class_alias('Rector\Exception\NotImplementedYetException', 'Rector\Core\Exception\NotImplementedYetException');
class_alias(
    'Rector\Exception\Reflection\MissingPrivatePropertyException',
    'Rector\Core\Exception\Reflection\MissingPrivatePropertyException'
);
class_alias('Rector\Exception\ShouldNotHappenException', 'Rector\Core\Exception\ShouldNotHappenException');
class_alias('Rector\Exception\VersionException', 'Rector\Core\Exception\VersionException');
class_alias('Rector\FileSystem\FileAndDirectoryFilter', 'Rector\Core\FileSystem\FileAndDirectoryFilter');
class_alias('Rector\FileSystem\FilePathHelper', 'Rector\Core\FileSystem\FilePathHelper');
class_alias('Rector\FileSystem\FilesFinder', 'Rector\Core\FileSystem\FilesFinder');
class_alias('Rector\FileSystem\FilesystemTweaker', 'Rector\Core\FileSystem\FilesystemTweaker');
class_alias('Rector\FileSystem\InitFilePathsResolver', 'Rector\Core\FileSystem\InitFilePathsResolver');
class_alias('Rector\NodeAnalyzer\ArgsAnalyzer', 'Rector\Core\NodeAnalyzer\ArgsAnalyzer');
class_alias('Rector\NodeAnalyzer\BinaryOpAnalyzer', 'Rector\Core\NodeAnalyzer\BinaryOpAnalyzer');
class_alias('Rector\NodeAnalyzer\CallAnalyzer', 'Rector\Core\NodeAnalyzer\CallAnalyzer');
class_alias('Rector\NodeAnalyzer\ClassAnalyzer', 'Rector\Core\NodeAnalyzer\ClassAnalyzer');
class_alias('Rector\NodeAnalyzer\CompactFuncCallAnalyzer', 'Rector\Core\NodeAnalyzer\CompactFuncCallAnalyzer');
class_alias('Rector\NodeAnalyzer\ConstFetchAnalyzer', 'Rector\Core\NodeAnalyzer\ConstFetchAnalyzer');
class_alias('Rector\NodeAnalyzer\DoctrineEntityAnalyzer', 'Rector\Core\NodeAnalyzer\DoctrineEntityAnalyzer');
class_alias('Rector\NodeAnalyzer\ExprAnalyzer', 'Rector\Core\NodeAnalyzer\ExprAnalyzer');
class_alias('Rector\NodeAnalyzer\MagicClassMethodAnalyzer', 'Rector\Core\NodeAnalyzer\MagicClassMethodAnalyzer');
class_alias('Rector\NodeAnalyzer\ParamAnalyzer', 'Rector\Core\NodeAnalyzer\ParamAnalyzer');
class_alias('Rector\NodeAnalyzer\PropertyAnalyzer', 'Rector\Core\NodeAnalyzer\PropertyAnalyzer');
class_alias('Rector\NodeAnalyzer\PropertyFetchAnalyzer', 'Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer');
class_alias('Rector\NodeAnalyzer\PropertyPresenceChecker', 'Rector\Core\NodeAnalyzer\PropertyPresenceChecker');
class_alias('Rector\NodeAnalyzer\ScopeAnalyzer', 'Rector\Core\NodeAnalyzer\ScopeAnalyzer');
class_alias('Rector\NodeAnalyzer\TerminatedNodeAnalyzer', 'Rector\Core\NodeAnalyzer\TerminatedNodeAnalyzer');
class_alias('Rector\NodeAnalyzer\VariableAnalyzer', 'Rector\Core\NodeAnalyzer\VariableAnalyzer');
class_alias('Rector\NodeAnalyzer\VariadicAnalyzer', 'Rector\Core\NodeAnalyzer\VariadicAnalyzer');
class_alias('Rector\NodeDecorator\CreatedByRuleDecorator', 'Rector\Core\NodeDecorator\CreatedByRuleDecorator');
class_alias('Rector\NodeDecorator\PropertyTypeDecorator', 'Rector\Core\NodeDecorator\PropertyTypeDecorator');
class_alias('Rector\NodeManipulator\AssignManipulator', 'Rector\Core\NodeManipulator\AssignManipulator');
class_alias('Rector\NodeManipulator\BinaryOpManipulator', 'Rector\Core\NodeManipulator\BinaryOpManipulator');
class_alias('Rector\NodeManipulator\ClassConstManipulator', 'Rector\Core\NodeManipulator\ClassConstManipulator');
class_alias(
    'Rector\NodeManipulator\ClassDependencyManipulator',
    'Rector\Core\NodeManipulator\ClassDependencyManipulator'
);
class_alias('Rector\NodeManipulator\ClassInsertManipulator', 'Rector\Core\NodeManipulator\ClassInsertManipulator');
class_alias('Rector\NodeManipulator\ClassManipulator', 'Rector\Core\NodeManipulator\ClassManipulator');
class_alias(
    'Rector\NodeManipulator\ClassMethodAssignManipulator',
    'Rector\Core\NodeManipulator\ClassMethodAssignManipulator'
);
class_alias('Rector\NodeManipulator\ClassMethodManipulator', 'Rector\Core\NodeManipulator\ClassMethodManipulator');
class_alias(
    'Rector\NodeManipulator\ClassMethodPropertyFetchManipulator',
    'Rector\Core\NodeManipulator\ClassMethodPropertyFetchManipulator'
);
class_alias('Rector\NodeManipulator\FuncCallManipulator', 'Rector\Core\NodeManipulator\FuncCallManipulator');
class_alias('Rector\NodeManipulator\FunctionLikeManipulator', 'Rector\Core\NodeManipulator\FunctionLikeManipulator');
class_alias('Rector\NodeManipulator\IfManipulator', 'Rector\Core\NodeManipulator\IfManipulator');
class_alias(
    'Rector\NodeManipulator\PropertyFetchAssignManipulator',
    'Rector\Core\NodeManipulator\PropertyFetchAssignManipulator'
);
class_alias('Rector\NodeManipulator\PropertyManipulator', 'Rector\Core\NodeManipulator\PropertyManipulator');
class_alias('Rector\NodeManipulator\StmtsManipulator', 'Rector\Core\NodeManipulator\StmtsManipulator');
class_alias(
    'Rector\PHPStan\NodeVisitor\ExprScopeFromStmtNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\ExprScopeFromStmtNodeVisitor'
);
class_alias(
    'Rector\PHPStan\NodeVisitor\UnreachableStatementNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\UnreachableStatementNodeVisitor'
);
class_alias(
    'Rector\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor',
    'Rector\Core\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor'
);
class_alias('Rector\Php\PhpVersionProvider', 'Rector\Core\Php\PhpVersionProvider');
class_alias(
    'Rector\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver',
    'Rector\Core\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver'
);
class_alias('Rector\Php\PolyfillPackagesProvider', 'Rector\Core\Php\PolyfillPackagesProvider');
class_alias('Rector\Php\ReservedKeywordAnalyzer', 'Rector\Core\Php\ReservedKeywordAnalyzer');
class_alias('Rector\PhpParser\AstResolver', 'Rector\Core\PhpParser\AstResolver');
class_alias('Rector\PhpParser\Comparing\NodeComparator', 'Rector\Core\PhpParser\Comparing\NodeComparator');
class_alias('Rector\PhpParser\Node\AssignAndBinaryMap', 'Rector\Core\PhpParser\Node\AssignAndBinaryMap');
class_alias('Rector\PhpParser\Node\BetterNodeFinder', 'Rector\Core\PhpParser\Node\BetterNodeFinder');
class_alias(
    'Rector\PhpParser\Node\CustomNode\FileWithoutNamespace',
    'Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace'
);
class_alias('Rector\PhpParser\Node\NodeFactory', 'Rector\Core\PhpParser\Node\NodeFactory');
class_alias('Rector\PhpParser\Node\Value\ValueResolver', 'Rector\Core\PhpParser\Node\Value\ValueResolver');
class_alias(
    'Rector\PhpParser\NodeFinder\LocalMethodCallFinder',
    'Rector\Core\PhpParser\NodeFinder\LocalMethodCallFinder'
);
class_alias('Rector\PhpParser\NodeFinder\PropertyFetchFinder', 'Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder');
class_alias('Rector\PhpParser\NodeTransformer', 'Rector\Core\PhpParser\NodeTransformer');
class_alias(
    'Rector\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser',
    'Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser'
);
class_alias(
    'Rector\PhpParser\NodeTraverser\RectorNodeTraverser',
    'Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser'
);
class_alias('Rector\PhpParser\Parser\InlineCodeParser', 'Rector\Core\PhpParser\Parser\InlineCodeParser');
class_alias('Rector\PhpParser\Parser\RectorParser', 'Rector\Core\PhpParser\Parser\RectorParser');
class_alias('Rector\PhpParser\Parser\SimplePhpParser', 'Rector\Core\PhpParser\Parser\SimplePhpParser');
class_alias('Rector\PhpParser\Printer\BetterStandardPrinter', 'Rector\Core\PhpParser\Printer\BetterStandardPrinter');
class_alias(
    'Rector\PhpParser\Printer\FormatPerservingPrinter',
    'Rector\Core\PhpParser\Printer\FormatPerservingPrinter'
);
class_alias('Rector\PhpParser\ValueObject\StmtsAndTokens', 'Rector\Core\PhpParser\ValueObject\StmtsAndTokens');
class_alias('Rector\ProcessAnalyzer\RectifiedAnalyzer', 'Rector\Core\ProcessAnalyzer\RectifiedAnalyzer');
class_alias('Rector\Provider\CurrentFileProvider', 'Rector\Core\Provider\CurrentFileProvider');
class_alias('Rector\Rector\AbstractRector', 'Rector\Core\Rector\AbstractRector');
class_alias('Rector\Rector\AbstractScopeAwareRector', 'Rector\Core\Rector\AbstractScopeAwareRector');
class_alias('Rector\Reflection\ClassModifierChecker', 'Rector\Core\Reflection\ClassModifierChecker');
class_alias('Rector\Reflection\ClassReflectionAnalyzer', 'Rector\Core\Reflection\ClassReflectionAnalyzer');
class_alias('Rector\Reflection\MethodReflectionResolver', 'Rector\Core\Reflection\MethodReflectionResolver');
class_alias('Rector\Reflection\ReflectionResolver', 'Rector\Core\Reflection\ReflectionResolver');
class_alias(
    'Rector\StaticReflection\DynamicSourceLocatorDecorator',
    'Rector\Core\StaticReflection\DynamicSourceLocatorDecorator'
);
class_alias('Rector\Util\ArrayChecker', 'Rector\Core\Util\ArrayChecker');
class_alias('Rector\Util\ArrayParametersMerger', 'Rector\Core\Util\ArrayParametersMerger');
class_alias('Rector\Util\FileHasher', 'Rector\Core\Util\FileHasher');
class_alias('Rector\Util\MemoryLimiter', 'Rector\Core\Util\MemoryLimiter');
class_alias('Rector\Util\PhpVersionFactory', 'Rector\Core\Util\PhpVersionFactory');
class_alias('Rector\Util\Reflection\PrivatesAccessor', 'Rector\Core\Util\Reflection\PrivatesAccessor');
class_alias('Rector\Util\StringUtils', 'Rector\Core\Util\StringUtils');
class_alias('Rector\Validation\RectorAssert', 'Rector\Core\Validation\RectorAssert');
class_alias('Rector\ValueObject\Application\File', 'Rector\Core\ValueObject\Application\File');
class_alias('Rector\ValueObject\Bootstrap\BootstrapConfigs', 'Rector\Core\ValueObject\Bootstrap\BootstrapConfigs');
class_alias('Rector\ValueObject\Configuration', 'Rector\Core\ValueObject\Configuration');
class_alias('Rector\ValueObject\Error\SystemError', 'Rector\Core\ValueObject\Error\SystemError');
class_alias('Rector\ValueObject\FileProcessResult', 'Rector\Core\ValueObject\FileProcessResult');
class_alias('Rector\ValueObject\FuncCallAndExpr', 'Rector\Core\ValueObject\FuncCallAndExpr');
class_alias('Rector\ValueObject\MethodName', 'Rector\Core\ValueObject\MethodName');
class_alias('Rector\ValueObject\PhpVersion', 'Rector\Core\ValueObject\PhpVersion');
class_alias('Rector\ValueObject\PhpVersionFeature', 'Rector\Core\ValueObject\PhpVersionFeature');
class_alias('Rector\ValueObject\PolyfillPackage', 'Rector\Core\ValueObject\PolyfillPackage');
class_alias('Rector\ValueObject\ProcessResult', 'Rector\Core\ValueObject\ProcessResult');
class_alias('Rector\ValueObject\Reporting\FileDiff', 'Rector\Core\ValueObject\Reporting\FileDiff');
class_alias('Rector\ValueObject\SprintfStringAndArgs', 'Rector\Core\ValueObject\SprintfStringAndArgs');
class_alias('Rector\ValueObject\Visibility', 'Rector\Core\ValueObject\Visibility');
class_alias(
    'Rector\ValueObjectFactory\Application\FileFactory',
    'Rector\Core\ValueObjectFactory\Application\FileFactory'
);
