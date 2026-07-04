<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\FormView;

final class RecursiveFormView
{
    public function setFormTheme(FormView $formView): void
    {
        $findThemes = function ($formView) use (&$findThemes): void {
            $child = $formView['child'];
            $findThemes($child);
        };

        $findThemes($formView);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\FormView;

final class RecursiveFormView
{
    public function setFormTheme(FormView $formView): void
    {
        $findThemes = function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source\FormView $formView) use (&$findThemes): void {
            $child = $formView['child'];
            $findThemes($child);
        };

        $findThemes($formView);
    }
}

?>
