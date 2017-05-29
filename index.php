<?php

traceHttp();

define("TOKEN", "yourtoken");
$wechatObj = new wechatCallbackapiTest();
$output = "";
$down = "";
$postApprovalCount = 0;
if (!(isset($_GET['echostr']) || isset($_GET['signature']) || isset($_GET['timestamp']) || isset($_GET['nonce']) || isset($GLOBALS["HTTP_RAW_POST_DATA"]))) {
  	include 'login.php';
}
else if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()  //回复消息
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];  //获得消息

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);  //用户发送的消息
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if (!empty($keyword))
            {
              	$type = substr($keyword , 0 , 4);
              	if ($type == "user")
                {
                  	include 'userInfo.php';
                    
                }
              	else if ($type == "post")
                {
                  	include 'postInfo.php';
                }
              	else if ($type == "find")
                {
                  	include 'findPost.php';
                }
              	else if ($type == "down")
                {
                  	include 'download.php';
                }
              	else if ($type == "bind")
                {
                  	include 'bindWechat.php';
                }
              	else if (substr($keyword , 0 , 7) == "relieve")
                {
                 	include 'relieveWechat.php';
                }
              	else if ($type == "mess")
                {
                  	$word = mb_substr($keyword , 4 , strlen($keyword)-4);
                  	$time=date("Y-m-d H:i:s");
                 	$file_path="issue.txt";
                    if(file_exists($file_path)){
                    	error_log($time." "."来自 ".$toUsername." 的留言：".$word."\r\n", 3, $file_path);
                    }
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "感谢留言");
                  	echo $resultStr;
                }
              	else
                {
                  	$msgType = "text";
                  	$contentStr = "1.输入 post+文章ID 获取文章内容，如post13457\n2.输入 down+文章ID 获取文章资源下载链接，如down12357;\n3.输入 user+用户ID 获取用户信息，如user100001;\n4.输入username+昵称获取用户信息,如username小明;\n5.输入 find+查找内容 查找文章ID，如find4月合集;\n6.输入 mess+留言内容 进行留言，如mess你好;\n7.输入 bind邮箱+空格+密码 将微信与网站账户绑定，如bind123456@qq.com password;\n8.输入 relieve+邮箱+空格+密码 解绑微信，如relieve123456@qq.com password;\n更多命令正在缓慢开发中...";
                  	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                  	echo $resultStr;
                }
            } 
          	else 
            {
              	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, "text", "please input something...");
              	echo $resultStr;
            }
            
        }else{
            echo "";
            exit;
        }
    }
}

function traceHttp()
{
    logger("\n\nREMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].(strstr($_SERVER["REMOTE_ADDR"],'101.226')? " FROM WeiXin": "Unknown IP"));
    logger("QUERY_STRING:".$_SERVER["QUERY_STRING"]);
}
function logger($log_content)
{
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
        sae_set_display_errors(false);
        sae_debug($log_content);
        sae_set_display_errors(true);
    }else{ //LOCAL
        $max_size = 500000;
        $log_filename = "log.xml";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content."\r\n", FILE_APPEND);
    }
}

?>