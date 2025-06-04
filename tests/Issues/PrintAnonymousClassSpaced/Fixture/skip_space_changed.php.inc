<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\PrintAnonymousClassSpaced;

$stringable = new
    /**
     * @implements \Stringeable
     */
    class implements \Stringable {
   	    #[\Override]
     	public function __toString(): string
    	{
        	return '';
    	}
	};
