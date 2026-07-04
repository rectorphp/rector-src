<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipRecursiveDimFetchClosure
{
    public function setFormTheme($form, $formView): void
    {
        $findThemes = function ($form, $formView) use (&$findThemes): void {
            foreach ($form as $name => $field) {
                $fieldView = $formView[$name];

                if ($field->count()) {
                    $findThemes($field, $fieldView);
                }
            }
        };

        $findThemes($form, $formView);
    }
}
