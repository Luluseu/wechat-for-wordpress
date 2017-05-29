<?php
$str_id = mb_substr($keyword , 4 , strlen($keyword)-4);
$id = intval($str_id, 10);
include 'init.php';
try {
  	$dbh = new PDO($dsn, $user, $pass);
  	$arr = $dbh->query("SELECT * from wp_postmeta where post_id='{$id}' AND meta_key='customPostStorage'");
  	if ($arr->rowCount())
    {
      	global $down;
      	foreach ($arr as $row) { 
          	global $down;
          	$down = $row["meta_value"];
        }
      	$down_json = json_decode($down,TRUE);
      	$count_json = count($down_json);
      	$down = "";
      	for ($i = 0; $i < $count_json; $i++)
        {
          	$name = $down_json[$i]['name'];
          	$url = $down_json[$i]['url'];
          	$downloadPwd = $down_json[$i]['downloadPwd'];
          	$extractPwd = $down_json[$i]['extractPwd'];
          	$down = $down.$name."\n"."URL：".$url."\n";
          	if ($downloadPwd != "")
              	$down = $down."提取码：".$downloadPwd."\n";
          	if ($extractPwd != "")
              	$down = $down."解压密码：".$extractPwd."\n";
          	$down."\n\n";
        }
      
      	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $down);
      	echo $resultStr;
    }
    else
    {
      $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "未查询到此文章");
      echo $resultStr;
    }
  	$dbh = null;
} catch (PDOException $e) {
  	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "error");
  	echo $resultStr;
}