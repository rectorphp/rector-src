<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function emptyBranch()
{
	// If there was a font color or size change, change the font tag now.
	if(@ereg("(\\\cf[0-9])||(\\\fs[0-9][0-9])", $tags)) {
		$html .= '</font><font size="'.$size.'" color="'.$color.'">';
	}
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\Fixture;

function emptyBranch()
{
	// If there was a font color or size change, change the font tag now.
	if(@preg_match("#(\\\\cf[0-9])||(\\\\fs[0-9][0-9])#m", $tags)) {
		$html .= '</font><font size="'.$size.'" color="'.$color.'">';
	}
}

?>
