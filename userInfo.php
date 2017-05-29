<?php
if (strlen($keyword) == 10)
{
    $str_id = mb_substr($keyword , 4 , 10);
    $id = intval($str_id, 10);
    $id = $id - 100000;
    include 'init.php';
    try {
      $dbh = new PDO($dsn, $user, $pass); 
      $arr = $dbh->query("SELECT * from wp_users where ID='{$id}'");
      $arr_meta = $dbh->query("SELECT * from wp_usermeta where user_id='{$id}' AND meta_key='customPointCount'");
      if ($arr->rowCount())
      {
        foreach ($arr as $row) {
          	foreach ($arr_meta as $row_meta) {
          		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "昵称：".$row["display_name"]."\nID：".($id+100000)."\n邮箱：".$row["user_email"]."\n注册日期：".$row["user_registered"]."\n积分：".$row_meta["meta_value"]);
          		echo $resultStr;
            }
        }
      }
      else
      {
          $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "未查询到此用户");
          echo $resultStr;
      }

      $dbh = null;
    } catch (PDOException $e) {
      $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "error");
      echo $resultStr;
  }
}
else if (mb_substr($keyword , 4, 4) == "name")
{
  	$name = mb_substr($keyword , 8 , strlen($keyword)+1);
  	include 'init.php';
  	try {
    	$dbh = new PDO($dsn, $user, $pass); 
    	$arr = $dbh->query("SELECT * from wp_users where display_name='{$name}'");
      	if ($arr->rowCount())
        {
         	 foreach ($arr as $row) {
               	 $userID = $row["user_nicename"] - 100000;
               	 $arr_meta = $dbh->query("SELECT * from wp_usermeta where user_id='{$userID}' AND meta_key='customPointCount'");
               	 foreach ($arr_meta as $row_meta) {
                 	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "昵称：".$name."\nID：".$row["user_nicename"]."\n邮箱：".$row["user_email"]."\n注册日期：".$row["user_registered"]."\n积分：".$row_meta["meta_value"]);
                 	echo $resultStr;
                 }
             }
        }
      	else
        {
          	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "未查询到此用户");
          	echo $resultStr; 
        }
      	$dbh = null;
    } catch (PDOException $e) {
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "error");
        echo $resultStr;
    }
      	
}
else
{
  $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "输入格式不正确");
  echo $resultStr;
}