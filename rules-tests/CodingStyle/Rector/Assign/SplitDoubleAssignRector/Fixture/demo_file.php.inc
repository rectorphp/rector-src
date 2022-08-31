<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

final class DemoFile
{
	public function run( $x )
	{
		$a = get_a();

		if ( strpos( $a, ', ' ) !== false ) {
			$b = $x . ' hello';
		} else {
			$as = explode( ':', $a );
			if ( isset( $as[1] ) ) {
				$b = $x . ' ' . $as[1];
			} else {
				$b = $x . ' ' . $a;
			}
		}

		return $b;
	}
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

final class DemoFile
{
	public function run( $x )
	{
		$a = get_a();

		if ( strpos( $a, ', ' ) !== false ) {
			return $x . ' hello';
		} else {
			$as = explode( ':', $a );
			return isset( $as[1] ) ? $x . ' ' . $as[1] : $x . ' ' . $a;
		}
	}
}

?>
