<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class NewArrayReplace
{
    public function getImagePaths($identifier = 'detail', $size = null)
    {
        $imagePaths = [];

        if ($size) {
          $imagePaths = $this->getFilePaths('/' . $identifier . '_' . $size . '.jpg/');
        } else {
           $imagePaths = $this->getFilePaths('/' . $identifier . '.jpg/');
        }

        return $imagePaths;
    }

    private function getFilePaths(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class NewArrayReplace
{
    /**
     * @return mixed[]
     */
    public function getImagePaths($identifier = 'detail', $size = null): array
    {
        $imagePaths = [];

        if ($size) {
          $imagePaths = $this->getFilePaths('/' . $identifier . '_' . $size . '.jpg/');
        } else {
           $imagePaths = $this->getFilePaths('/' . $identifier . '.jpg/');
        }

        return $imagePaths;
    }

    private function getFilePaths(): array
    {
        return [];
    }
}

?>
