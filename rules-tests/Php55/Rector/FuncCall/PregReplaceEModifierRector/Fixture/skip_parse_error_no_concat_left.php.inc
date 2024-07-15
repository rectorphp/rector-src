<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

class SkipParseErrorNoConcatLeft
{
    public function run($contents)
    {
        $source_content = preg_replace($search.'e', "'"
            . $this->_quote_replace($this->left_delimiter) . 'php'
            . "' . str_repeat(\"\n\", substr_count('\\0', \"\n\")) .'"
            . $this->_quote_replace($this->right_delimiter)
            . "'"
            , $source_content);
    }
}
