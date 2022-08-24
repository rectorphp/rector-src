<?php

$data = 'value';

switch ($data) {
        case Lexer::T_DELETE:
            $statement = deleteStatement();
            break;

        default:
            $statement = syntaxError('SELECT, UPDATE or DELETE');
            break;
}

echo $statement;

?>
-----
<?php

$data = 'value';

$statement = match ($data) {
    Lexer::T_DELETE => deleteStatement(),
    default => syntaxError('SELECT, UPDATE or DELETE'),
};

echo $statement;

?>
