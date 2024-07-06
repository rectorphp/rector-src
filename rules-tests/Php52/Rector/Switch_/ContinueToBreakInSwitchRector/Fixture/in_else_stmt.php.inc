<?php

class InElseStmt
{
    function processNotification(array $notifications, string $tableType)
    {
        foreach ($notifications as $notification) {
            switch ($tableType) {
                case ($tableType == 'type1'):
                {
                    if (rand(0,1)) {

                        if (rand(0,1)) {

                            if (rand(0,1)) {

                            }
                        }
                    } else {
                        if (rand(0,1)) {

                            if (rand(0,1)) {
                         		continue;
                            }
                        }
                    }
                }
                case ($tableType == 'type2'):
                {
                }
            }
        }
    }
}

?>
-----
<?php

class InElseStmt
{
    function processNotification(array $notifications, string $tableType)
    {
        foreach ($notifications as $notification) {
            switch ($tableType) {
                case ($tableType == 'type1'):
                {
                    if (rand(0,1)) {

                        if (rand(0,1)) {

                            if (rand(0,1)) {

                            }
                        }
                    } else {
                        if (rand(0,1)) {

                            if (rand(0,1)) {
                         		break;
                            }
                        }
                    }
                }
                case ($tableType == 'type2'):
                {
                }
            }
        }
    }
}

?>
