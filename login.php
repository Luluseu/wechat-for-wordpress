<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>登陆</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="layui/css/layui.css"  media="all">
  <link rel="stylesheet" href="assert/css/login.css"  media="all">
</head>
<body>
          

              

<div id="login">
  <?php
    if (isset($_POST['username']) && isset($_POST['password'])) {
      //验证密码 
      include 'checkPass.php';
      $userID = -1;
      $isConform = check($_POST['username'], $_POST['password'], $userID);
      if ($isConform == true) {
        //echo "<p id='errorMessage'>".$_POST['username']."----".$_POST['password']."成果</p>";
        session_start();
        $_SESSION["id"] = $userID;
        $_SESSION["email"] = $_POST['username'];
        $_SESSION["isLogin"] = "yes";
        header("location: http://wechat.oomoe.moe/admin/");
      } else {
        echo "<p id='errorMessage'>邮箱或密码错误</p>";
      }
    } else {
      echo "<p id='errorMessage'>请输入完整信息</p>";
    }
  
  ?>
  <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>登陆</legend>
  </fieldset>

  <form class="layui-form layui-form-pane" action="login.php" method="post">
    <div class="layui-form-item">
      <label class="layui-form-label">用户名</label>
      <div class="layui-input-block">
        <input type="text" name="username" autocomplete="off" placeholder="请输入用户名" class="layui-input">
      </div>
    </div>

    <div class="layui-form-item">
      <label class="layui-form-label">密码</label>
      <div class="layui-input-block">
        <input type="password" name="password" placeholder="请输入密码" autocomplete="off" class="layui-input" onblur="checkIsInput(this);">
      </div>
      <div class="layui-form-mid layui-word-aux" id="tips">请务必填写用户名</div>
    </div>

    <div class="layui-form-item">
      <button class="layui-btn" lay-submit="" lay-filter="demo2">提交</button>
    </div>
  </form>
</div>
          
<script src="layui/layui.js" charset="utf-8"></script>

</body>
</html>