<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray() {
        $HeaderArray = [];

        $HeaderArray[] = i::_('Lager');
        $HeaderArray[] = i::_('Art.Nr.');
        $HeaderArray[] = i::_('Kontenplankonto');
        $HeaderArray[] = i::_('Lieferant');
        $HeaderArray[] = i::_('Artikel');
        $HeaderArray[] = i::_('Preis');
        $HeaderArray[] = i::_('Menge');
        $HeaderArray[] = i::_('Einheit');
        $HeaderArray[] = i::_('Wert');
        $HeaderArray[] = i::_('Mwst');

        return $HeaderArray;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray(): array {
        $HeaderArray = [];

        $HeaderArray[] = i::_('Lager');
        $HeaderArray[] = i::_('Art.Nr.');
        $HeaderArray[] = i::_('Kontenplankonto');
        $HeaderArray[] = i::_('Lieferant');
        $HeaderArray[] = i::_('Artikel');
        $HeaderArray[] = i::_('Preis');
        $HeaderArray[] = i::_('Menge');
        $HeaderArray[] = i::_('Einheit');
        $HeaderArray[] = i::_('Wert');
        $HeaderArray[] = i::_('Mwst');

        return $HeaderArray;
    }
}

?>
