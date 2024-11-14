PHPUnit 10.5.38 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12
Configuration: /Users/samsonasik/www/rector-src/phpunit.xml

.............................................................   61 / 4067 (  1%)
.....................................EE..................F...  122 / 4067 (  2%)
........................FFFFFFFFFFFFFFFF.FFFFFFFFFFFFFFFFFFFF  183 / 4067 (  4%)
FFFF.FFFFFFFFFFFFFF...........FFFFFF..FFFFFFFFF..........F...  244 / 4067 (  5%)
.......................................F.FFEF................  305 / 4067 (  7%)
...........................................................F.  366 / 4067 (  8%)
.FF.F.....F.....F....F...F...................................  427 / 4067 ( 10%)
.............................................................  488 / 4067 ( 11%)
.............................................................  549 / 4067 ( 13%)
.............F...............................................  610 / 4067 ( 14%)
....FFFF.....................................................  671 / 4067 ( 16%)
.............................................................  732 / 4067 ( 17%)
.............................................................  793 / 4067 ( 19%)
..........................................F.......F..........  854 / 4067 ( 20%)
.............................................................  915 / 4067 ( 22%)
...............................F..F..........................  976 / 4067 ( 23%)
............................................................. 1037 / 4067 ( 25%)
....................F..............................F.FF...... 1098 / 4067 ( 26%)
.............................F............................... 1159 / 4067 ( 28%)
............................................................. 1220 / 4067 ( 29%)
............................................................. 1281 / 4067 ( 31%)
............................................................. 1342 / 4067 ( 32%)
............................................................. 1403 / 4067 ( 34%)
....................................................F........ 1464 / 4067 ( 35%)
.....F........F.............................................. 1525 / 4067 ( 37%)
............................................................. 1586 / 4067 ( 38%)
............................................................. 1647 / 4067 ( 40%)
............................................................. 1708 / 4067 ( 41%)
............................................................. 1769 / 4067 ( 43%)
............................................................. 1830 / 4067 ( 44%)
............................................................. 1891 / 4067 ( 46%)
............................................................. 1952 / 4067 ( 47%)
...............................F....FFF...................... 2013 / 4067 ( 49%)
............................................................. 2074 / 4067 ( 50%)
............................................................. 2135 / 4067 ( 52%)
............................................................. 2196 / 4067 ( 53%)
............................................................. 2257 / 4067 ( 55%)
........................................F.................... 2318 / 4067 ( 56%)
............................................................. 2379 / 4067 ( 58%)
............................................................. 2440 / 4067 ( 59%)
...........................................EE................ 2501 / 4067 ( 61%)
......................................................F..E... 2562 / 4067 ( 62%)
FFFF...FF.F..............F...F...................F........... 2623 / 4067 ( 64%)
..........................FFF.FFFFF.FFFFFFFFFFFFFFFF.F..FFFFF 2684 / 4067 ( 65%)
FFFFF........................................................ 2745 / 4067 ( 67%)
............................................................. 2806 / 4067 ( 68%)
....................................FFFFFFFFFF............... 2867 / 4067 ( 70%)
............................................................. 2928 / 4067 ( 71%)
............................................................. 2989 / 4067 ( 73%)
....................................F........................ 3050 / 4067 ( 74%)
............................................................. 3111 / 4067 ( 76%)
............................................................. 3172 / 4067 ( 77%)
....................................................F........ 3233 / 4067 ( 79%)
......F......F............................................... 3294 / 4067 ( 80%)
............................................................. 3355 / 4067 ( 82%)
............................................................. 3416 / 4067 ( 83%)
............................................................. 3477 / 4067 ( 85%)
............................................................. 3538 / 4067 ( 86%)
....................................................F........ 3599 / 4067 ( 88%)
............................................................. 3660 / 4067 ( 89%)
...................F...F..................................... 3721 / 4067 ( 91%)
............................................................. 3782 / 4067 ( 92%)
............................................................. 3843 / 4067 ( 94%)
............................................................. 3904 / 4067 ( 95%)
............................................................. 3965 / 4067 ( 97%)
..........F...F.............................................. 4026 / 4067 ( 98%)
.........................................                     4067 / 4067 (100%)

Time: 00:16.128, Memory: 421.00 MB

There were 6 errors:

1) Rector\Tests\PhpAttribute\Printer\PhpAttributeGroupFactoryTest::testCreateFromClassWithItems
LogicException: Invalid value

/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/BuilderHelpers.php:276
/Users/samsonasik/www/rector-src/src/PhpAttribute/NodeFactory/NamedArgsFactory.php:36
/Users/samsonasik/www/rector-src/src/PhpAttribute/NodeFactory/PhpAttributeGroupFactory.php:122
/Users/samsonasik/www/rector-src/src/PhpAttribute/NodeFactory/PhpAttributeGroupFactory.php:62
/Users/samsonasik/www/rector-src/tests/PhpAttribute/Printer/PhpAttributeGroupFactoryTest.php:25

2) Rector\Tests\PhpAttribute\Printer\PhpAttributeGroupFactoryTest::testCreateArgsFromItems
TypeError: Rector\PhpAttribute\AnnotationToAttributeMapper\ArrayItemNodeAnnotationToAttributeMapper::map(): Return value must be of type PhpParser\Node\Expr, PhpParser\Node\ArrayItem returned

/Users/samsonasik/www/rector-src/src/PhpAttribute/AnnotationToAttributeMapper/ArrayItemNodeAnnotationToAttributeMapper.php:72
/Users/samsonasik/www/rector-src/src/PhpAttribute/AnnotationToAttributeMapper.php:39
/Users/samsonasik/www/rector-src/src/PhpAttribute/AnnotationToAttributeMapper/ArrayAnnotationToAttributeMapper.php:49
/Users/samsonasik/www/rector-src/src/PhpAttribute/AnnotationToAttributeMapper.php:39
/Users/samsonasik/www/rector-src/src/PhpAttribute/NodeFactory/PhpAttributeGroupFactory.php:115
/Users/samsonasik/www/rector-src/tests/PhpAttribute/Printer/PhpAttributeGroupFactoryTest.php:38

3) Rector\Tests\Issues\FqcnAnnotationToAttribute\FqcnAnnotationToAttributeTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
PHPStan\PhpDocParser\Parser\ParserException: Unexpected token "'user'", expected TOKEN_DOUBLE_QUOTED_STRING at offset 71 on line 3

/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/ConstExprParser.php:79
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:684
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:575
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:553
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:522
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:148
/Users/samsonasik/www/rector-src/vendor/phpstan/phpdoc-parser/src/Parser/PhpDocParser.php:75
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/PhpDoc/PhpDocStringResolver.php:22
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:230
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:574
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:585
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:580
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:585
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:184
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:152
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:137
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Type/FileTypeMapper.php:90
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:4191
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:469
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:422
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:721
phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:390
/Users/samsonasik/www/rector-src/src/NodeTypeResolver/PHPStan/Scope/PHPStanNodeScopeResolver.php:330
/Users/samsonasik/www/rector-src/src/NodeTypeResolver/PHPStan/Scope/PHPStanNodeScopeResolver.php:292
/Users/samsonasik/www/rector-src/src/NodeTypeResolver/NodeScopeAndMetadataDecorator.php:35
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:198
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:114
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:56
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:168
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:134
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:261
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:226
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/FqcnAnnotationToAttribute/FqcnAnnotationToAttributeTest.php:16

4) Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\CreateFunctionToAnonymousFunctionRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
TypeError: Rector\PhpParser\Node\Value\ValueResolver::getValue(): Argument #1 ($expr) must be of type PhpParser\Node\Arg|PhpParser\Node\Expr, PhpParser\Node\InterpolatedStringPart given, called in /Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php on line 134

/Users/samsonasik/www/rector-src/src/PhpParser/Node/Value/ValueResolver.php:57
/Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php:134
/Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php:107
/Users/samsonasik/www/rector-src/rules/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector.php:162
/Users/samsonasik/www/rector-src/rules/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector.php:111
/Users/samsonasik/www/rector-src/src/Rector/AbstractRector.php:138
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:108
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:133
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:76
/Users/samsonasik/www/rector-src/src/PhpParser/NodeTraverser/RectorNodeTraverser.php:41
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:71
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:168
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:134
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:261
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:226
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector/CreateFunctionToAnonymousFunctionRectorTest.php:16

