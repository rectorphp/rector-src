<?php

declare(strict_types=1);

use Rector\Php56\Rector\FuncCall\PowToExpRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PowToExpRector::class);

    $services->set(RenameFunctionRector::class)
        ->configure([
            'mcrypt_generic_end' => 'mcrypt_generic_deinit',
            'set_socket_blocking' => 'stream_set_blocking',
            'ocibindbyname' => 'oci_bind_by_name',
            'ocicancel' => 'oci_cancel',
            'ocicolumnisnull' => 'oci_field_is_null',
            'ocicolumnname' => 'oci_field_name',
            'ocicolumnprecision' => 'oci_field_precision',
            'ocicolumnscale' => 'oci_field_scale',
            'ocicolumnsize' => 'oci_field_size',
            'ocicolumntype' => 'oci_field_type',
            'ocicolumntyperaw' => 'oci_field_type_raw',
            'ocicommit' => 'oci_commit',
            'ocidefinebyname' => 'oci_define_by_name',
            'ocierror' => 'oci_error',
            'ociexecute' => 'oci_execute',
            'ocifetch' => 'oci_fetch',
            'ocifetchstatement' => 'oci_fetch_all',
            'ocifreecursor' => 'oci_free_statement',
            'ocifreestatement' => 'oci_free_statement',
            'ociinternaldebug' => 'oci_internal_debug',
            'ocilogoff' => 'oci_close',
            'ocilogon' => 'oci_connect',
            'ocinewcollection' => 'oci_new_collection',
            'ocinewcursor' => 'oci_new_cursor',
            'ocinewdescriptor' => 'oci_new_descriptor',
            'ocinlogon' => 'oci_new_connect',
            'ocinumcols' => 'oci_num_fields',
            'ociparse' => 'oci_parse',
            'ociplogon' => 'oci_pconnect',
            'ociresult' => 'oci_result',
            'ocirollback' => 'oci_rollback',
            'ocirowcount' => 'oci_num_rows',
            'ociserverversion' => 'oci_server_version',
            'ocisetprefetch' => 'oci_set_prefetch',
            'ocistatementtype' => 'oci_statement_type',
        ]);

    # inspired by level in psalm - https://github.com/vimeo/psalm/blob/82e0bcafac723fdf5007a31a7ae74af1736c9f6f/tests/FileManipulationTest.php#L1063
    $services->set(AddDefaultValueForUndefinedVariableRector::class);
};
