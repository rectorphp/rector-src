<?php

trait TitleTrait
{
    public function getTitle()
    {
        if ($this->title === null) {
            return null;
        }
        
        return trim($this->title);
    }
}

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class DemoFile
{
    private ?string $title;
    
    use TitleTrait;
}

?>
