<?php
require_once('value.php');

if($_POST['mode'])			$mode = $_POST['mode'];
elseif($_GET['mode'])		$mode = $_GET['mode'];

if($_POST['teacher'])		$teacher = $_POST['teacher'];
elseif($_GET['teacher'])	$teacher = $_GET['teacher'];

if($_POST['owner'])			$owner = $_POST['owner'];
elseif($_GET['owner'])		$owner = $_GET['owner'];

$deleteflag = $_POST['deleteflag'];

if($teacher == "root" && $mode != "internal"){
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>�����κ��</title>
</head>
<body>
<center>
<div class="head"><h1>�����κ��</h1></div>
<font color="red"><b>Caution!!</b><br>������������ȡ���������ѤϤǤ��ʤ��ʤ�ޤ���
��ͭ�ե������������ȡ�����Ͽ���Ƥ�����Υǡ��������ѤǤ��ޤ���</font>
<form method="POST" action="{$user_delete_cgi}">
<table id="box">
	<tr>
		<td class="title">������붵��������</td>
	</tr>
	<tr>
		<td><center>

HTML_END;
	make_teacher_list();
	print <<< HTML_END
		</center></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="deleteflag" value="all">��ͭ�ե�����⤹�٤ƺ��</td>
	</tr>
</table>
<input type="hidden" name="owner" value="{$owner}">
<input type="hidden" name="mode" value="internal">
<input type="SUBMIT" value="�������">
</form>
</center>
</body>
</html>
HTML_END;
}
elseif($teacher != "root" && $mode != "internal"){
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>�����κ��</title>
</head>
<body>
<center>
<div class="head"><h1>�����κ��</h1></div>
<font color="red"><b>Caution!!</b><br>�����κ����Ԥ��ȡ���ͭ����ե�����������졢����Ͽ���ʤ��¤꺣������ѤϤǤ��ʤ��ʤ�ޤ���</font>
<form method="POST" action="{$user_delete_cgi}">
<table>
	<tr>
		<td>{$teacher}�������Ƥ�����Ǥ�����</td>
	</tr>
</table>
<br>
<input type="hidden" name="deleteflag" value=all>
<input type="hidden" name="teacher" value={$teacher}>
<input type="hidden" name="owner" value="{$owner}">
<input type="hidden" name="mode" value="internal">
<input type="SUBMIT" value="�������">
</form>
</center>
</body>
</html>
HTML_END;
}
else{
	$deletefile = array();
	
	if($deleteflag == "all"){
		$delete_list = array("communication","systems","master-c","master-s","doctor");
		foreach($delete_list as $value){
			array_push($deletefile,delete_depart($value,$teacher));
		}
	}
	$check = file($FILE_PASSWD) or die("open error");
	$data = array();
	$result = array();
	
	foreach($check as $value){
		array_push($data,rtrim($value));
	}
	
	foreach($data as $value){
		list($ID,$passwd) = split(":",$value);
		if($ID != $teacher){
			array_push($result,$value);
		}
	}
	
	$OUT = fopen($FILE_PASSWDNEW,"w") or die("open error");
	foreach($result as $value){
		fwrite($OUT,$value."\n");
	}
	fclose($OUT);
	unlink($FILE_PASSWD);
	rename($FILE_PASSWDNEW,$FILE_PASSWD);
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>�����κ��</title></head>
<body>
<form method=$method action="$certification_cgi">
<table>
	<tr>
		<td>
HTML_END;
	if($teacher == "select"){
		print "���������򤵤�Ƥ��ޤ���";
	}
	else{
		print $teacher."�������ޤ�����";
	}
	print <<< HTML_END
</td>
	</tr>
	<tr>
		<td>
HTML_END;

	if($deleteflag == "all"){
		foreach($deletefile as $value){
			foreach($value as $value2){
				print $value2.".xml��".$value2.".pdf�������ޤ�����<br>";
			}
		}
	}
	else{
		if($teacher != "select"){
			print $teacher."�Υ�������ȤΤߤ������ޤ�����<br>�ʥե�����ϻĤäƤ��ޤ���";
		}
	}
	print <<< HTML_END
</td>
	</tr>
	<tr>
		<td>
HTML_END;
	if($owner == "root"){
		print <<< HTML_END
<a href={$certification_cgi}?teacher=root&mode=internal>���ܰ��������</a>
HTML_END;
	}
	else{
		print <<< HTML_END
<a href={$top_cgi}>�ȥåץڡ��������</a>
HTML_END;
	}
	print <<< HTML_END
</td>
	</tr>
</table>
</form>
</body>
</html>
HTML_END;
}

function delete_depart($depart,$teacher){
	$list_txt		= "./data/".$depart."/xml/list.txt";
	$list_txtnew	= "./data/".$depart."/xml/list.new";
	$XML_D			= "./data/".$depart."/xml/";
	$XML_PDF_D		= "./data/".$depart."/pdf/";
	
	$check = file($list_txt);
	$data = array();
	$result = array();
	$deletefile = array();
	
	foreach($check as $value){
		array_push($data,rtrim($value));
	}
	
	foreach($data as $value){
		list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
		if($teacher != $teacher_ID){
			array_push($result,$value);
		}
		else{
			array_push($deletefile,$kamoku_ID);
		}
	}
	
	$OUT = fopen($list_txtnew,"w");
	foreach($result as $value){
		fwrite($OUT,$value."\n");
	}
	fclose($OUT);
	unlink($list_txt);
	rename($list_txtnew,$list_txt);
	
	foreach($deletefile as $value){
		unlink($XML_D.$value.".xml");
		if(file_exists($XML_PDF_D.$value.".pdf")) unlink($XML_PDF_D.$value.".pdf");
	}
	return $deletefile;
}

function make_teacher_list(){
	global $FILE_PASSWD;
	$check = file($FILE_PASSWD) or die("open error");
	$data = array();
	
	print <<< HTML_END
		<select name="teacher">
			<option value="select">���򤷤Ƥ�������</option>

HTML_END;
	foreach($check as $value){
		list($ID,$passwd) = split(":",$value);
		if($ID != "root"){
			array_push($data,$value);
		}
	}
	sort($data);
	foreach($data as $value){
		list($ID,$passwd) = split(":",$value);
		print "\t\t\t<option value=\"".$ID."\">".$ID."</option>\n";
	}
	print "\t\t</select>\n";
}
