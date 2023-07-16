<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

final class DemoFile
{
    public function run( $arg )
    {
		$arg = preg_replace_callback(
			'/([a-z])?([a-z])([a-z]*)/',
			static function ( $match ) {
				$replace = '';
				if ( !empty( $match[1] ) ) {
					$replace = 'abc';
				}
				$replace .= $match[2];
				if ( !empty( $match[3] ) ) {
					$replace .= 'abc';
				}
				return $replace;
			},
			$arg
		);

		return $arg;
    }
}

?>
