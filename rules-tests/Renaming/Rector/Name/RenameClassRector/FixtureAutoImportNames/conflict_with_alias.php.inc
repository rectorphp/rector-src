<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Project\PedBook\Auto\Obj\TableDisease as ObjTableDisease;
use Project\PedBook\Auto\Obj\TableMedicine as ObjTableMedicine;
use Project\PedBook\Auto\Stc\TableMedicine;
use Project\PedBook\Manual\Cln\ComponentAppMedicineListing_Model_Medicine;
use Special\Architecture\Manual\Abs\ToolExact_ComponentModel;

class ComponentTeste_Model extends ToolExact_ComponentModel
{
	private function setResults(): void
	{
		$Select = TableMedicine::select();

		$this->Results = new ComponentAppMedicineListing_Model_Medicine(
			$Rows->map(function (ObjTableMedicine $Row) {
				return $Row;
			})
		);
	}
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNames;

use Project\PedBook\Auto\Stc\TableDisease;
use Project\PedBook\Manual\Cln\ComponentAppMedicineListing_Model_Medicine;
use Special\Architecture\Manual\Abs\ToolExact_ComponentModel;

class ComponentTeste_Model extends ToolExact_ComponentModel
{
	private function setResults(): void
	{
		$Select = TableDisease::select();

		$this->Results = new ComponentAppMedicineListing_Model_Medicine(
			$Rows->map(function (\Project\PedBook\Auto\Obj\TableDisease $Row) {
				return $Row;
			})
		);
	}
}

?>
