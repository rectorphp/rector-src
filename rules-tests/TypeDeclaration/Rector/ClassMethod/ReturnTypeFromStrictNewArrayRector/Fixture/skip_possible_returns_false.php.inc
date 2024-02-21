<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class SkipPossibleReturnFalse
{
    public function couldUpdate(\stdClass $pricesItem, AbstractDocument $document)
    {
        $return = [];
        $couldUpdate = false;

        foreach ($document as $compareKey => $compareValue) {

            $return[$compareKey] = [
                $compareValue,
                $pricesItem->$compareKey === null
                    ? 'NULL'
                    : (float) $pricesItem->$compareKey,
            ];

            $isSame = bccomp(
                (string) ($pricesItem->$compareKey),
                (string) ($compareValue),
                2
            );

            if (!empty($isSame) && !$couldUpdate) {
                $couldUpdate = true;
            }
        }

        if ($couldUpdate) {
            return $return;
        }

        return false;
    }

}