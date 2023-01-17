<?php

namespace Rector\Tests\Php80\Rector\FuncCall\TokenGetAllToObjectRector\Fixture;

final class TernaryIf
{
    public function run()
    {
        $tokens = token_get_all('<?php ' . implode('', array_slice($fileContent, $start, $end - $start)));

        foreach ($tokens as $i => $token) {
            $readableToken = is_array($token) ? $token[1] : $token;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\TokenGetAllToObjectRector\Fixture;

final class TernaryIf
{
    public function run()
    {
        $tokens = \PhpToken::tokenize('<?php ' . implode('', array_slice($fileContent, $start, $end - $start)));

        foreach ($tokens as $i => $token) {
            $readableToken = $token->text;
        }
    }
}

?>