5) Rector\Tests\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector\CreateFunctionToAnonymousFunctionRectorTest::test with data set #12 ('/Users/samsonasik/www/rector-...hp.inc')
TypeError: Rector\PhpParser\Node\Value\ValueResolver::getValue(): Argument #1 ($expr) must be of type PhpParser\Node\Arg|PhpParser\Node\Expr, PhpParser\Node\InterpolatedStringPart given, called in /Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php on line 134

/Users/samsonasik/www/rector-src/src/PhpParser/Node/Value/ValueResolver.php:57
/Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php:134
/Users/samsonasik/www/rector-src/src/PhpParser/Parser/InlineCodeParser.php:107
/Users/samsonasik/www/rector-src/rules/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector.php:162
/Users/samsonasik/www/rector-src/rules/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector.php:111
/Users/samsonasik/www/rector-src/src/Rector/AbstractRector.php:138
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:108
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:133
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:98
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:217
/Users/samsonasik/www/rector-src/vendor/nikic/php-parser/lib/PhpParser/NodeTraverser.php:76
/Users/samsonasik/www/rector-src/src/PhpParser/NodeTraverser/RectorNodeTraverser.php:41
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:71
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:168
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:134
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:261
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:226
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php72/Rector/FuncCall/CreateFunctionToAnonymousFunctionRector/CreateFunctionToAnonymousFunctionRectorTest.php:16

6) Rector\Tests\Php74\Rector\ArrayDimFetch\CurlyToSquareBracketArrayStringRector\CurlyToSquareBracketArrayStringRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
PHPStan\Parser\ParserErrorsException: Syntax error, unexpected '{', expecting ';', Syntax error, unexpected '{', expecting ';', Syntax error, unexpected '{', expecting ';'

phar:///Users/samsonasik/www/rector-src/vendor/phpstan/phpstan/phpstan.phar/src/Parser/RichParser.php:70
/Users/samsonasik/www/rector-src/src/PhpParser/Parser/RectorParser.php:40
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:193
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:114
/Users/samsonasik/www/rector-src/src/Application/FileProcessor.php:56
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:168
/Users/samsonasik/www/rector-src/src/Application/ApplicationFileProcessor.php:134
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:261
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:226
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/ArrayDimFetch/CurlyToSquareBracketArrayStringRector/CurlyToSquareBracketArrayStringRectorTest.php:16

--

There were 170 failures:

1) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TestModifyReprintTest::test
Failed asserting that null is an instance of class Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TestModifyReprintTest.php:53

2) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #37 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/64d1e0a7b9088b601ae9423d8d8f4efe.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

3) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/df4260c470d27607c1d18745512af2a2.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

4) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #5 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/e43854fd8dba8e05df9588436889ca44.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

5) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/fe6d7382e5bf5ed5769d76e7c389830b.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

6) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #11 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/e1aa2b4af0e29fb06bc5ae9ac80d81ec.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

7) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #12 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/95e47da11341ee993a0737367dc441c0.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

8) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #13 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/8300b9340b18d715dd22b6552df46d7a.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

9) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #22 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/9c6a50b8d3a81fd606a21bffac83e85c.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

10) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #24 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/3677d67f866296d6d1f2f19fea081b2a.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

11) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #28 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/b4b375269622f15b8ceae6d5830d7053.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

12) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #29 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/6680055c4aba9dabca9dfa7aa5a32a8c.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

13) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #31 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/a4c6ac7f6292a607b9a62213302dbfc4.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

14) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #34 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/20384537ae30cbf06ee29b456f2454c0.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

15) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #35 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/36133c2ec413673425784f5ff83431bc.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

16) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #38 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/ba39a509951eafe67fb3047873d6ffdb.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

17) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #45 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/900ef72a6d9bbff424d5e0f0ea5ca55c.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

18) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #51 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/b3a4b9901fe767b406cf2c7b6bdf576f.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

19) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/634354020994c367bbad952943640c8f.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

20) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/7d171c12005a492d48e05141c281443a.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

21) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/bb47225b24c3f0c592090f12cf768ca1.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

22) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/1c5940f3b61419bba075ea6cc856b57f.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

23) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #10 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/9f36c6495f317833775f22184e58b81c.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

24) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #14 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/f334df940b0945567c075e6541218861.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

25) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #15 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/6b719c2f7a46c705e9a97d655a5af0e5.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

26) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #16 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/d19690493f23f74d3d758727177aa6fc.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

27) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #17 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/18dcc14735606a29e592387a210a4af5.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

28) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #18 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/7f006055651fbfeff2386a3915e00d79.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

29) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #20 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/06f31edab40b5d6e6e1a083b59e6206b.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

30) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #21 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/4bfd81265f75840c196ee00eccd936cc.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

31) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #25 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/7e0b63e132fefe139eb450a5ac061f4f.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

32) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #26 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/3df4a2897fcdaafd1630d8813fe42d2d.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

33) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #27 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/e3090c8a5f5895693a1b5081b139a134.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

34) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #30 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/2685af69fc2a5a2b043e605bad6865e0.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

35) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #33 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/a2c17cf242e4c24164f9d09be2f3a5cf.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

36) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #40 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/7a791eff0a22afcf3d2c8c43b9960545.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

37) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #41 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/ece5aca5aa0d72440adf5881203a1b5e.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

38) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #42 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/597fe37440c422143b92216b688c78d3.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

39) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #43 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/77da0b1c9193a6b4ea9ecc1a57b94892.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

40) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #44 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/11bd9cf700392b7402f2be1fba2650a1.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

41) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #46 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/77801d4740019ed27946f4edb1b919cd.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

42) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/657b00cbd5de5707a40c8777547d1495.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

43) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/47bb2b00cd87eec554ccf39e8a8127ba.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

44) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #6 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/522386c3715d66f69d08297541340512.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

45) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #23 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/3eeef65840263638377fb82cb6d56ccb.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

46) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #39 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/0b643e829cc3e9462d7245d32d576755.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

47) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #50 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/f717c222ad6a0e20c4d5444ac2196e8d.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

48) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #49 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/d57cb3b5076513875be14ed0aa9e1a57.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

49) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #36 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/f89d194837f96c2d36d2337a8fc5bb80.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

50) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #19 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/59a9f14bd273e45f11f4f629bfee22a1.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

51) Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest::test with data set #32 ('/Users/samsonasik/www/rector-...hp.inc')
../../../../var/folders/dv/m3gvpzh94t59l94qz3w2dgfr0000gn/T/rector/tests_fixture_/a650109f49a5000798be6adad8cf1889.php
Failed asserting that false is true.

/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:138
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:93
/Users/samsonasik/www/rector-src/tests/BetterPhpDocParser/PhpDocParser/TagValueNodeReprint/TagValueNodeReprintTest.php:57

52) Rector\Tests\Comments\CommentRemover\CommentRemoverTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
 {
     public function run($value)
     {
-        // else
         switch ($value) {
             case 'key':
-                // something
                 return 'https://some_very_long_link.cz';
         }
     }
 };'

/Users/samsonasik/www/rector-src/tests/Comments/CommentRemover/CommentRemoverTest.php:49

53) Rector\Tests\Comments\CommentRemover\CommentRemoverTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'/* some comment */
-    $value = 1;'
+'$value = 1;'

/Users/samsonasik/www/rector-src/tests/Comments/CommentRemover/CommentRemoverTest.php:49

54) Rector\Tests\Comments\CommentRemover\CommentRemoverTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'# some comment
-    $value = 1;'
+'$value = 1;'

/Users/samsonasik/www/rector-src/tests/Comments/CommentRemover/CommentRemoverTest.php:49

55) Rector\Tests\Comments\CommentRemover\CommentRemoverTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'$value = 1;
-    // some comment'
+'$value = 1;'

/Users/samsonasik/www/rector-src/tests/Comments/CommentRemover/CommentRemoverTest.php:49

56) Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\AnnotationToAttributeRenameAutoImportTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;
 
-use Symfony\Component\Security\Http\Attribute\IsGranted;
+use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
-#[IsGranted('TEST')]
+/**
+ * @IsGranted("TEST")
+ */
 class IsGrantedController extends AbstractController
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AnnotationToAttributeRenameAutoImport/AnnotationToAttributeRenameAutoImportTest.php:16

57) Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\AnnotationToAttributeRenameAutoImportTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "not_imported_yet.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;
 
