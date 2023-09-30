<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CopySwitchComment
{
    public function run()
    {
        // some comment here
        switch ($this->lexer->lookahead['type']) {
            case Lexer::T_DELETE:
                $statement = $this->DeleteStatement();
                break;

            default:
                $statement = $this->syntaxError('SELECT, UPDATE or DELETE');
                break;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class CopySwitchComment
{
    public function run()
    {
        // some comment here
        $statement = match ($this->lexer->lookahead['type']) {
            Lexer::T_DELETE => $this->DeleteStatement(),
            default => $this->syntaxError('SELECT, UPDATE or DELETE'),
        };
    }
}

?>
