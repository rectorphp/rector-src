<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

final class WebUtil 
{   
 	  public static function radioList($name, $items = [], $selected, $class = "", $groupingCount = 0, $groupingClass = "", $required = false)
    {
               
    }
}

WebUtil::radioList("inPersonOrVirtual", $inPersonOrVirtualItems, $selected, "", 1, "col-sm-3 margin-bottom-10");