-use Symfony\Component\Security\Http\Attribute\IsGranted;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
-#[IsGranted('TEST')]
+/**
+ * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("TEST")
+ */
 class NotImportedYet extends AbstractController
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AnnotationToAttributeRenameAutoImport/AnnotationToAttributeRenameAutoImportTest.php:16

58) Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\AnnotationToAttributeRenameAutoImportTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "not_imported_yet2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;
 
-use Symfony\Component\Security\Http\Attribute\IsGranted;
+use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
-#[IsGranted('TEST')]
+/**
+ * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("TEST")
+ */
 class NotImportedYet2 extends AbstractController
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AnnotationToAttributeRenameAutoImport/AnnotationToAttributeRenameAutoImportTest.php:16

59) Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\AnnotationToAttributeRenameAutoImportTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "with_existing_attribute.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\RenameAnnotationToAttributeAutoImport\Fixture;
 
-use Symfony\Component\Security\Http\Attribute\IsGranted;
+use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\Routing\Annotation\Route;
 
+/**
+ * @IsGranted("TEST")
+ */
 #[Route(path: '/pro/{id}/networks/{networkId}/sectors', name: 'api_network_sectors', requirements: ['id' => '\d+', 'networkId' => '\d+'])]
-#[IsGranted('TEST')]
 class WithExistingAttribute extends AbstractController
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AnnotationToAttributeRenameAutoImport/AnnotationToAttributeRenameAutoImportTest.php:16

60) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "annotation_to_attribute.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Doctrine\ORM\Mapping\Entity;
-
-#[Entity]
+/**
+ * @\Doctrine\ORM\Mapping\Entity()
+ */
 class SomeEntity
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

61) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_deep_annotation.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-use Doctrine\ORM\Mapping\ManyToMany;
-use Doctrine\ORM\Mapping\JoinTable;
-use Doctrine\ORM\Mapping\JoinColumn;
-use Doctrine\ORM\Mapping\OrderBy;
-
 class DoctrineDeepAnnotation
 {
     /**
-     * @ManyToMany(targetEntity="Comment", cascade={"persist", "remove"})
-     * @JoinTable(name="absence_comment", joinColumns={@JoinColumn(name="request_id", referencedColumnName="id")}, inverseJoinColumns={@JoinColumn(name="comment_id", referencedColumnName="id")})
-     * @OrderBy({"createdAt"="DESC"})
+     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Comment", cascade={"persist","remove"})
+     * @Doctrine\ORM\Mapping\JoinTable(name="absence_comment", joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="request_id", referencedColumnName="id")}, inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="comment_id", referencedColumnName="id")})
+     * @Doctrine\ORM\Mapping\OrderBy({"createdAt" = "DESC"})
      */
     public $property;
 }
+
+?>

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

62) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutes
 {
     /**
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

63) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #5 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_after_generic.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesAfterGeneric
 {
     /**
-     * @OA\Property(type="array", @OA\Items(ref=@Model(type=TestItem::class)))
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @OA\Property(
+     *     type="array",
+     *     @OA\Items(ref=@Model(type=TestItem::class))
+     * )
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

64) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #6 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_after_generic_with_string_key_numeric.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesAfterGenericWithStringKeyNumeric
 {
     /**
-     * @OA\Property(type="array", '1'=@OA\Items(ref=@Model(type=TestItem::class)))
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @OA\Property(
+     *     type="array",
+     *     '1'=@OA\Items(ref=@Model(type=TestItem::class))
+     * )
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

65) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_before_generic.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesBeforeGeneric
 {
     /**
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      * @OA\Property(
      *     type="array",
      *     @OA\Items(ref=@Model(type=TestItem::class))

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

66) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_with_comment_before.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesWithCommentBefore
 {
     /**
      * Testsssssssssss
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     *
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

67) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_with_next_doc.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesWithNextDoc
 {
     /**
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      * @return Response
      */
     public function some(): Response

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

68) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #10 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_with_next_doc_other_routes.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesWithNextDoc2
 {
     /**
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      * @return Response
-     * @Route("/third", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/third", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

69) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #11 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_with_prev_doc.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesWithPrevDoc
 {
     /**
      * @return Response
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

70) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #12 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "two_routes_with_prev_doc_with_description_multiline.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;
 
-use Symfony\Component\Routing\Annotation\Route;
-
 class TwoRoutesWithPrevDocWithDescriptionMultiline
 {
     /**
@@ @@
      * @return Response line 1
      *     line 2
      *      line 3
-     * @Route("/first", methods={"GET"})
-     * @Route("/second", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
+     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
      */
     public function some(): Response
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

71) Rector\Tests\Issues\AutoImport\AutoImportTest::test with data set #23 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_no_namespace.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-use Doctrine\ORM\Mapping\Table;
-
 /**
- * @Table("Table_Name")
+ * @Doctrine\ORM\Mapping\Table("Table_Name")
  */
 class NoNamespace
 {
 }
+
+?>

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/AutoImport/AutoImportTest.php:16

72) Rector\Tests\Issues\EmptyLineNestedAnnotationToAttribute\EmptyLineNestedAnnotationToAttributeTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
-#[ORM\Table(name: 'segments')]
-#[ORM\Index(name: 'sequence', columns: ['sequence'])]
-#[ORM\Index(name: 'status_channel_started_at', columns: ['status', 'channel', 'started_at'])]
-#[ORM\Index(name: 'representation_started_at', columns: ['representation', 'started_at'])]
-#[ORM\Index(name: 'channel_started_at_status_representation_sequence', columns: ['channel', 'started_at', 'status', 'representation', 'sequence'])]
-#[ORM\UniqueConstraint(name: 'unique_channel_representation_started_at', columns: ['channel', 'representation', 'started_at'])]
-#[ORM\Entity(repositoryClass: \App\Repository\SegmentRepository::class)]
+/**
+ * @ORM\Entity(repositoryClass="App\Repository\SegmentRepository")
+ * @ORM\Table(
+ *     name="segments",
+ *
+ *     indexes={
+ *     @ORM\Index(name="sequence", columns={"sequence"}),
+ *     @ORM\Index(name="status_channel_started_at", columns={"status", "channel", "started_at"}),
+ *     @ORM\Index(name="representation_started_at", columns={"representation", "started_at"}),
+ *     @ORM\Index(name="channel_started_at_status_representation_sequence", columns={"channel", "started_at", "status", "representation", "sequence"})
+ * },
+ *     uniqueConstraints={
+ *     @ORM\UniqueConstraint(name="unique_channel_representation_started_at", columns={"channel", "representation", "started_at"})
+ * }
+ *     )
+ */
 class Segment
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/EmptyLineNestedAnnotationToAttribute/EmptyLineNestedAnnotationToAttributeTest.php:16

73) Rector\Tests\Issues\FqcnAnnotationToAttribute\FqcnAnnotationToAttributeTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "aliased_and_sub_namespace.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 use Symfony\Component\Validator\Constraints as SymfonyConstraints;
 use Symfony\Component\Serializer\Annotation\Groups;
 
-#[ORM\Entity]
-#[Constraints\UniqueEntity('azureB2cUuid')]
-#[Constraints\UniqueEntity('uuid')]
-#[Constraints\UniqueEntity('email')]
-#[ORM\Table('user')]
-#[ORM\Index(name: 'name_index', columns: ['name'])]
-#[ORM\Index(name: 'surname_index', columns: ['surname'])]
+/**
+ * @ORM\Entity()
+ * @ORM\Table("user", indexes={
+ *  @ORM\Index(name="name_index", columns={"name"}),
+ *  @ORM\Index(name="surname_index", columns={"surname"}),
+ * })
+ * @Constraints\UniqueEntity("azureB2cUuid")
+ * @Constraints\UniqueEntity("uuid")
+ * @Constraints\UniqueEntity("email")
+ */
 class AliasedAndSubNamespace
 {
-    #[SymfonyConstraints\NotBlank]
-    #[SymfonyConstraints\Email(mode: 'strict')]
-    #[ORM\Column(type: 'string', unique: true)]
-    #[Groups(['import', 'export-user', 'export-claim'])]
+    /**
+     * @SymfonyConstraints\NotBlank()
+     * @SymfonyConstraints\Email(mode="strict")
+     * @ORM\Column(type="string", unique=true)
+     * @Groups({"import", "export-user", "export-claim"})
+     */
     protected string $email = "";
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/FqcnAnnotationToAttribute/FqcnAnnotationToAttributeTest.php:16

74) Rector\Tests\Issues\FqcnAnnotationToAttribute\FqcnAnnotationToAttributeTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Issues\FqcnAnnotationToAttribute\Fixture;
 
