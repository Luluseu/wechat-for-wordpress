<?php
include 'checkPass.php';
$str = mb_substr($keyword , 7 , strlen($keyword)-7);

if (strpos($str,' ') === false || strpos($str,'@') === false) {
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "输入格式有误");
    echo $resultStr;
} else {
  	$email_pass = explode(" ", $str);
    $email = $email_pass[0];
    $pass = $email_pass[1];
  	$userID = -1;

    $isConform = check($email, $pass, $userID);

    if ($isConform == true && $userID != -1) {
      	include 'init.php';
      	try {
  			$dbh = new PDO($dsn, $user, $pass);
          	$arr_find = $dbh->query("select * from wp_usermeta where user_id='{$userID}' AND meta_key='wechatUser'");
          	if (($arr_find->rowCount()) == 1) {
  				$arr = $dbh->query("delete from wp_usermeta where user_id='{$userID}' AND meta_key='wechatUser'");
          		$dbh = null;
      			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "解绑成功");
        		echo $resultStr;
            } else {
             	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "你还未绑定过");
        		echo $resultStr;
            }
        } catch (PDOException $e) {
  			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "解绑失败");
  			echo $resultStr;
		}
    } else {
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "验证失败，邮箱或密码输入错误");
        echo $resultStr;
    }
}