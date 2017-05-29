<?php
$word = mb_substr($keyword , 4 , strlen($keyword)-4);
$word = "%".$word."%";
//$output = "";
include 'init.php';
try {
  	$dbh = new PDO($dsn, $user, $pass);
  	$arr = $dbh->query("SELECT * from wp_posts where post_title like '{$word}' AND post_status='publish' AND post_type='post'");
    
  	global $output;
  	$output = $output."共找到".$arr->rowCount()."个结果"."\n";
  	if ($arr->rowCount())
    {
      	foreach ($arr as $row) {
          	global $output;
          	$output = $output."\n标题：".$row["post_title"]."\n发表日期：".$row["post_date"]."\n文章ID：".$row["ID"]."\n-------------------";
            
        }
      	if (strlen($output > 2000))
          	$output = "返回结果超过微信限制，请输入更精确地搜索内容";
      	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", $output."\n\ntip：输入 post+文章id 获得文章详细信息，如post13473\n输入 down+id 获得下载链接");
      	echo $resultStr;
    }
    else
    {
      $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "没有结果");
      echo $resultStr;
    }
  	$dbh = null;
} catch (PDOException $e) {
  	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "error");
  	echo $resultStr;
}