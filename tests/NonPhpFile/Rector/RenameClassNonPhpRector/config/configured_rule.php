<?php

declare(strict_types=1);

use Rector\Core\NonPhpFile\Rector\RenameClassNonPhpRector;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassNonPhpRector::class)
        ->configure([
            'Session' => 'Illuminate\Support\Facades\Session',
            OldClass::class => NewClass::class,
            // Laravel
            'Form' => 'Collective\Html\FormFacade',
            'Html' => 'Collective\Html\HtmlFacade',
        ]);
};
