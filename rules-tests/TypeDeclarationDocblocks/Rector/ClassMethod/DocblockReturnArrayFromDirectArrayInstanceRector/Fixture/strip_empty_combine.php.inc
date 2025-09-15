<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class StripEmptyCombine
{
    public function get(): array
    {
        return [
            'a' => false,
            'b' => 'b',
            'c' => true,
            'd' => $this->getNullable(),
            'class' => StripEmptyCombine::class,
            'opt' => [],
            'init' => false,
            'attrs' => [
                'x' => 'x',
                'class' => 'pick',
                'apply' => 'true',
                'head' => $this->trans('foo'),
            ],
        ];
    }

    private function getNullable(): ?string
    {
        return null;
    }

    function trans(): string
    {
        return 'foo';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class StripEmptyCombine
{
    /**
     * @return array<string, string|bool|mixed[]|array<string, string>|null>
     */
    public function get(): array
    {
        return [
            'a' => false,
            'b' => 'b',
            'c' => true,
            'd' => $this->getNullable(),
            'class' => StripEmptyCombine::class,
            'opt' => [],
            'init' => false,
            'attrs' => [
                'x' => 'x',
                'class' => 'pick',
                'apply' => 'true',
                'head' => $this->trans('foo'),
            ],
        ];
    }

    private function getNullable(): ?string
    {
        return null;
    }

    function trans(): string
    {
        return 'foo';
    }
}

?>
