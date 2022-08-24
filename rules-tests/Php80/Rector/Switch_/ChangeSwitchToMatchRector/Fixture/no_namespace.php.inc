<?php

$data = 'value';

switch ($data) {
        case Lexer::T_DELETE:
            $statement = $this->DeleteStatement();
            break;

        default:
            $statement = $this->syntaxError('SELECT, UPDATE or DELETE');
            break;
}

echo $statement;

?>
-----
<?php

$data = 'value';

$statement = match ($data) {
    Lexer::T_DELETE => $this->DeleteStatement(),
    default => $this->syntaxError('SELECT, UPDATE or DELETE'),
};

echo $statement;

?>