-#[\Doctrine\ORM\Mapping\Entity]
-#[\Doctrine\ORM\Mapping\Index(name: 'name_index', columns: ['name'])]
-#[\Doctrine\ORM\Mapping\Index(name: 'surname_index', columns: ['surname'])]
-#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('azureB2cUuid')]
-#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('uuid')]
-#[\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity('email')]
-#[\Doctrine\ORM\Mapping\Table('user')]
+/**
+ * @\Doctrine\ORM\Mapping\Entity()
+ * @\Doctrine\ORM\Mapping\Table("user", indexes={
+ *  @\Doctrine\ORM\Mapping\Index(name="name_index", columns={"name"}),
+ *  @\Doctrine\ORM\Mapping\Index(name="surname_index", columns={"surname"}),
+ * })
+ * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("azureB2cUuid")
+ * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("uuid")
+ * @\Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity("email")
+ */
 class Entity
 {
-    #[\Symfony\Component\Validator\Constraints\NotBlank]
-    #[\Symfony\Component\Validator\Constraints\Email(mode: 'strict')]
-    #[\Doctrine\ORM\Mapping\Column(type: 'string', unique: true)]
-    #[\Symfony\Component\Serializer\Annotation\Groups(['import', 'export-user', 'export-claim'])]
+    /**
+     * @\Symfony\Component\Validator\Constraints\NotBlank()
+     * @\Symfony\Component\Validator\Constraints\Email(mode="strict")
+     * @\Doctrine\ORM\Mapping\Column(type="string", unique=true)
+     * @\Symfony\Component\Serializer\Annotation\Groups({"import", "export-user", "export-claim"})
+     */
     protected string $email = "";
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/FqcnAnnotationToAttribute/FqcnAnnotationToAttributeTest.php:16

75) Rector\Tests\Issues\FqcnAnnotationToAttribute\FqcnAnnotationToAttributeTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "sub_namespace_from_use.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping;
 
-#[Mapping\Entity]
+/**
+ * @Mapping\Entity()
+ */
 class SubNamespaceFromUse
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/FqcnAnnotationToAttribute/FqcnAnnotationToAttributeTest.php:16

76) Rector\Tests\Issues\NoNamespaced\NoNamespacedTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "skip_used_in_annotation.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-use App\Repository\DemoRepository;
-use Doctrine\DBAL\Types\Types;
-use Doctrine\ORM\Mapping as ORM;
-
 /**
  * @ORM\Entity(repositoryClass=DemoRepository::class)
  */

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/NoNamespaced/NoNamespacedTest.php:16

77) Rector\Tests\Issues\PartialValueDocblockUpdate\PartialValueDocblockUpdateTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "add_default_value_to_route.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     /**
      * @Since("6.4.0.1")
-     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"}, defaults={})
+     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"})
      *
      * @return SomeJsonResponse
      */

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/PartialValueDocblockUpdate/PartialValueDocblockUpdateTest.php:16

78) Rector\Tests\Issues\PartialValueDocblockUpdate\PartialValueDocblockUpdateTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "add_default_value_to_route2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 class AddDefaultValueToRoute2 {
     /**
      * @required
-     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"}, defaults={})
+     * @Route("/api/_admin/reset-excluded-search-term", name="api.admin.reset-excluded-search-term", methods={"POST"})
      *
      * @return JsonResponse
      */

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/PartialValueDocblockUpdate/PartialValueDocblockUpdateTest.php:16

79) Rector\Tests\Issues\PlainValueParser\PlainValueParserTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SomeFixture
 {
-    #[CustomAnnotation(description: 'List of value :
-  - < b > TRY < /b>: To try
-  - < b > TEST < /b>: to test ( Default if no parameters given )')]
+    /**
+     * @CustomAnnotation(description="List of value:
+     *  - <b>TRY</b>: To try
+     *  - <b>TEST</b>: to test (Default if no parameters given)")
+     */
     public function test()
     {}
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/PlainValueParser/PlainValueParserTest.php:16

80) Rector\Tests\Issues\RenameClassInCallbackFromAssertAnnotation\RenameClassInCallbackFromAssertAnnotationTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "callback_with_curly_braces.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 final class CallbackWithCuryBraces
 {
     /**
-     * @Assert\Choice(callback={"Some\Other\Random\Class_", "getChoices"})
+     * @Assert\Choice(callback={"Some\Random\Class_", "getChoices"})
      */
     private $attribute;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/RenameClassInCallbackFromAssertAnnotation/RenameClassInCallbackFromAssertAnnotationTest.php:16

81) Rector\Tests\Issues\ScopeNotAvailable\ArrayAnnotationToAttributeTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "array_in_attribute.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 class ArrayInAttribtue
 {
     public function __construct(
-        #[ORM\ManyToOne(targetEntity: OfferPrice::class, inversedBy: 'myEntities', cascade: ['persist'])]
+        /**
+         * @ORM\ManyToOne(targetEntity=OfferPrice::class, inversedBy="myEntities", cascade={"persist"})
+         */
         private OfferPrice $offerPrice
     )
     {

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/ScopeNotAvailable/ArrayAnnotationToAttributeTest.php:16

82) Rector\Tests\Issues\ScopeNotAvailable\JsonThrowCaseSensitiveConstFetchTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
         $condition = true;
         if ($condition) {
             $output = [];
-            echo(json_encode($output, JSON_THROW_ON_ERROR));
+            echo(json_encode($output));
             exit;
         }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/ScopeNotAvailable/JsonThrowCaseSensitiveConstFetchTest.php:16

83) Rector\Tests\Issues\SimplifyEmpty\SimplifyEmptyTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"

Applied Rector rules:
 * Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector
 * Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector
 * Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector

Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
     public function check(): bool
     {
-        return $this->getString() !== null && $this->getString() !== '' && $this->getString() !== '0' && !(($this->getString2() === null || $this->getString2() === '' || $this->getString2() === '0') && !is_numeric($this->getString2()));
+        return $this->getString() !== null && $this->getString() !== '' && $this->getString() !== '0' && !($this->getString2() === null || $this->getString2() === '' || $this->getString2() === '0') && !is_numeric($this->getString2());
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/tests/Issues/SimplifyEmpty/SimplifyEmptyTest.php:16

84) Rector\Tests\Carbon\Rector\FuncCall\TimeFuncCallToCarbonRector\TimeFuncCallToCarbonRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "first_class_callable.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        $time = static fn() => \Carbon\Carbon::now()->timestamp;
+        $time = (static fn() => \Carbon\Carbon::now()->timestamp);
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Carbon/Rector/FuncCall/TimeFuncCallToCarbonRector/TimeFuncCallToCarbonRectorTest.php:16

85) Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\ExplicitReturnNullRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "do_while_maybe_returned.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 			return 2;
 		} while (++$i < 1);
-  return null;
+        return null;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/ClassMethod/ExplicitReturnNullRector/ExplicitReturnNullRectorTest.php:16

86) Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\ExplicitReturnNullRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "do_while_maybe_returned2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 		    return 2;
 		} while (++$i < 1);
-  return null;
+        return null;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/ClassMethod/ExplicitReturnNullRector/ExplicitReturnNullRectorTest.php:16

87) Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\ExplicitReturnNullRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "do_while_maybe_returned3.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
                 return 1;
     		}
 		} while (++$i < 1);
-  return null;
+        return null;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/ClassMethod/ExplicitReturnNullRector/ExplicitReturnNullRectorTest.php:16

88) Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\ExplicitReturnNullRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "do_while_maybe_returned4.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 		} while (++$i < 1);
 		execute:
 			echo 'test';
-   return null;
+            return null;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/ClassMethod/ExplicitReturnNullRector/ExplicitReturnNullRectorTest.php:16

89) Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\UnusedForeachValueToArrayKeysRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "remove_both_item_from_value_use.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run(array $definitions)
     {
-        foreach (array_keys($definitions) as $id) {
+        foreach ($definitions as $id => [$domElement, $file]) {
             echo $id;
         }
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/Foreach_/UnusedForeachValueToArrayKeysRector/UnusedForeachValueToArrayKeysRectorTest.php:16

90) Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\UnusedForeachValueToArrayKeysRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "remove_single_item_from_value_use.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run(array $definitions)
     {
-        foreach ($definitions as $id => [$domElement]) {
+        foreach ($definitions as $id => [$domElement, $file]) {
             if ($domElement) {
                 return true;
             }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/Foreach_/UnusedForeachValueToArrayKeysRector/UnusedForeachValueToArrayKeysRectorTest.php:16

91) Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\CombineIfRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "property_fetch_in_condition.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        if ($this->art->netzid > 0 && ($artzo_list = $this->artzo_list) && $artzo_list !== []) {
+        if ($this->art->netzid > 0 && $artzo_list = $this->artzo_list && $artzo_list !== []) {
             foreach ($artzo_list as $art) {
             }
         }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/If_/CombineIfRector/CombineIfRectorTest.php:16

92) Rector\Tests\CodeQuality\Rector\If_\CombineIfRector\CombineIfRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "property_fetch_in_condition2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        if ($artzo_list !== [] && ($this->art->netzid > 0 && $artzo_list = $this->artzo_list)) {
+        if ($artzo_list !== [] && $this->art->netzid > 0 && $artzo_list = $this->artzo_list) {
             foreach ($artzo_list as $art) {
             }
         }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/If_/CombineIfRector/CombineIfRectorTest.php:16

93) Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\SimplifyIfReturnBoolRectorTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture7.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function validateDimensions()
     {
-        return !(true || true);
+        return !true || true;
     }
 
     public function function2($value, $secondValue)
     {
-        return !(($value === true) || ($secondValue === true));
+        return !($value === true) || ($secondValue === true);
     }
 
     public function function3($attribute, $value, $parameters)
     {
-        return !($this->failsBasicDimensionChecks($parameters, $width, $height) ||
-            $this->failsRatioCheck($parameters, $width, $height));
+        return !$this->failsBasicDimensionChecks($parameters, $width, $height) ||
+            $this->failsRatioCheck($parameters, $width, $height);
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/If_/SimplifyIfReturnBoolRector/SimplifyIfReturnBoolRectorTest.php:16

94) Rector\Tests\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector\LogicalToBooleanRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "or.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-if (($f = false) || true) {
+if ($f = false || true) {
     return $f;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/LogicalAnd/LogicalToBooleanRector/LogicalToBooleanRectorTest.php:16

95) Rector\Tests\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector\LogicalToBooleanRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "mixed.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     if (
     (
-        1 === $val1 || (1 === $val2 ||
-        1 === $val3) || 1 === $val4
+        1 === $val1 || 1 === $val2 ||
+        1 === $val3 || 1 === $val4
     )
     ) {
         //code
@@ @@
 
     if (
     (
-        1 === $val1 && (1 === $val2 &&
-        1 === $val3) && 1 === $val4
+        1 === $val1 && 1 === $val2 &&
+        1 === $val3 && 1 === $val4
     )
     ) {
         //code

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/LogicalAnd/LogicalToBooleanRector/LogicalToBooleanRectorTest.php:16

96) Rector\Tests\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector\LogicalToBooleanRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "and.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-if (($f = false) && true) {
+if ($f = false && true) {
     return $f;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/LogicalAnd/LogicalToBooleanRector/LogicalToBooleanRectorTest.php:16

97) Rector\Tests\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector\SwitchNegatedTernaryRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_ternary.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
     {
         return isset($a)
             ? ($a)
-            : (isset($b) ? $b : 'b');
+            : isset($b) ? $b : 'b';
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/CodeQuality/Rector/Ternary/SwitchNegatedTernaryRector/SwitchNegatedTernaryRectorTest.php:16

98) Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\RecastingRemovalRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
         $string = $string;
 
         $array = [];
-        $array = $array;
+        $array = (array) $array;
 
         $array = (array) $string;
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/DeadCode/Rector/Cast/RecastingRemovalRector/RecastingRemovalRectorTest.php:16

99) Rector\Tests\DeadCode\Rector\ClassLike\RemoveAnnotationRector\RemoveAnnotationRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "remove_by_type.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class RemoveByType
 {
+    /**
+     * @DI\InjectParams({
+     *     "subscribeService" = 'some',
+     *     "ipService" = 'some'
+     * })
+     */
     public function __construct()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/DeadCode/Rector/ClassLike/RemoveAnnotationRector/RemoveAnnotationRectorTest.php:16

100) Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\RemoveEmptyClassMethodRectorTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_public_presenter_method_with_non_primitive_annotation.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class KeepPublicPresenterMethodWithoutPrimitiveAnnotation extends Presenter
 {
-    /**
-     * @User(secured)
-     */
-    public function keep()
-    {
-    }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/DeadCode/Rector/ClassMethod/RemoveEmptyClassMethodRector/RemoveEmptyClassMethodRectorTest.php:16

101) Rector\Tests\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector\ChangeNestedForeachIfsToEarlyContinueRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multi_exprs_with_OR_both_true.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
             if (!true) {
                 continue;
             }
-            if (!(1 === 1 || 2 === 2)) {
+            if (!1 === 1 || 2 === 2) {
                 continue;
             }
             $payload[] = $partPackage;

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/EarlyReturn/Rector/Foreach_/ChangeNestedForeachIfsToEarlyContinueRector/ChangeNestedForeachIfsToEarlyContinueRectorTest.php:16

102) Rector\Tests\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector\ChangeNestedForeachIfsToEarlyContinueRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multi_exprs_with_OR_both_true2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
             if (!true) {
                 continue;
             }
-            if (!(1 === 1 || 2 === 2)) {
+            if (!1 === 1 || 2 === 2) {
                 continue;
             }
-            if (!(1 === 1 || 2 === 2)) {
+            if (!1 === 1 || 2 === 2) {
                 continue;
             }
             $payload[] = $partPackage;

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/EarlyReturn/Rector/Foreach_/ChangeNestedForeachIfsToEarlyContinueRector/ChangeNestedForeachIfsToEarlyContinueRectorTest.php:16

103) Rector\Tests\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector\ChangeNestedForeachIfsToEarlyContinueRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "comment_inside_if_statement.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
             if ($value !== 5) {
                 continue;
             }
-            // why am I doing this?
             if ($value !== 10) {
                 continue;
             }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/EarlyReturn/Rector/Foreach_/ChangeNestedForeachIfsToEarlyContinueRector/ChangeNestedForeachIfsToEarlyContinueRectorTest.php:16

104) Rector\Tests\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector\ChangeIfElseValueAssignToEarlyReturnRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "lost_comment.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
     protected function replaceUrl($internalUrl, $publicUrl, $contenttext)
     {
         if (null === $publicUrl) {
-            // parameters could not be resolved into a url
             return preg_replace('@href=(["\'\s]*'.preg_quote($internalUrl, '@').'["\'\s]*)@', '', $contenttext);
         }
 
-        // else comment
         return str_replace($internalUrl, $publicUrl, $contenttext);
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/EarlyReturn/Rector/If_/ChangeIfElseValueAssignToEarlyReturnRector/ChangeIfElseValueAssignToEarlyReturnRectorTest.php:16

105) Rector\Tests\Php56\Rector\FuncCall\PowToExpRector\PowToExpRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
     $result = 1.2 ** 2.3;
 
-    echo (-2) ** 3;
+    echo -2 ** 3;
 
-    ($a--) ** (++$b);
+    $a-- ** ++$b;
 
     \a\pow(5, 6);
     7 ** 8;
@@ @@
 
     (9 ** 10) ** 11 ** 12;
 
-    (1 + 2) ** (3 * 4);
+    1 + 2 ** 3 * 4;
 
-    ($b = 4) ** 3;
+    $b = 4 ** 3;
 
     13 ** 14;

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php56/Rector/FuncCall/PowToExpRector/PowToExpRectorTest.php:20

106) Rector\Tests\Php73\Rector\String_\SensitiveHereNowDocRector\SensitiveHereNowDocRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 function sensitiveHereNowDoc()
 {
     $value = <<<PHP_WRAP
-    PHP_2
-PHP_WRAP;
+        PHP_2
+    PHP_WRAP;
 
     $value = <<<PHP_WRAP_1
-    PHP_WRAP_2
-PHP_WRAP_1;
+        PHP_WRAP_2
+    PHP_WRAP_1;
 
 // examples from RFC
     $value = <<<END_WRAP
-a
-b
-ENDING
-END_WRAP;
+    a
+    b
+    ENDING
+    END_WRAP;
 }
 
 ?>

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php73/Rector/String_/SensitiveHereNowDocRector/SensitiveHereNowDocRectorTest.php:19

107) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_docblock_on_return4.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
     public function run()
     {
         function deep() {
-            fn() =>
+            fn() => 
                 /**
                  * comment
                  */

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

108) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "referenced_but_not_used.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        $callback = fn($b) => ++$b;
+        $callback = (fn($b) => ++$b);
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

109) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "referenced_inner_class.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        $callback = fn($b) => new class { public function __construct($i)
+        $callback = (fn($b) => new class { public function __construct($i)
             {
             }
-        };
+        });
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

110) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_docblock_on_return3.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        fn() =>
+        fn() => 
             /**
              * comment
              */

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

111) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #6 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "reference_to_inner_closure_unused.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run($a)
     {
-        $y = fn() => fn() => 1;
+        $y = (fn() => fn() => 1);
 
         return $y;
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

112) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_docblock_on_return.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        fn() =>
+        fn() => 
             /** @psalm-suppress UndefinedFunction */
             ff();
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

113) Rector\Tests\Php74\Rector\Closure\ClosureToArrowFunctionRector\ClosureToArrowFunctionRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_docblock_on_return2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        fn() =>
+        fn() => 
             // @psalm-suppress UndefinedFunction
             ff();
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Closure/ClosureToArrowFunctionRector/ClosureToArrowFunctionRectorTest.php:16

114) Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\AddLiteralSeparatorToNumberRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "big_integers.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     public function run()
     {
-        $int2 = 1_000_000;
+        $int2 = 1000000;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/LNumber/AddLiteralSeparatorToNumberRector/AddLiteralSeparatorToNumberRectorTest.php:16

115) Rector\Tests\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector\AddLiteralSeparatorToNumberRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "big_floats.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
     public function run()
     {
         $float = 1000.0;
-        $float2 = 1_000_000.0;
-        $float3 = 1_000_500.001;
+        $float2 = 1000000.0;
+        $float3 = 1000500.001;
     }
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/LNumber/AddLiteralSeparatorToNumberRector/AddLiteralSeparatorToNumberRectorTest.php:16

116) Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\ParenthesizeNestedTernaryRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
   $b = 2;
   $c = 3;
 
-  $value = ($a ? $b : $a) ?: null;
-  $value = (($a ? $b : $a) ?: null);
-  $value = ($a ? $b : $a) ? $c : null;
-  $value = (($a ? $b : $a) ? $c : null);
+  $value = $a ? $b : $a ?: null;
+  $value = ($a ? $b : $a ?: null);
+  $value = $a ? $b : $a ? $c : null;
+  $value = ($a ? $b : $a ? $c : null);
 }
 
 ?>

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php74/Rector/Ternary/ParenthesizeNestedTernaryRector/ParenthesizeNestedTernaryRectorTest.php:16

117) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #26 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_quote.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation('
-    summary: key
-    description: \'something `id => name`.\'
-')]
+/**
+ * @GenericAnnotation("
+ *     summary: key
+ *     description: 'something `id => name`.'
+ * ")
+ */
 final class NestedQuote
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

118) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #27 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_quoted_asterisk.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation('
-    key: value
-    another_key:
-        another_value/*:
-            schema: 100
-')]
+/**
+ * @GenericAnnotation("
+ *     key: value
+ *     another_key:
+ *         another_value/*:
+ *             schema: 100
+ * ")
+ */
 final class NestedQuote
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

119) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #28 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "non_namespaced_class_with_annotation.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 <?php
 
-#[Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation(some: '/demo/')]
+/**
+ * @Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation(
+ *     some="/demo/"
+ * )
+ */
 final class NonNamespacedClassWithAnnotation
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

120) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #30 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "simple_nested_arrays_and_alias_import.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class EntityColumnAndAssertChoice
 {
-    #[Assert\GenericAnnotation(['php5', 'php7'])]
-    #[Assert\GenericAnnotation(choices: ['5.0', 'id' => '5.2'])]
-    #[Assert\GenericAnnotation(choices: [2, 3, 5])]
+    /**
+     * @Assert\GenericAnnotation({"php5", "php7"})
+     * @Assert\GenericAnnotation(choices={"5.0", "id"="5.2"})
+     * @Assert\GenericAnnotation(choices={2, 3, 5})
+     */
     public $primeNumbers;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

121) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #31 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "trailing_comma.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class TrailingComma
 {
-    #[GenericAnnotation(key: 'value')]
-    #[GenericAnnotation(some: 'item', summary: 'item')]
+    /**
+     * @GenericAnnotation(key="value", )
+     * @GenericAnnotation(
+     *     "some" = "item",
+     *     "summary" = "item",
+     * )
+     */
     protected $someColumn;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

122) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #32 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "with_comment.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 {
     /**
      * This comment is before the annotations
+     * @GenericAnnotation(key="value") this annotation has parameters
+     * @GenericAnnotation(
+     *     "some" = "item",
+     *     "summary" = "item",
+     * ) this annotation is multi-line
+     * @GenericAnnotation(key="value") (this comment is within parentheses)
+     * @GenericAnnotation(key="value") "this comment is within quotes"
+     * This comment does not belong to an annotation and will be ignored
      */
     #[GenericAnnotation] // this is a simple annotation
-    #[GenericAnnotation(key: 'value')] // this annotation has parameters
-    #[GenericAnnotation(some: 'item', summary: 'item')] // this annotation is multi-line
-    #[GenericAnnotation(key: 'value')] // (this comment is within parentheses)
-    #[GenericAnnotation(key: 'value')] // "this comment is within quotes"
     protected $someColumn;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

123) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #6 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "validation_integer_parameter.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ValidationIntegerParameter
 {
-    #[Assert\Length(min: 100, max: 255, maxMessage: 'some Message', allowed: true)]
+    /**
+     * @Assert\Length(
+     *     min=100,
+     *     max=255,
+     *     maxMessage="some Message",
+     *     allowed=true
+     * )
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

124) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "preserve_quotes.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class MyEntity
 {
-    #[Assert\Choice(callback: '\MyApp\Path\To\My::callbackFunction', strict: true)]
+    /**
+     * @Assert\Choice(callback="\MyApp\Path\To\My::callbackFunction", strict=true)
+     */
     private string $type;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

125) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #10 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "symfony_route.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SymfonyRoute
 {
-    #[Route(path: '/path', name: EntityColumnAndAssertChoice::class)]
+    /**
+     * @Route("/path", name=EntityColumnAndAssertChoice::class)
+     */
     public function action()
     {
         $keepSpace = 1;

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

126) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #11 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "symfony_security.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SymfonySecurity
 {
-    #[Security("is_granted(constant('\\\\Rector\\\\Tests\\\\Php80\\\\Rector\\\\Class_\\\\AnnotationToAttributeRector\\\\Source\\\\ConstantReference::FIRST_NAME'))")]
+    /**
+     * @Security("is_granted(constant('\\Rector\\Tests\\Php80\\Rector\\Class_\\AnnotationToAttributeRector\\Source\\ConstantReference::FIRST_NAME'))")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

127) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #22 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_single_quoted_key_value.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation('sample', itemOperations: 'yes')]
+/**
+ * @GenericAnnotation("sample", itemOperations='yes')
+ */
 final class KeepSingleQuotedKeyValue
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

128) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "validation_file.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ValidationFile
 {
-    #[Assert\File(maxSize: 100)]
+    /**
+     * @Assert\File(maxSize="100")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

129) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "validation_string_parameter.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ValidationIntegerParameter
 {
-    #[Assert\Length(min: 100, max: 255, maxMessage: 'some Message', allowed: 'true')]
+    /**
+     * @Assert\Length(
+     *     min="100",
+     *     max="255",
+     *     maxMessage="some Message",
+     *     allowed="true"
+     * )
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

130) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #18 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "first_param_is_silent.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation('sample', itemOperations: 'yes')]
+/**
+ * @GenericAnnotation("sample", itemOperations="yes")
+ */
 final class FirstParamIsSilent
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

131) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #23 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multiline_content.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation('
-    summary: Send webcam reward
-')]
+/**
+ * @GenericAnnotation("
+ *     summary: Send webcam reward
+ * ")
+ */
 final class MultilineContent
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

132) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #24 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_array_with_constants.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;
 
