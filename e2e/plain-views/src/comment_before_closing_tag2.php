<div>
    <h4>
        List of users
    </h4>
    <ul>
        <?php foreach ($users as $user): ?>
        <li>
            <?php
            	echo $user['name'];
            	// echo "laskdjflaksdfjlsjf";
            ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
