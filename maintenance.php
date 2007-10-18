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
<title>�ѥ�����ѹ�</title>
</head>
<body>
<div class="head"><h1>�ѥ�����ѹ�</h1></div>
<div id="main">
	<form action="{$maintenance_cgi}" method="POST">
	<div id="box">
		<div class="title">����ID</div>
		<div class="content">{$_GET{'teacher'}}</div>
		<div class="title">���ѥ��������</div>
		<div class="content">Ⱦ�ѱѿ���<span class="red">20��</span>�ޤ�</div>
		<input type="password" name="new_pass" size="20">
		<div class="title">�ѥ���ɤκ�����</div>
		<div class="content">��ǧ�Τ���⤦�������Ϥ��Ƥ���������</div>
		<input type="password" name="check_pass" size="20">
	</div>
	<p><input type="submit" value="�ѹ�"><input type="reset" value="�ꥻ�å�"></p>
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
<title>�ѥ�����ѹ�</title>
<body>
<h1>�ѥ�����ѹ����ޤ�����</h1>
<p><a href={$certification_cgi}?teacher={$_POST{'teacher'}}&mode=internal>���ܰ�����</a></p>
</body>
</html>
HTML_END;
}

function error($error_flag){
	global $maintenance_cgi;
	$error_msg = array();
	
	if($error_flag[0])	$error_msg[0] = '�ѥ���ɤ����Ϥ���Ƥ��ޤ���';
	if($error_flag[1])	$error_msg[1] = '��ǧ�Υѥ���ɤȰ��פ��ޤ���';
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="CSS/maintenance.css" type="text/css">
<title>���ϥ��顼</title>
</head>
<body>
<h2 class="red">���Ϥ˥��顼������ޤ���</h2>
�ʲ��ι��ܤ��ǧ���Ƥ���������<br>
HTML_END;
	foreach($error_flag as $key => $value){
		if($value==1) print "��".$error_msg[$key]."<br>\n";
	}
    print <<< HTML_END
<p>�ѥ���ɤ��ѹ��˼��Ԥ��ޤ�����</p>
<p>�����̤���ꡢ�⤦�������Ϥ��Ƥ���������</p>
<p><a href="{$maintenance_cgi}?teacher={$_POST{'teacher'}}">���</a></p>
</body>
</html>
HTML_END;
	exit;
}
?>
