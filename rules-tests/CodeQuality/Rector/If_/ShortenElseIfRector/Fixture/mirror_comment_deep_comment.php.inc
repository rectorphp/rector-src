<?php
namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

final class MirrorCommentDeepComment
{
	public function run( int $arg, array $b_arg ) {
		if ( $arg > 5 ) {
			echo "a";
		} else {
			// above if comment
			if ( $a === 5 ) {
				// inside if comment
				foreach ( $b_arg as $element ) {
					// inside foreach comment
					echo $element;
				}
				echo "b";
			} else {
			 	// inside else comment
				echo "c";
			}
		}
	}
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ShortenElseIfRector\Fixture;

final class MirrorCommentDeepComment
{
	public function run( int $arg, array $b_arg ) {
		if ( $arg > 5 ) {
			echo "a";
		} elseif ( $a === 5 ) {
			// above if comment
			// inside if comment
			foreach ( $b_arg as $element ) {
				// inside foreach comment
				echo $element;
			}
			echo "b";
		} else {
			// inside else comment
			echo "c";
		}
	}
}

?>
