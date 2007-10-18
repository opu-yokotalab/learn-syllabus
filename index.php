<?php
require_once("value.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>岡山県立大学シラバス管理システム2008</title>
<link rel="stylesheet" href="css/top.css" type="text/css">
<link rel="stylesheet" href="css/default.css" type="text/css">
</head>
<body>
<div class="top">
	<div class="head"><h1>岡山県立大学シラバス管理システム2008</h1></div>
	<div class="left">
		シラバス管理システムにようこそ。<br>
		教員ID（メールアドレス）とパスワードを入力してください。<br>
		<a href="./use.html">使い方・注意事項</a>
	</div>
	<div class="right">
		<form action="./certification.php" method="post">
			<table class="pswd">
			<tr>
				<td class="pswdt">教員ID</td>
				<td><input type="text" name="teacher" size="30"></td>
			</tr>
			<tr>
				<td class="pswdt">パスワード</td>
				<td><input type="password" name="password" size="20"></td>
			</tr>
			</table>
			<input type="submit" value="認証"><input type="reset" value="リセット">
		</form>
		<div class="topright">
			<a href="./regist.php">教員の新規登録</a><br>
			<a href="./list.php">登録済の科目一覧</a><br>
			<a href="http://www.adobe.com/jp/products/acrobat/readstep2.html" target="_blank"><img id="getacro" src="get_adobe_reader.gif"></a>
		</div>
	</div>
</div>
<div class="foot">
	<hr>
	<a href="mailto:syllabus-support@alpha.c.oka-pu.ac.jp">メーリングリスト</a>（バグ報告など）<br>
	<p>
		Ver.<?php echo $version." rev.2008"; ?><br>
		製作：Yokota Lab.
	</p>
</div>
</body>
</html>
