<?php

declare(strict_types=1);

namespace Rector\Tests\Php70;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Php70\EregToPcreTransformer;

final class EregToPcreTransformerTest extends TestCase
{
    private EregToPcreTransformer $eregToPcreTransformer;

    protected function setUp(): void
    {
        $this->eregToPcreTransformer = new EregToPcreTransformer();
    }

    #[DataProvider('provideDataDropping')]
    #[DataProvider('provideDataCaseSensitive')]
    public function testCaseSensitive(string $ereg, string $expectedPcre): void
    {
        $pcre = $this->eregToPcreTransformer->transform($ereg, false);
        $this->assertSame($expectedPcre, $pcre);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataCaseSensitive(): Iterator
    {
        yield ['hi', '#hi#m'];
    }

    #[DataProvider('provideDataCaseInsensitive')]
    public function testCaseInsensitive(string $ereg, string $expectedPcre): void
    {
        $pcre = $this->eregToPcreTransformer->transform($ereg, true);
        $this->assertSame($expectedPcre, $pcre);
    }

    /**
     * @return Iterator<string[]>
     */
    public static function provideDataCaseInsensitive(): Iterator
    {
        yield ['hi', '#hi#mi'];
    }

    /**
     * @return Iterator<mixed>
     */
    public static function provideDataDropping(): Iterator
    {
        yield ['mearie\.org', '#mearie\.org#m'];
        yield ['mearie[.,]org', '#mearie[\.,]org#m'];
        yield ['[a-z]+[.,][a-z]+', '#[a-z]+[\.,][a-z]+#m'];
        yield ['^[a-z]+[.,][a-z]+$', '#^[a-z]+[\.,][a-z]+$#m'];
        yield ['^[a-z]+[.,][a-z]{3,}$', '#^[a-z]+[\.,][a-z]{3,}$#m'];
        yield ['a|b|(c|d)|e', '#a|b|(c|d)|e#m'];
        yield ['a|b|()|c', '#a|b|()|c#m'];
        yield ['[[:alnum:][:punct:]]', '#[[:alnum:][:punct:]]#m'];
        yield ['[]-z]', '#[\]-z]#m'];
        yield ['[[a]]', '#[\[a]\]#m'];
        yield ['[---]', '#[\--\-]#m'];
        yield ['[a\z]', '#[a\\\z]#m'];
        yield ['[^^]', '#[^\^]#m'];
        yield ['^$^$^$^$', '#^$^$^$^$#m'];
        yield ['\([^>]*\"?[^)]*\)', '#\([^>]*"?[^\)]*\)#m'];
        yield [
            '^(http(s?):\/\/|ftp:\/\/)*([[:alpha:]][-[:alnum:]]*[[:alnum:]])(\.[[:alpha:]][-[:alnum:]]*[[:alpha:]])+(/[[:alpha:]][-[:alnum:]]*[[:alnum:]])*(\/?)(/[[:alpha:]][-[:alnum:]]*\.[[:alpha:]]{3,5})?(\?([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+)(&([[:alnum:]][-_%[:alnum:]]*=[-_%[:alnum:]]+))*)?$',
            '#^(http(s?):\/\/|ftp:\/\/)*([[:alpha:]][\-[:alnum:]]*[[:alnum:]])(\.[[:alpha:]][\-[:alnum:]]*[[:alpha:]])+(\/[[:alpha:]][\-[:alnum:]]*[[:alnum:]])*(\/?)(\/[[:alpha:]][\-[:alnum:]]*\.[[:alpha:]]{3,5})?(\?([[:alnum:]][\-_%[:alnum:]]*=[\-_%[:alnum:]]+)(&([[:alnum:]][\-_%[:alnum:]]*=[\-_%[:alnum:]]+))*)?$#m',
        ];
    }
}
