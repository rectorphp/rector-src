<?php
class SwitchScopeCheck
{
    public function getNumber (array $numbers, int $number): int {
        $action = $_GET['action'] ?? 'something';
        switch ($action) {
            default:
                $sum = 0;
                foreach ($numbers as $temp) {
                    $sum += $temp;
                }
                break;
        }
        return $sum % $number;
    }
}
?>
