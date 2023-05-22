<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedVariableElseToTernary\Fixture;

class Fixture
{
    protected function _flatten(int $red = 255, int $green = 255, int $blue = 255)
    {
        $srcImg = $this->createImage();

        if (function_exists('imagecreatetruecolor')) {
            $create = 'imagecreatetruecolor';
            $copy   = 'imagecopyresampled';
        } else {
            $create = 'imagecreate';
            $copy   = 'imagecopyresized';
        }
        $dest = $create($this->width, $this->height);

        $matte = imagecolorallocate($dest, $red, $green, $blue);

        imagefilledrectangle($dest, 0, 0, $this->width, $this->height, $matte);
        imagecopy($dest, $srcImg, 0, 0, 0, 0, $this->width, $this->height);

        // Kill the file handles
        imagedestroy($srcImg);

        $this->resource = $dest;

        return $this;
    }

}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\RemoveUnusedVariableElseToTernary\Fixture;

class Fixture
{
    protected function _flatten(int $red = 255, int $green = 255, int $blue = 255)
    {
        $srcImg = $this->createImage();

        if (function_exists('imagecreatetruecolor')) {
            $create = 'imagecreatetruecolor';
        } else {
            $create = 'imagecreate';
        }
        $dest = $create($this->width, $this->height);

        $matte = imagecolorallocate($dest, $red, $green, $blue);

        imagefilledrectangle($dest, 0, 0, $this->width, $this->height, $matte);
        imagecopy($dest, $srcImg, 0, 0, 0, 0, $this->width, $this->height);

        // Kill the file handles
        imagedestroy($srcImg);

        $this->resource = $dest;

        return $this;
    }

}

?>
