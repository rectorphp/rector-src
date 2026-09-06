<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector\Fixture;

class SkipCommentedCodeBetween
{
    public function run($ch)
    {
        $pdf = curl_exec($ch);

        // $url = "https://example.com" . "?id=" . $id;
        // curl_setopt($ch, CURLOPT_URL, $url);
        // $pdf = curl_exec($ch);
        // curl_close($ch);

        return $pdf;
    }
}
