<?php
require_once("value.php");

$error_flag = array();

if($_POST['in']==NULL){
	html_top();
}
elseif($_POST['in']==1){
	input_check();
	make_crypted_pass();
	html_out();
}

function html_top(){
	global $maintenance_cgi;
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>パスワード変更</title>
</head>
<body>
<div class="head"><h1>パスワード変更</h1></div>
<div id="main">
	<form action="{$maintenance_cgi}" method="POST">
	<div id="box">
		<div class="title">教員ID</div>
		<div class="content">{$_GET{'teacher'}}</div>
		<div class="title">新パスワード設定</div>
		<div class="content">半角英数字<span class="red">20字</span>まで</div>
		<input type="password" name="new_pass" size="20">
		<div class="title">パスワードの再入力</div>
		<div class="content">確認のためもう一度入力してください。</div>
		<input type="password" name="check_pass" size="20">
	</div>
	<p><input type="submit" value="変更"><input type="reset" value="リセット"></p>
	<input type="hidden" name="in" value="1">
	<input type="hidden" name="teacher" value="{$_GET{'teacher'}}">
	</form>
</div>
</body>
</html>
HTML_END;
}

function input_check(){
	$error_flag = array();
	
	if($_POST{'new_pass'} == "")					$error_flag[0] = 1;
	if($_POST{'new_pass'} != $_POST{'check_pass'})	$error_flag[1] = 1;
	
	foreach($error_flag as $value){
		$Error_flags += $value;
	}
	if($Error_flags) error($error_flag);
}

function make_crypted_pass(){
	global $FILE_PASSWD,$FILE_PASSWDNEW;
	
	$crypted_passwd = crypt($_POST{'new_pass'},'$1$');
	$TMP = file($FILE_PASSWD);
	$OUT = fopen($FILE_PASSWDNEW,"w");
	foreach($TMP as $value){
		list($teacher,$passwd) = explode(":",$value);
		$passwd = rtrim($passwd);
		if($_POST{'teacher'} == $teacher){
			$passwd = $crypted_passwd;
		}
		fwrite($OUT,$teacher.":".$passwd."\n");
	}
	fclose($OUT);
	unlink($FILE_PASSWD);
	rename($FILE_PASSWDNEW,$FILE_PASSWD);
}

function html_out(){
	global $certification_cgi;

	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="CSS/maintenance.css" type="text/css">
<title>パスワード変更</title>
<body>
<h1>パスワード変更しました。</h1>
<p><a href={$certification_cgi}?teacher={$_POST{'teacher'}}&mode=internal>科目一覧へ</a></p>
</body>
</html>
HTML_END;
}

function error($error_flag){
	global $maintenance_cgi;
	$error_msg = array();
	
	if($error_flag[0])	$error_msg[0] = 'パスワードが入力されていません．';
	if($error_flag[1])	$error_msg[1] = '確認のパスワードと一致しません．';
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="CSS/maintenance.css" type="text/css">
<title>入力エラー</title>
</head>
<body>
<h2 class="red">入力にエラーがあります。</h2>
以下の項目を確認してください。<br>
HTML_END;
	foreach($error_flag as $key => $value){
		if($value==1) print "・".$error_msg[$key]."<br>\n";
	}
    print <<< HTML_END
<p>パスワードの変更に失敗しました。</p>
<p>前画面に戻り、もう一度入力してください。</p>
<p><a href="{$maintenance_cgi}?teacher={$_POST{'teacher'}}">戻る</a></p>
</body>
</html>
HTML_END;
	exit;
}
?>
