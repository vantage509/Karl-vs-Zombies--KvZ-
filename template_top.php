<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
	<title>Humans vs. Zombies :: Source</title>
	<link rel="stylesheet" type="text/css" href="style/styles.css" >
	
	<script type="text/javascript" src="/js/gotos.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5888167-7']);
  _gaq.push(['_setDomainName', '.hvzsource.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

function clickclear(thisfield, defaulttext) {
	if (thisfield.value == defaulttext) {
	thisfield.value = "";
	}
}
function clickrecall(thisfield, defaulttext) {
	if (thisfield.value == "") {
	thisfield.value = defaulttext;
	}
}
</script>
	
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><!-- BEGIN LOGIN ROW -->
	<td colspan="3" style="background:#3E3C3D;height: 30px;"><form method="post" action="index.php">
<?php
$doc_root = $_SERVER['DOCUMENT_ROOT'] . '/'; // Used for checking existance of files at server level

if($_POST['submit'] == 'Login') {
	$logname = preg_replace("/[^A-Za-z0-9]/","",$_POST['user']);
	$logpass = md5(preg_replace("/[^A-Za-z0-9]/","",$_POST['pass']));
	$sql = my_quick_con($config) or die("MySQL problem"); 
	$table_u = $config['user_table'];
	
	$ret = mysql_query("SELECT password FROM $table_u WHERE username='$logname';");
	$pass = mysql_fetch_assoc($ret);
	$pass = $pass['password'];
	if(mysql_num_rows($ret) == 1 && $pass == $logpass) {
		$ret = mysql_query("SELECT id FROM $table_u WHERE username='$logname';");
		$id = mysql_fetch_assoc($ret);
		$id = $id['id'];
		mysql_free_result($ret);
		$_SESSION['user'] = $logname;
		$_SESSION['id'] = $id;
		header('Location: account.php');
	} else {
		echo "<font color=red>Invalid username/password</font>&nbsp;&nbsp;|&nbsp;&nbsp;";
	}
	if(is_resource($ret)) {
		mysql_free_result($ret);
	}
}

if(!isset($_SESSION['user'])) {
	echo '<a href="register.php">Register</a>&nbsp;&nbsp;|&nbsp;&nbsp;<input name="user" type="text" value="Username" size="15" onclick="clickclear(this, \'Username\')" onblur="clickrecall(this,\'Username\')"><input name="pass" type="password" value="" size="15"><input type="submit" name="submit" value="Login">&nbsp;&nbsp;|&nbsp;&nbsp;<a href="pass_reset.php">Forgot your Password?</a>';
} else { 
	echo '<a href="account.php">My Account</a>&nbsp;&nbsp;|&nbsp;&nbsp; <a href="logout.php">Log Out</a>';
}     
?></form></td>
</tr>
<tr>
	<td style="background:url(images/LeftBKGRNDImage.jpg) no-repeat right top black;height: 270px;">&nbsp;</td>
	<td width="900" height="300" valign="top" bgcolor="#FFFFFF">
	<table id="Table_01" border="0" cellpadding="0" cellspacing="0" bgcolor="#141414"><!-- BEGIN HEADER TABLE #005500-->
	<tr>
		<td align="right" class="style1" colspan="2" style="background:#3E3C3D;height: 30px;"></td>
	</tr>
	<tr height="10"><!-- BEGIN HEADER TRANSITION ROW -->
				<td colspan="2" width="900"><img src="images/default_transition.jpg" alt=""></td>
	</tr>
	<tr height="210"><!-- BEGIN MAIN HEADER ROW -->
		<td width="600"><img src="images/Top_05.jpg" alt=""></td>
		<td width="300"><table border="0" cellpadding="0" cellspacing="0"><!-- BEGIN ABOUT TABLE -->
			<tr>
				<td rowspan="2" height="210" width="20"><img src="images/default_about_left.jpg" alt=""></td>
				<td width="220"><img src="images/default_about_top.jpg" alt=""></td>
				<td rowspan="2" height="210" width="60"><img src="images/default_top_ladder.jpg" alt=""></td>
			</tr>
			<tr>
				<td height="180"><p class="style2">Funky text goes here, and lots of it if you want!<br><b>HvZSource</b> is a free piece of software developed by the creators of <b>HvZ</b>.<br><br>Along with rebuilding a more robust system, we will be adding some innovative and premium content.</p></td>
			</tr>
		</table></td>
	</tr>
	<tr bgcolor="#FFFFFF"><!-- BEGIN MENU BAR ROW -->
		<td colspan="2" height="50" width="900"><a href="index.php"><img src="images/default_menu_home.jpg" alt="Home" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="kill.php"><img src="images/default_menu_report.jpg" alt="Report a Tag" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="players.php"><img src="images/default_menu_players.jpg" alt="Players" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="rules.php"><img src="images/default_menu_rules.jpg" alt="Rules" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="http://www.humansvszombies.org/"><img src="images/default_menu_org.jpg" alt="HvZ.org" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="http://status.hvzsource.com/"><img src="images/default_menu_status.jpg" alt="Status" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="http://wiki.humansvszombies.org/"><img src="images/default_menu_wiki.jpg" alt="Wiki" border="0"></a
			><img src="images/default_menu_spacer.jpg" alt=""
			><a href="http://forums.humansvszombies.org/"><img src="images/default_menu_forums.jpg" alt="Forums" border="0"></a></td>
	</tr>
	</table>
	</td>
	<td align="left" style="background:url(images/RightBKGRNDImage.jpg) no-repeat left top black;height: 270px;">&nbsp;</td>
</tr><tr>
	<td style="background:url(images/LeftColumn.jpg) right top repeat-y">&nbsp;</td>
	<td width="900" bgcolor="#FFFFFF">
	<table width="100%" border="0" cellspacing="10" cellpadding="10">
	<tr>
		<td valign="top">
