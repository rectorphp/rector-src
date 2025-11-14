<?php

$output = "a  b\tc";
$preg_match_matches = NULL;
$preg_match_all_matches = NULL;
$preg_replace_callback_array_count = NULL;
var_dump([
    'preg_filter' => preg_filter('/[a-z]+/', 'X', $output, NULL),
    'preg_grep' => preg_grep('/^[a-z]+/', [$output, 'x'], NULL),
    'preg_match' => preg_match('/^[a-z]+/', $output, $preg_match_matches, NULL, NULL),
    'preg_match $matches' => $preg_match_matches,
    'preg_match_all' => preg_match_all('/^[a-z]+/', $output, $preg_match_all_matches, NULL, NULL),
    'preg_match_all $matches' => $preg_match_all_matches,
    'preg_replace' => preg_replace('/[a-z]+/', 'X', $output, NULL),
    'preg_replace_callback' => preg_replace_callback('/[a-z]+/', function($word) {
        return $word;
    }, $output, NULL),
    'preg_replace_callback_array' => preg_replace_callback_array([
        '~[a]+~i' => function ($match) {
            return 'X';
        },
        '~[b]+~i' => function ($match) {
            return 'Y';
        },
    ], $output, NULL, $preg_replace_callback_array_count, NULL),
    'preg_split' => preg_split('/\s/', $output, NULL, PREG_SPLIT_NO_EMPTY),
]);

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector\Fixture;

$output = "a  b\tc";
$preg_match_matches = NULL;
$preg_match_all_matches = NULL;
$preg_replace_callback_array_count = NULL;
var_dump([
    'preg_filter' => preg_filter('/[a-z]+/', 'X', $output, 0),
    'preg_grep' => preg_grep('/^[a-z]+/', [$output, 'x'], 0),
    'preg_match' => preg_match('/^[a-z]+/', $output, $preg_match_matches, 0, 0),
    'preg_match $matches' => $preg_match_matches,
    'preg_match_all' => preg_match_all('/^[a-z]+/', $output, $preg_match_all_matches, 0, 0),
    'preg_match_all $matches' => $preg_match_all_matches,
    'preg_replace' => preg_replace('/[a-z]+/', 'X', $output, 0),
    'preg_replace_callback' => preg_replace_callback('/[a-z]+/', function($word) {
        return $word;
    }, $output, 0),
    'preg_replace_callback_array' => preg_replace_callback_array([
        '~[a]+~i' => function ($match) {
            return 'X';
        },
        '~[b]+~i' => function ($match) {
            return 'Y';
        }
    ], $output, 0, $preg_replace_callback_array_count, 0),
    'preg_split' => preg_split('/\s/', $output, 0, PREG_SPLIT_NO_EMPTY),
]);

?>
