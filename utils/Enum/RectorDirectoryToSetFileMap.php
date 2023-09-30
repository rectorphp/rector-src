<?php

declare(strict_types=1);

namespace Rector\Utils\Enum;

final class RectorDirectoryToSetFileMap
{
    /**
     * @return array<string, string>
     */
    public static function provide(): array
    {
        return [
            __DIR__ . '/../../rules/CodeQuality/Rector' => __DIR__ . '/../../config/set/code-quality.php',
            __DIR__ . '/../../rules/CodingStyle/Rector' => __DIR__ . '/../../config/set/coding-style.php',
            __DIR__ . '/../../rules/DeadCode/Rector' => __DIR__ . '/../../config/set/dead-code.php',
            __DIR__ . '/../../rules/EarlyReturn/Rector' => __DIR__ . '/../../config/set/early-return.php',
            __DIR__ . '/../../rules/Naming/Rector' => __DIR__ . '/../../config/set/naming.php',
            __DIR__ . '/../../rules/TypeDeclaration/Rector' => __DIR__ . '/../../config/set/type-declaration.php',
            __DIR__ . '/../../rules/Privatization/Rector' => __DIR__ . '/../../config/set/privatization.php',
            __DIR__ . '/../../rules/Php52/Rector' => __DIR__ . '/../../config/set/php52.php',
            __DIR__ . '/../../rules/Php53/Rector' => __DIR__ . '/../../config/set/php53.php',
            __DIR__ . '/../../rules/Php54/Rector' => __DIR__ . '/../../config/set/php54.php',
            __DIR__ . '/../../rules/Php55/Rector' => __DIR__ . '/../../config/set/php55.php',
            __DIR__ . '/../../rules/Php56/Rector' => __DIR__ . '/../../config/set/php56.php',
            __DIR__ . '/../../rules/Php70/Rector' => __DIR__ . '/../../config/set/php70.php',
            __DIR__ . '/../../rules/Php71/Rector' => __DIR__ . '/../../config/set/php71.php',
            __DIR__ . '/../../rules/Php72/Rector' => __DIR__ . '/../../config/set/php72.php',
            __DIR__ . '/../../rules/Php73/Rector' => __DIR__ . '/../../config/set/php73.php',
            __DIR__ . '/../../rules/Php74/Rector' => __DIR__ . '/../../config/set/php74.php',
            __DIR__ . '/../../rules/Php80/Rector' => __DIR__ . '/../../config/set/php80.php',
            __DIR__ . '/../../rules/Php81/Rector' => __DIR__ . '/../../config/set/php81.php',
            __DIR__ . '/../../rules/Php82/Rector' => __DIR__ . '/../../config/set/php82.php',
            __DIR__ . '/../../rules/Strict/Rector' => __DIR__ . '/../../config/set/strict-booleans.php',
            __DIR__ . '/../../rules/Instanceof_/Rector' => __DIR__ . '/../../config/set/instanceof.php',

            // doctrine
            __DIR__ . '/../../vendor/rector/rector-doctrine/rules/CodeQuality' => __DIR__ . '/../../vendor/rector/rector-doctrine/config/sets/doctrine-code-quality.php',

            // phpunit
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit50' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit50.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit60' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit60.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit70' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit70.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit80' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit80.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit90' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit90.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/PHPUnit100' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit100.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/AnnotationsToAttributes' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/annotations-to-attributes.php',
            __DIR__ . '/../../vendor/rector/rector-phpunit/rules/CodeQuality' => __DIR__ . '/../../vendor/rector/rector-phpunit/config/sets/phpunit-code-quality.php',

            // rector-downgrade
            __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp72' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php72.php',
            __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp73' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php73.php',
            __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp74' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php74.php',
            __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp80' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php80.php',
            __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp81' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php81.php',

            // symfony
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony25' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony25.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony26' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony26.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony27' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony27.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony28' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony28.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony30' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony30.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony33' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony33.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony34' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony34.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony40' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony40.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony42' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony42.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony43' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony43.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony44' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony44.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony51' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony51.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony52' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony52.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony53' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony53.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony60' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony60.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony61' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony61.php',
            __DIR__ . '/../../vendor/rector/rector-symfony/rules/Symfony62' => __DIR__ . '/../../vendor/rector/rector-symfony/config/sets/symfony/symfony62.php',
        ];
    }
}
