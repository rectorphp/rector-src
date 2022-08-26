<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * @template T
 */
class TypedResponse
{
    /**
     * @var T
     */
    public $data;
}

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

final class DemoFile
{
    /**
     * @template T
     * @param T $data
     * @return TypedResponse<T>
     */
    public function createResponse($data): TypedResponse
    {
        /** @var TypedResponse<T> */
        $response = new TypedResponse();
        $response->data = $data;
        return $response;
    }
    
    public function run()
    {
        return $this->createResponse('qwe');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

/**
 * @template T
 */
class TypedResponse
{
    /**
     * @var T
     */
    public $data;
}

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

final class DemoFile
{
    /**
     * @template T
     * @param T $data
     * @return TypedResponse<T>
     */
    public function createResponse($data): TypedResponse
    {
        $response = new TypedResponse();
        $response->data = $data;
        return $response;
    }
    
    /**
     * @return \Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\TypedResponse<string>
     */
    public function run(): \Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture\TypedResponse
    {
        return $this->createResponse('qwe');
    }
}

?>
