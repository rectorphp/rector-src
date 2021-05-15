Syntax error found in 9 files

------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/packages/Testing/PHPUnit/AbstractRectorTestCase.php:29
    27| {
    28|     use MovingFilesTrait;
  > 29|     protected \RectorPrefix20210516\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider;
    30|     protected \Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector $removedAndAddedFilesCollector;
    31|     protected ?\Symplify\SmartFileSystem\SmartFileInfo $originalTempFileInfo;
Syntax error in rector-prefixed-downgraded-php70/packages/Testing/PHPUnit/AbstractRectorTestCase.php on line 29
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/nette/bootstrap/src/Bootstrap/Configurator.php:20
    18| {
    19|     use SmartObject;
  > 20|     public const COOKIE_SECRET = 'nette-debug';
    21|     /** @var callable[]  function (Configurator $sender, DI\Compiler $compiler); Occurs after the compiler is created */
    22|     public $onCompile;
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/nette/bootstrap/src/Bootstrap/Configurator.php on line 20
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/symfony/console/Event/ConsoleCommandEvent.php:25
    23|      * The return code for skipped commands, this will also be passed into the terminate event.
    24|      */
  > 25|     public const RETURN_CODE_DISABLED = 113;
    26|     /**
    27|      * Indicates if the command should be run or skipped.
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/symfony/console/Event/ConsoleCommandEvent.php on line 25
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/symfony/console/EventListener/ErrorListener.php:61
    59|         return [\RectorPrefix20210516\_HumbugBox0b2f2d5c77b8\Symfony\Component\Console\ConsoleEvents::ERROR => ['onConsoleError', -128], \RectorPrefix20210516\_HumbugBox0b2f2d5c77b8\Symfony\Component\Console\ConsoleEvents::TERMINATE => ['onConsoleTerminate', -128]];
    60|     }
  > 61|     private static function getInputString(\RectorPrefix20210516\_HumbugBox0b2f2d5c77b8\Symfony\Component\Console\Event\ConsoleEvent $event) : ?string
    62|     {
    63|         $commandName = $event->getCommand() ? $event->getCommand()->getName() : null;
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/vendor/symfony/console/EventListener/ErrorListener.php on line 61
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/src/Command/IgnoredRegexValidator.php:89
    87|         return \substr($regex, 1, $endDelimiterPosition - 1);
    88|     }
  > 89|     private function getText(\RectorPrefix20210516\Hoa\Compiler\Llk\TreeNode $treeNode) : ?string
    90|     {
    91|         if ($treeNode->getId() === 'token') {
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/src/Command/IgnoredRegexValidator.php on line 89
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/Type/PHPUnit/Assert/AssertTypeSpecifyingExtensionHelper.php:71
    69|      * @return \PhpParser\Node\Expr|null
    70|      */
  > 71|     private static function createExpression(\PHPStan\Analyser\Scope $scope, string $name, array $args) : ?\PhpParser\Node\Expr
    72|     {
    73|         $trimmedName = self::trimName($name);
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/Type/PHPUnit/Assert/AssertTypeSpecifyingExtensionHelper.php on line 71
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/PhpDoc/PHPUnit/MockObjectTypeNodeResolverExtension.php:26
    24|         return 'phpunit-v1';
    25|     }
  > 26|     public function resolve(\PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode, \PHPStan\Analyser\NameScope $nameScope) : ?\PHPStan\Type\Type
    27|     {
    28|         if (!$typeNode instanceof \PHPStan\PhpDocParser\Ast\Type\UnionTypeNode) {
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/PhpDoc/PHPUnit/MockObjectTypeNodeResolverExtension.php on line 26
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/Rules/PHPUnit/ShouldCallParentMethodsRule.php:55
    53|      * @return bool
    54|      */
  > 55|     private function hasParentClassCall(?array $stmts, string $methodName) : bool
    56|     {
    57|         if ($stmts === null) {
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-phpunit/src/Rules/PHPUnit/ShouldCallParentMethodsRule.php on line 55
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php:18
    16|      * @noRector
    17|      */
  > 18|     private const DEFAULT_EXCLUDED_FATAL_CLASSES = ['RectorPrefix20210516\\Symfony\\Component\\Form\\FormExtensionInterface', 'RectorPrefix20210516\\Symfony\\Component\\Asset\\PackageInterface', 'RectorPrefix20210516\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'RectorPrefix20210516\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'RectorPrefix20210516\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'RectorPrefix20210516\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'RectorPrefix20210516\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'RectorPrefix20210516\\Sonata\\Twig\\Extension\\TemplateExtension'];
    19|     /**
    20|      * @var ParameterTypeResolver
Syntax error in rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php on line 18