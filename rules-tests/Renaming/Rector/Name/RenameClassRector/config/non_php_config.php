<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassRector::class)
        ->configure([
            OldClass::class => NewClass::class,
            // Laravel
            'Session' => 'Illuminate\Support\Facades\Session',
            'Form' => 'Collective\Html\FormFacade',
            'Html' => 'Collective\Html\HtmlFacade',
        ]);
};
