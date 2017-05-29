<?php
$str_id = mb_substr($keyword , 4 , strlen($keyword)-4);
$id = intval($str_id, 10);
include 'init.php';
try {
  	$dbh = new PDO($dsn, $user, $pass);
  	$arr = $dbh->query("SELECT * from wp_posts where ID='{$id}' AND post_status='publish' AND post_type='post'");
  	$postApprovalCount_arr = $dbh->query("SELECT * from wp_postmeta where post_id='{$id}' AND meta_key='postApprovalCount'");
    //$postApprovalCount_arr = $postApprovalCount_arr->fetch(PDO::FETCH_NUM);
  	//$postApprovalCount = $postApprovalCount_arr[0]["postApprovalCount"];
  	//foreach ($postApprovalCount_arr as $row) {
    //	global $postApprovalCount;
    // 	$postApprovalCount = $row["postApprovalCount"];
    //}
  	if ($arr->rowCount())
    {
      	foreach ($arr as $row) {
          	//$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "标题：");
          	//echo $resultStr;
          	$content = preg_replace('/[\r\n]+/', "\n", strip_tags($row["post_content"]));
          	if (strlen($content) > 2048)
              	$content = "文本过长，未予以显示";
          	//global $postApprovalCount;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "标题：".$row["post_title"]."\n发表日期：".$row["post_date"]."\n作者ID：".($row["post_author"]+100000)./*"\n点赞：+".$postApprovalCount.*/"\n链接：".$row["guid"]."\n内容：".$content);
            echo $resultStr;
        }
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