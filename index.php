<?php
require_once("value.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>������Ω��إ���Х����������ƥ�2008</title>
<link rel="stylesheet" href="css/top.css" type="text/css">
<link rel="stylesheet" href="css/default.css" type="text/css">
</head>
<body>
<div class="top">
	<div class="head"><h1>������Ω��إ���Х����������ƥ�2008</h1></div>
	<div class="left">
		����Х����������ƥ�ˤ褦������<br>
		����ID�ʥ᡼�륢�ɥ쥹�ˤȥѥ���ɤ����Ϥ��Ƥ���������<br>
		<a href="./use.html">�Ȥ�������ջ���</a>
	</div>
	<div class="right">
		<form action="./certification.php" method="post">
			<table class="pswd">
			<tr>
				<td class="pswdt">����ID</td>
				<td><input type="text" name="teacher" size="30"></td>
			</tr>
			<tr>
				<td class="pswdt">�ѥ����</td>
				<td><input type="password" name="password" size="20"></td>
			</tr>
			</table>
			<input type="submit" value="ǧ��"><input type="reset" value="�ꥻ�å�">
		</form>
		<div class="topright">
			<a href="./regist.php">�����ο�����Ͽ</a><br>
			<a href="./list.php">��Ͽ�Ѥβ��ܰ���</a><br>
			<a href="http://www.adobe.com/jp/products/acrobat/readstep2.html" target="_blank"><img id="getacro" src="get_adobe_reader.gif"></a>
		</div>
	</div>
</div>
<div class="foot">
	<hr>
	<a href="mailto:syllabus-support@alpha.c.oka-pu.ac.jp">�᡼��󥰥ꥹ��</a>�ʥХ����ʤɡ�<br>
	<p>
		Ver.<?php echo $version." rev.2008"; ?><br>
		���Yokota Lab.
	</p>
</div>
</body>
</html>
