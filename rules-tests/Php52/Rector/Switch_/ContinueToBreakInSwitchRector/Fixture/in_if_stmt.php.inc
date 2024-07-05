<?php

class InIfStmt
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
                                continue;
                            }
                        }
                    } else {
                        // some code
                    }

                  	continue;
                }
                case ($tableType == 'type2'):
                {
                    // some code
                    break;
                }
            }
        }
    }
}

?>
-----
<?php

class InIfStmt
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
                                break;
                            }
                        }
                    } else {
                        // some code
                    }

                  	break;
                }
                case ($tableType == 'type2'):
                {
                    // some code
                    break;
                }
            }
        }
    }
}

?>
