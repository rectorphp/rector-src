<?php

namespace Rector\Tests\Php80\Rector\FuncCall\TokenGetAllToObjectRector\Fixture;

final class NotArrayFirst2
{
    public function run()
    {
        $code = '<?php echo 1;';

        $tokens = token_get_all($code);
        $this->tokens = [];
        foreach ($tokens as $token) {
            if (!is_array($token) || ($token[0] !== T_WHITESPACE && $token[0] !== T_INLINE_HTML)) {
                $this->tokens = $token;
            }
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\TokenGetAllToObjectRector\Fixture;

final class NotArrayFirst2
{
    public function run()
    {
        $code = '<?php echo 1;';

        $tokens = \PhpToken::tokenize($code);
        $this->tokens = [];
        foreach ($tokens as $token) {
            if ($token[0] !== T_WHITESPACE && $token[0] !== T_INLINE_HTML) {
                $this->tokens = $token->text;
            }
        }
    }
}

?>
