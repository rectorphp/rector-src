<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class SkipNoDefaultNextIndirectInReturnExpr
{
    public function price_action_by_server_name($price, $server)
    {
        $factor = 1.0;

        switch ($server->name) {
            case 'a':
                $factor = 0.95;
                break;
            case 'b':
            case 'c':
                $factor = 0.8;
                break;
        }

        return $price * $factor;
    }
}
