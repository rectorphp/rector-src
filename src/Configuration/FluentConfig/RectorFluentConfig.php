<?php

namespace Rector\Core\Configuration\FluentConfig;

// @todo an idea

return RectorFluentConfig::make()
    ->rename()
        ->property()
            ->onClass('SomeClass')
                ->fromTo('oldPropety', 'newProperty')
    ->build();
