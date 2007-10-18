<?php
require_once("value.php");

$error_flag = array();

if($_POST['in']==NULL){
	html_top();
}
elseif($_POST['in']==1){
	input_check();
	make_crypted_pass();
	sorting_passfile();
	html_out();
}

function html_top(){
	global $user_regist_cgi;
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>������Ͽ</title>
</head>
<body>
<h2>�ڶ���������Ͽ��</h2>
<div id="main">
 <form action="{$user_regist_cgi}" method="POST">
 <div id="box">
  <div class="title">����ID��Ͽ</div>
  <div class="content"><span class="u">�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������</span><br>
   <input type="text" name="teacher" size="40"></div>
  <div class="title">�ѥ��������</div>
  <div class="content">Ⱦ�ѱѿ���<span class="red">20��</span>�ޤ�<br>
   <input type="password" name="passwd" size="20"></div>
  <div class="title">�ѥ���ɤκ�����</div>
  <div class="content">��ǧ�Τ���⤦�������Ϥ��Ƥ���������<br>
   <input type="password" name="check_passwd" size="20"></div>
  </div>
  <p><input type="submit" value="��Ͽ"><input type="reset" value="�ꥻ�å�"></p>
  <input type="hidden" name="in" value="1">
 </form>
</div>
<a href="./">���</a>
</body>
</html>
HTML_END;
}

function input_check(){
	global $FILE_PASSWD,$error_flag;
	$check = file($FILE_PASSWD);
	foreach($check as $value){
		list($teacher,$passwd) = explode(":",$value);
		$passwd = rtrim($passwd);
		if($_POST['teacher'] == $teacher){
			$error_flag[0] = 1;
			break;
		}
	}
	
	if($_POST['teacher']=="")						$error_flag[1] = 1;
	if($_POST['passwd']=="")						$error_flag[2] = 1;
	if($_POST['passwd']!=$_POST['check_passwd'])	$error_flag[3] = 1;
	
	// ����ʸ����¸��Ƚ��
	
	foreach($error_flag as $value){
		$Error_flags += $value;
	}
	if($Error_flags) error($error_flag);
}

function make_crypted_pass(){
	global $FILE_PASSWD;
	$crypted_passwd = crypt($_POST['passwd'],'$1$');
	$REGIST = fopen($FILE_PASSWD,"a");
	fwrite($REGIST,$_POST['teacher'].":".$crypted_passwd."\n");
	fclose($REGIST);
}

function sorting_passfile(){	// ������
	global $FILE_PASSWD,$FILE_PASSWDNEW;
	$data = array();
	
	$STD = file($FILE_PASSWD);
	foreach($STD as $value){
		$value = rtrim($value);
		array_push($data,$value);
	}
	sort($data);
	$OUT = fopen($FILE_PASSWDNEW,"w");
	foreach($data as $value){
		fwrite($OUT,$value."\n");
	}
	fclose($OUT);
	unlink($FILE_PASSWD);
	rename($FILE_PASSWDNEW,$FILE_PASSWD);
}

function html_out(){
	global $top_cgi;
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="CSS/maintenance.css" type="text/css">
<title>������Ͽ</title>
</head>
<body>
  <h2 style="color:deeppink;">����ID:{$_POST['teacher']}</h2>��Ͽ��λ���ޤ�����
  <p><a href=$top_cgi>�ȥåץڡ�����</a></p>
 </form>
</body>
</html>
HTML_END;
}

function error($error_flag){
	$error_msg = array();
	
	if($error_flag[0])	$error_msg[0] = '���Ϥ��줿�����Ϥ��Ǥ���Ͽ����Ƥ��ޤ���';
	if($error_flag[1])	$error_msg[1] = '����ID�����Ϥ���Ƥ��ޤ���';
	if($error_flag[2])	$error_msg[2] = '�ѥ���ɤ����Ϥ���Ƥ��ޤ���';
	if($error_flag[3])	$error_msg[3] = '��ǧ�Υѥ���ɤȰ��פ��ޤ���';
	if($error_flag[4])	$error_msg[4] = '����ID�����ܸ�ޤ�������ʸ�����ޤޤ�Ƥ��ޤ���';
	
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
<p>�ʲ��ι��ܤ��ǧ���Ƥ���������</p>
HTML_END;
	foreach($error_flag as $key => $value){
		if($value==1) print "��".$error_msg[$key]."<br>\n";
	}
    print <<< HTML_END
<p>��Ͽ�˼��Ԥ��ޤ�����</p>
<p>�����̤���ꡢ�⤦�������Ϥ��Ƥ���������</p>
<p><a href="./regist.php">���</a></p>
</body>
</html>
HTML_END;

	exit;
}
?>