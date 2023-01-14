<?php
echo 'test';
?>
<div>
    <?php
        for ($i = 5; $i <= count($items); $i++) {
            echo $items[$i];
        }
    ?>
</div>
-----
<?php

echo 'test';
?>
<div>
    <?php 
$itemsCount = count($items);
for ($i = 5; $i <= $itemsCount; $i++) {
            echo $items[$i];
        }
?>
</div>
