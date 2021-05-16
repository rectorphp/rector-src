Syntax error found in 4 files

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
Parse error: rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php:18
    16|      * @noRector
    17|      */
  > 18|     private const DEFAULT_EXCLUDED_FATAL_CLASSES = ['RectorPrefix20210516\\Symfony\\Component\\Form\\FormExtensionInterface', 'RectorPrefix20210516\\Symfony\\Component\\Asset\\PackageInterface', 'RectorPrefix20210516\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'RectorPrefix20210516\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'RectorPrefix20210516\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'RectorPrefix20210516\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'RectorPrefix20210516\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'RectorPrefix20210516\\Sonata\\Twig\\Extension\\TemplateExtension'];
    19|     /**
    20|      * @var ParameterTypeResolver
Syntax error in rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php on line 18
sh ./full_build_php70.sh  389.87s user 83.19s system 113% cpu 6:55.21 total