-#[GenericAnnotation(some: [ConstantReference::FIRST_NAME => ['foo' => ['bar']], ConstantReference::LAST_NAME])]
-#[GenericAnnotation(some: [ConstantReference::LAST_NAME, 'trailingValue'])]
+/**
+ * @GenericAnnotation(
+ *     some={
+ *          ConstantReference::FIRST_NAME={
+ *              foo: {"bar"}
+ *          },
+ *          ConstantReference::LAST_NAME
+ *     }
+ * )
+ * @GenericAnnotation(
+ *     some={
+ *          ConstantReference::LAST_NAME,
+ *          trailingValue
+ *     }
+ * )
+ */
 final class ArrayWithConstantAsKey
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

133) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #20 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "function_call_inside.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class FunctionCallInside
 {
-    #[GenericAnnotation("is_granted('ROLE_USER')")]
+    /**
+     * @GenericAnnotation("is_granted('ROLE_USER')")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

134) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nullable_bool_value.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
-#[ORM\Entity(repositoryClass: \App\Some\Class::class)]
+/**
+ * @ORM\Entity(repositoryClass="App\Some\Class")
+ */
 class NullableBoolValue
 {
-    #[ORM\Column(type: 'bigint', nullable: true)]
+    /**
+     * @ORM\Column(type="bigint", nullable="true")
+     */
     private int $stop_ts;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

135) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "validation_cast_integer_parameter.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ValidationCastIntegerParameter
 {
-    #[Assert\Length(min: 100, max: 255)]
+    /**
+     * @Assert\Length(
+     *     min="100",
+     *     max="255"
+     * )
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

136) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "param_converter_with_silent_key.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ParamConverterWithSilentKey
 {
-    #[ParamConverter('some_name')]
+    /**
+     * @ParamConverter(name="some_name")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

137) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #12 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "symfony_security2.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SymfonySecurity2
 {
-    #[Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_FRIENDLY_USER')")]
+    /**
+     * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_FRIENDLY_USER')")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

138) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #21 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "keep_single_quoted.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericSingleImplicitAnnotation;
 
-#[GenericSingleImplicitAnnotation('/')]
+/**
+ * @GenericSingleImplicitAnnotation('/')
+ */
 final class KeepSingleQuoted
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

139) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #17 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "constant_as_array_key.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\ConstantReference;
 
-#[GenericAnnotation([ConstantReference::FIRST_NAME => true, 'some::string' => 'some-value'])]
+/**
+ * @GenericAnnotation({
+ *     ConstantReference::FIRST_NAME = true,
+ *     "some::string" = "some-value"
+ * })
+ */
 final class ConstantAsArrayKey
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

140) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #19 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "from_implicit_to_name.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericSingleImplicitAnnotation;
 
-#[GenericSingleImplicitAnnotation('/')]
+/**
+ * @GenericSingleImplicitAnnotation("/")
+ */
 final class FromImplicitToName
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

141) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #5 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "validation_file_with_string.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ValidationFileWithString
 {
-    #[Assert\File(maxSize: '5555K')]
+    /**
+     * @Assert\File(maxSize="5555K")
+     */
     public function action()
     {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

142) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #25 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_nested_arrays.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation(title: 'sample', route: ['absolute' => true, 'parameters' => ['id' => '{id}']])]
+/**
+ * @GenericAnnotation(title="sample", route={absolute=true, parameters={id="{id}"}}))
+ */
 final class NestedNestedArrays
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

143) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "preserve_int_key_defined.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
-#[\Doctrine\ORM\Mapping\Embeddable]
-#[ORM\DiscriminatorMap([1 => 'CostDetailEntity'])]
+/**
+ * @\Doctrine\ORM\Mapping\Embeddable
+ * @ORM\DiscriminatorMap({ 1 = "CostDetailEntity" })
+ */
 class PreserveIntKeyDefined
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

144) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_entity.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;
 
-#[\Doctrine\ORM\Mapping\Entity(repositoryClass: \App\Some\Class::class)]
+/**
+ * @\Doctrine\ORM\Mapping\Entity(repositoryClass="App\Some\Class")
+ */
 class DoctrineEntity
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/AnnotationToAttributeRectorTest.php:16

145) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\MultipleCallAnnotationToAttributeRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fixture.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 #[Entity]
 class User
 {
-    #[ManyToMany(targetEntity: 'Phonenumber')]
+    /**
+     * @ManyToMany(targetEntity="Phonenumber")
+     */
     public $phonenumbers;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/MultipleCallAnnotationToAttributeRectorTest.php:16

146) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "nested_attributes_with_brackets.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class NestedAttributeWithBrackets
 {
-    #[Assert\All([new Assert\NotNull()])]
+    /**
+     * @Assert\All({
+     *     @Assert\NotNull()
+     * })
+     */
     public $value;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

147) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #5 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "unique_constraints.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;
 
-#[\Doctrine\ORM\Mapping\Table]
-#[\Doctrine\ORM\Mapping\UniqueConstraint(name: 'some_key')]
+/**
+ * @\Doctrine\ORM\Mapping\Table(uniqueConstraints={
+ *     @\Doctrine\ORM\Mapping\UniqueConstraint(name="some_key")
+ * })
+ */
 final class UniqueConstraints
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

148) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "sql_statement.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
 
-#[GenericAnnotation(filters: [new GenericAnnotation(some: '
-         $this.id IN(
-             SELECT id
-             FROM foo
-             WHERE bar = \'baz\'
-         )')])]
+/**
+ * @GenericAnnotation(filters={
+ *      @GenericAnnotation(some="
+ *          $this.id IN(
+ *              SELECT id
+ *              FROM foo
+ *              WHERE bar = 'baz'
+ *          )"
+ *      )
+ * })
+ */
 class SqlStatement
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

149) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multiple_nested_attributes_without_parentheses.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class MultipleNestedAttributeWithoutParentheses
 {
-    #[Assert\All([new Assert\NotNumber(secondValue: 1000), new Assert\NotNumber(hey: 10, hi: 'hello')])]
+    /**
+     * @Assert\All({
+     *     @Assert\NotNumber(secondValue=1000),
+     *     @Assert\NotNumber(hey=10, hi="hello"),
+     * })
+     */
     public $value;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

150) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "new_line_after_open_parentheses.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\FixturePhp81;
 
-#[\Doctrine\ORM\Mapping\Table]
-#[\Doctrine\ORM\Mapping\UniqueConstraint(name: 'some_key')]
+/**
+ * @\Doctrine\ORM\Mapping\Table(
+ *      uniqueConstraints={
+ *          @\Doctrine\ORM\Mapping\UniqueConstraint(name="some_key")
+ *      }
+ * )
+ */
 final class AfterOpenParentheses
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

151) Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multiple_nested_attributes.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class MultipleNestedAttribute
 {
-    #[Assert\All([new Assert\NotNumber(secondValue: 1000), new Assert\NotNumber(hey: 10, hi: 'hello')])]
+    /**
+     * @Assert\All({
+     *     @Assert\NotNumber(secondValue=1000),
+     *     @Assert\NotNumber(hey=10, hi="hello"),
+     * })
+     */
     public $value;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Class_/AnnotationToAttributeRector/Php81NestedAttributesRectorTest.php:19

152) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "string_annotation_value.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
-#[ORM\Table(name: App\Entity\Aktenzeichen::TABLE_NAME)]
-#[ORM\UniqueConstraint(name: 'aktenzeichen_idx', columns: ['aktenzeichen'])]
+/**
+ * @ORM\Table(name=App\Entity\Aktenzeichen::TABLE_NAME, uniqueConstraints={@ORM\UniqueConstraint(name="aktenzeichen_idx", columns={"aktenzeichen"})})
+ */
 class StringAnnotationValue
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

153) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #5 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "fully_qualified_use.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 use Doctrine\ORM\Mapping as ORM;
 use Doctrine\ORM\Mapping\Index;
 
-#[ORM\Table]
-#[Index(name: 'search_idx')]
+/**
+ * @ORM\Table(indexes={@Index(name="search_idx")}]
+ */
 final class FullyQualifiedUse
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

154) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "some_class.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SomeEntity
 {
-    #[ORM\JoinTable(name: 'join_table_name')]
-    #[ORM\JoinColumn(name: 'origin_id')]
-    #[ORM\InverseJoinColumn(name: 'target_id')]
+    /**
+     * @ORM\JoinTable(name="join_table_name",
+     *     joinColumns={@ORM\JoinColumn(name="origin_id")},
+     *     inverseJoinColumns={@ORM\JoinColumn(name="target_id")}
+     * )
+     */
     private $collection;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

155) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #4 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_unique_constraints.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping\Table;
 
