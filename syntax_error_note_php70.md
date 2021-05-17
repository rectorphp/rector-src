Syntax error found in 2 files

------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/src/Command/IgnoredRegexValidator.php:89
    87|         return \substr($regex, 1, $endDelimiterPosition - 1);
    88|     }
  > 89|     private function getText(\RectorPrefix20210517\Hoa\Compiler\Llk\TreeNode $treeNode) : ?string
    90|     {
    91|         if ($treeNode->getId() === 'token') {
Syntax error in rector-prefixed-downgraded-php70/vendor/phpstan/phpstan-extracted/src/Command/IgnoredRegexValidator.php on line 89
------------------------------------------------------------
Parse error: rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php:18
    16|      * @noRector
    17|      */
  > 18|     private const DEFAULT_EXCLUDED_FATAL_CLASSES = ['RectorPrefix20210517\\Symfony\\Component\\Form\\FormExtensionInterface', 'RectorPrefix20210517\\Symfony\\Component\\Asset\\PackageInterface', 'RectorPrefix20210517\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'RectorPrefix20210517\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'RectorPrefix20210517\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'RectorPrefix20210517\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'RectorPrefix20210517\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'RectorPrefix20210517\\Sonata\\Twig\\Extension\\TemplateExtension'];
    19|     /**
    20|      * @var ParameterTypeResolver
Syntax error in rector-prefixed-downgraded-php70/vendor/symplify/autowire-array-parameter/src/Skipper/ParameterSkipper.php on line 18