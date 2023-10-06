<div class="test <?php if (rand(0,1)) echo 'hidden'; ?>"></div>
-----
<div class="test <?php if (rand(0,1)) {
    echo 'hidden';
} ?>"></div>
