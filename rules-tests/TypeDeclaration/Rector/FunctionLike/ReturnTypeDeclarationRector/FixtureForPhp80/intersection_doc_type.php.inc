<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp80;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\FirstInterface;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\SecondInterface;

final class IntersectionDocType
{
    /**
     * @param FirstInterface&SecondInterface $value
     */
    public function get($value)
    {
        return $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp80;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\FirstInterface;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\SecondInterface;

final class IntersectionDocType
{
    /**
     * @param FirstInterface&SecondInterface $value
     * @return \Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\FirstInterface&\Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\SourceForPhp80\SecondInterface
     */
    public function get($value)
    {
        return $value;
    }
}

?>
