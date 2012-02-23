<?php
set_time_limit(0);

include('multi_curl.class.include.php');

//配置变量
$crontab_url = 'http://localhost/rzxh/configer/cron/room/';
$mysql_conf['server'] = 'localhost';
$mysql_conf['user'] = 'root';
$mysql_conf['password'] = '';
$mysql_conf['db'] = 'rzxh';


$link = mysql_connect($mysql_conf['server'],$mysql_conf['user'], $mysql_conf['password']);
mysql_select_db($mysql_conf['db']);

$query = "SELECT room_id from rzxh_room";
$result = mysql_query($query);

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	 $urls[] = $crontab_url.$line['room_id'];
}

mysql_free_result($result);
mysql_close($link);


$m = new Http_MultiRequest();
$m->setUrls($urls);

// parallel fetch（并行抓取）:
$data = $m->exec();


/*serial fetch（串行抓取）:
foreach ($urls as $url)
{
    $data[] = $m->execOne($url);
}
*/

print_r($data);