<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login | Hospital Management</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
if((isset($_GET['pid']))&&($_GET['pid']==01))
{
	require("include/dbinfo.php");
	
	$username=$_POST['login'];
	$query="select * from Passwords where Username='$username'";
        $result= mysqli_query($con, $query);
	if($row=mysqli_fetch_array($result))
	{
		if((!strcmp($row['Username'],$_POST['login']))&&(!strcmp($row['Password'],$_POST['password'])))
		{
			$username=$_POST['login'];
                        
			$query="select * from session where username='$username'";
                        $result= mysqli_query($con, $query);
			$row=mysqli_fetch_array($result);
			if(strcmp($row['Username'],""))
			{
				echo "<script type=\"text/javascript\">alert(\"Multiple logins not allowed. Access Denied.\")</script>";
			}
			else
			{
				session_start();
				$sessionid=$_COOKIE['PHPSESSID'];
				$_SESSION['username']=$username;
				mysqli_query($con,"insert into session value ('$username','$sessionid')");
				setcookie("username",$_POST['login'],time()+3600);
				header('Location: login.php');
			}
		}
		else echo "<script type=\"text/javascript\">alert(\"Wrong Password. Access Denied.\")</script>";
	}
	else echo "<script type=\"text/javascript\">alert(\"Username doesn't exist.\")</script>";
}
?>
<br/>
<h1><center>Hospital Management System</center></h1>
  <section class="container">
    <div class="login">
      <h1>Login</h1>
      <form method="post" action="index.php?pid=01">
        <p><input type="text" name="login" value="" placeholder="Username"></p>
        <p><input type="password" name="password" value="" placeholder="Password"></p>
        <p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label>
        </p>
        <p class="submit"><input type="submit" name="commit" value="Login"></p>
      </form>
    </div>

    <div class="login-help">
      <!---<p><font color=#000000>Forgot your password? <a href="index.html">Click here to submit a reset request</a>.</font></p>-->
    </div>
  </section>
</body>
</html>