-#[Table]
-#[\Doctrine\ORM\Mapping\UniqueConstraint(name: 'some_name')]
+/**
+ * @Table(uniqueConstraints={@\Doctrine\ORM\Mapping\UniqueConstraint(name="some_name")})
+ */
 class DoctrineUniqueConstraints
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

156) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #7 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "multiple_inversed_join_columns.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class MultipleInversedJoinColumns
 {
-    #[ORM\JoinTable(name: 'join_table_name')]
-    #[ORM\InverseJoinColumn(name: 'target_id')]
-    #[ORM\InverseJoinColumn(name: 'another_id')]
+    /**
+     * @ORM\JoinTable(name="join_table_name",
+     *     inverseJoinColumns={
+     *          @ORM\JoinColumn(name="target_id"),
+     *          @ORM\JoinColumn(name="another_id")
+     *     }
+     * )
+     */
     private $collection;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

157) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #2 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_nested_join_columns_promoted_property.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 final class DoctrineNestedJoinColumnsPromotedProperty
 {
     public function __construct(
-        #[JoinColumn(name: 'entity_id', referencedColumnName: 'id')]
-        #[JoinColumn(name: 'entity_type', referencedColumnName: 'entity_type')]
+        /**
+         * @JoinColumns({
+         *   @JoinColumn(name="entity_id", referencedColumnName="id"),
+         *   @JoinColumn(name="entity_type", referencedColumnName="entity_type"),
+         * })
+         */
         protected $page,
     ) {
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

158) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #6 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "merge_with_existing_attribute.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
+/**
+ * @ORM\Table(name="api_alert")
+ */
 #[ApiResource]
-#[ORM\Table(name: 'api_alert')]
 class Alert
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

159) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #3 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_table.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 use Doctrine\ORM\Mapping as ORM;
 
-#[ORM\Table(name: 'user_account_role')]
-#[ORM\Index(name: 'some_name')]
-#[ORM\UniqueConstraint(name: 'user_account_role_unique', columns: ['user_account_id', 'list_user_role_id'])]
-#[ORM\UniqueConstraint(name: 'second')]
-#[ORM\UniqueConstraint(name: 'third')]
+/**
+ * @ORM\Table(name="user_account_role",
+ *     uniqueConstraints={
+ *          @ORM\UniqueConstraint(name="user_account_role_unique", columns={"user_account_id", "list_user_role_id"}),
+ *          @ORM\UniqueConstraint(name="second"),
+ *          @ORM\UniqueConstraint(name="third"),
+ *      },
+ *     indexes={
+ *          @ORM\Index(name="some_name")
+ *     }
+ * )
+ */
 class DoctrineTable
 {
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

160) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_nested_join_columns.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class DoctrineNestedJoinColumns
 {
-    #[ORM\JoinColumn(name: 'entity_id', referencedColumnName: 'id')]
-    #[ORM\JoinColumn(name: 'entity_type', referencedColumnName: 'entity_type')]
+    /**
+     * @ORM\JoinColumns({
+     *   @ORM\JoinColumn(name="entity_id", referencedColumnName="id"),
+     *   @ORM\JoinColumn(name="entity_type", referencedColumnName="entity_type"),
+     * })
+     */
     protected $page;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

161) Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "doctrine_join_column_nested_in_join_table.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\Fixture;
 
-use Doctrine\ORM\Mapping\InverseJoinColumn;
 use Doctrine\ORM\Mapping\JoinColumn;
 use Doctrine\ORM\Mapping\JoinTable;
 
 final class DoctrineJoinColumnNestedInJoinTable
 {
-    #[JoinTable(name: 'lemma_type')]
-    #[InverseJoinColumn(name: 'lemma_id', referencedColumnName: 'lemma_id')]
+    /**
+     * @JoinTable(name="lemma_type",
+     *      inverseJoinColumns={@JoinColumn(name="lemma_id", referencedColumnName="lemma_id")}
+     * )
+     */
     private iterable $lemmas;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php80/Rector/Property/NestedAnnotationToAttributeRector/NestedAnnotationToAttributeRectorTest.php:16

162) Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\ReadOnlyPropertyRectorTest::test with data set #18 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "skip_entity.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
      * @ORM\GeneratedValue(strategy="CUSTOM")
      * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
      */
-    private UuidInterface $id;
+    private readonly UuidInterface $id;
 
     /**
      * @var string

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Php81/Rector/Property/ReadOnlyPropertyRector/ReadOnlyPropertyRectorTest.php:16

163) Rector\Tests\Renaming\Rector\Name\RenameClassRector\RenameClassRectorTest::test with data set #21 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "some_rename_before_attribute.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;
 
-use Doctrine\DBAL\DBALException;
 use Symfony\Component\Routing\Annotation\Route;
 
 class SomeRenameBeforeAttribute

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Renaming/Rector/Name/RenameClassRector/RenameClassRectorTest.php:16

164) Rector\Tests\Renaming\Rector\Name\RenameClassRector\RenameClassRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "class_annotations_serializer_type.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;
 
-use JMS\Serializer\Annotation as Serializer;
-
 class ClassAnnotationsSerializerIterableType
 {
     /**
-     * @Serializer\Type("array<Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass>")
+     * @Serializer\Type("array<Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass>")
      */
     public $flights = [];
 
     /**
-     * @Serializer\Type("Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass")
+     * @Serializer\Type("Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass")
      */
     public $time;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Renaming/Rector/Name/RenameClassRector/RenameClassRectorTest.php:16

165) Rector\Tests\Renaming\Rector\Name\RenameClassRector\RenameClassRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "class_annotations_target_entity.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;
 
-use Doctrine\ORM\Mapping as ORM;
-
 final class ClassAnnotationsTargetEntity
 {
     /**
-     * @ORM\OneToMany(targetEntity="Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass")
+     * @ORM\OneToMany(targetEntity="Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass")
      */
     public $entityProperty;
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/Renaming/Rector/Name/RenameClassRector/RenameClassRectorTest.php:16

166) Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\ReturnNeverTypeRectorTest::test with data set #18 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "skip_throw_as_expr.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class SkipThrowAsExpr
 {
-    public function run($someClass)
+    public function run($someClass): never
     {
         $this->foo = $someClass ?: throw new Exception('current request is null');
     }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/TypeDeclaration/Rector/ClassMethod/ReturnNeverTypeRector/ReturnNeverTypeRectorTest.php:16

167) Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\ReturnTypeFromStrictNewArrayRectorTest::test with data set #9 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "return_specific_type.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ReturnSpecificType
 {
-    /**
-     * @return \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\AppleJuice[]
-     */
     public function run(): array
     {
         $values = [];

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/TypeDeclaration/Rector/ClassMethod/ReturnTypeFromStrictNewArrayRector/ReturnTypeFromStrictNewArrayRectorTest.php:16

168) Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\ReturnTypeFromStrictNewArrayRectorTest::test with data set #8 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "return_override_with_array.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
 
 final class ReturnOverrideWithArray
 {
-    /**
-     * @return int[]
-     */
     public function run(): array
     {
         $values = [];

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/TypeDeclaration/Rector/ClassMethod/ReturnTypeFromStrictNewArrayRector/ReturnTypeFromStrictNewArrayRectorTest.php:16

169) Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\TypedPropertyFromAssignsRectorTest::test with data set #1 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "skip_column.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
      * @ORM\Column(name="userID", type="integer", nullable=false)
      * @ORM\Id
      */
-    private $someId = '0';
+    private string $someId = '0';
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/TypeDeclaration/Rector/Property/TypedPropertyFromAssignsRector/TypedPropertyFromAssignsRectorTest.php:16

170) Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\TypedPropertyFromAssignsRectorTest::test with data set #0 ('/Users/samsonasik/www/rector-...hp.inc')
Failed on fixture file "skip_collection.php.inc"
Failed asserting that string matches format description.
--- Expected
+++ Actual
@@ @@
     /**
      * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User\UserToAffiliate")
      */
-    private $items = [];
+    private array $items = [];
 }

/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:249
/Users/samsonasik/www/rector-src/src/Testing/PHPUnit/AbstractRectorTestCase.php:165
/Users/samsonasik/www/rector-src/rules-tests/TypeDeclaration/Rector/Property/TypedPropertyFromAssignsRector/TypedPropertyFromAssignsRectorTest.php:16

ERRORS!
Tests: 4067, Assertions: 4865, Errors: 6, Failures: 170.
