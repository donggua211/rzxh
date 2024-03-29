<?php
set_time_limit(0);

include('multi_curl.class.include.php');

//配置变量
$crontab_url = 'http://localhost/configer/cron/room/';

$cache_room_filename = dirname(dirname(__FILE__)).'/application/cache/room_cache.php';
if( file_exists( $cache_room_filename ) )
{
	include_once( $cache_room_filename );
}

if(!isset($rooms))
	die('无法获取room caches');

if(empty($rooms))
	die('room caches 数据为空');
	
/*
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
*/

foreach($rooms as $room_id => $room_num)
{
	$urls[] = $crontab_url.$room_id.'/'.$room_num;
}


/*
 * CURL Mode:
 * START
 */
$m = new Http_MultiRequest();
$m->setUrls($urls);

// parallel fetch（并行抓取）:
$data = $m->exec();


//serial fetch（串行抓取）:
/*
foreach ($urls as $url)
{
    $data[] = $m->execOne($url);
}
/*
 * CURL Mode:
 * END
 */

 
/*
 * filet get content Mode:
 * START
 *
foreach ($urls as $url)
{
    $data[] = file_get_contents($url);
}
/*
 * filet get content Mode:
 * END
 */

print_r($data);