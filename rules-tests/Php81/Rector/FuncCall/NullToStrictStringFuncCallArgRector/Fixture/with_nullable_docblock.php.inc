<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class WithNullableDocblock
{
   /**
     * @var string|null
     */
    public $importedId;

    /**
     * @param string|null $importedId
     */
    public function setImportedId($importedId) {
        if (strlen($this->importedId) > 0) {
            $this->importedId = $importedId;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class WithNullableDocblock
{
   /**
     * @var string|null
     */
    public $importedId;

    /**
     * @param string|null $importedId
     */
    public function setImportedId($importedId) {
        if (strlen((string) $this->importedId) > 0) {
            $this->importedId = $importedId;
        }
    }
}

?>
