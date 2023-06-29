<?php

$rs = mysql_query($qry);
mysql_query($upsql) or die(mysql_error());

$result = mysql_query($sql);