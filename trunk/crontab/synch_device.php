<?php
include('multi_curl.class.include.php');

$crontab_url = 'http://localhost/project/rzxh/tsinghua/trunk/configer/synch/room/';

$link = mysql_connect('localhost', 'root', '');
mysql_select_db('rzxh');

$query = "SELECT room_id from rzxh_room";
$result = mysql_query($query);

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	 $urls[] = $crontab_url.$line['room_id'];
}

mysql_free_result($result);
mysql_close($link);


$m = new Http_MultiRequest();
$m->setUrls($urls);

//parallel fetch（并行抓取）:
$data = $m->exec();


/*serial fetch（串行抓取）:
foreach ($urls as $url)
{
    $data[] = $m->execOne($url);
}
*/

print_r($data);