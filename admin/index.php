<!DOCTYPE>
<html>
  <head>
    <title>微信公众号台管理</title>
    <meta charset="utf-8">
  </head>
  <body>
    <?php
      session_start();
      if (isset($_SESSION['isLogin']) && ($_SESSION['isLogin'] == "yes")) {
	    include 'header.php';
        
      } else {
        header("location: http://youweb/login.php");
      }
      echo "<p>".$_SESSION['id']."---".$_SESSION['email']."---".$_SESSION['isLogin']."</p>";
      echo "<p>sdfdsgds</p>";
    ?>
  </body>
</html>