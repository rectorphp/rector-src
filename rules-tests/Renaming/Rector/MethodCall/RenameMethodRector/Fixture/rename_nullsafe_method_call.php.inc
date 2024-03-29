<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

class RenameNullSafeMethodCall
{
    private function createHtml()
    {
        $html = new \Nette\Utils\Html();
        $html?->add('someContent');

        $anotherHtml = $html;
        $anotherHtml?->add('someContent');
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

class RenameNullSafeMethodCall
{
    private function createHtml()
    {
        $html = new \Nette\Utils\Html();
        $html?->addHtml('someContent');

        $anotherHtml = $html;
        $anotherHtml?->addHtml('someContent');
    }
}

?>
