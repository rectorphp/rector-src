<?php
function test_callback2($newNode) {
  return new \PhpParser\Node\Stmt\Expression($newNode);
  // var_dump($output);
  ?>
  <div class="wrap">
    <img src="<?= escape('hi there'); ?>">
  </div>
  <?php
}