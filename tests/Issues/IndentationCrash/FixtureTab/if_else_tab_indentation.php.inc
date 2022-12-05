<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\FixtureTab;

final class IfElseTabIndentation
{
	/**
	 * @param string[]|string $args
	 *
	 * @return void
	 */
	public function run( $args ) {
		if ( is_array( $args ) ) {
			foreach ( $args as $data ) {
				echo $data;
			}
		} else {
			echo $args;
		}
	}
}