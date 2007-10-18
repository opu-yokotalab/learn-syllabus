<?php
require_once('value.php');

$mode = $_POST['mode'];

if($_POST['root'])			$root = $_POST['root'];
elseif($_GET['root'])		$root = $_GET['root'];

if($_POST['kamokuID'])		$kamokuID = $_POST['kamokuID'];
elseif($_GET['kamokuID'])	$kamokuID = $_GET['kamokuID'];

if($_POST['teacher'])		$teacher = $_POST['teacher'];
elseif($_GET['teacher'])	$teacher = $_GET['teacher'];

if($_POST['depart'])		$depart = $_POST['depart'];
elseif($_GET['depart'])		$depart = $_GET['depart'];

$list_txt		= "./data/".$depart."/xml/list.txt";
$list_txtnew	= "./data/".$depart."/xml/list.new";
$XML_D			= "./data/".$depart."/xml/";
$XML_PDF_D		= "./data/".$depart."/pdf/";

if($mode != "internal"){
	$check = file($list_txt) or die("open error");
	foreach($check as $value){
		list($page,$ok,$kamoku_ID_r,$teacher_ID,$kamoku_name_r) = explode(":",$value);
		$kamoku_name_r = rtrim($kamoku_name_r);
		if($kamokuID == $kamoku_ID_r){
			$kamoku_name = $kamoku_name_r;
		}
	}
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<head><title>科目削除</title></head>
<body>
<center>
<div class="head"><h1>科目の削除</h1></div>
<form method="POST" action="{$kamoku_delete_cgi}">
<table>
<tr>
	<td>{$kamoku_name}を削除してよろしいですか？</td>
</tr>
</table>
<input type="hidden" name="kamokuID" value="{$kamokuID}">
<input type="hidden" name="kamoku_name" value="{$kamoku_name}">
<input type="hidden" name="teacher" value="{$teacher}">
<input type="hidden" name="mode" value="internal">
<input type="hidden" name="depart" value="{$depart}">
<input type="hidden" name="root" value="{$root}">

<input type="SUBMIT" value="削除する">
<input type="button" value="削除せずに前の画面に戻る" onClick="history.back()">
</form>
</center>
</body>
</html>
HTML_END;
}

else{
	$list_out = array();
	
	$kamoku_name = $_POST{'kamoku_name'};
	$check = file($list_txt) or die("open error");
	foreach($check as $value){
		list($page,$ok,$kamoku_ID_r,$teacher_ID,$kamoku_name_r) = explode(":",$value);
		if($kamoku_ID_r != $kamokuID){
			array_push($list_out,rtrim($value));
		}
	}
	$OUT = fopen($list_txtnew,"w");
	foreach($list_out as $value){
		fwrite($OUT,$value."\n");
	}
	fclose($OUT);
	unlink($list_txt);
	rename($list_txtnew,$list_txt);
	
	unlink($XML_D.$kamokuID.".xml");
	if(file_exists($XML_PDF_D.$kamokuID.".pdf")) unlink($XML_PDF_D.$kamokuID.".pdf");
	//if(file_exists($XML_html_D.$kamokuID.".html")) unlink($XML_html_D.$kamokuID.".html");
	
	if($root == 1)	$teacher = "root";
	
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<head><title>削除完了</title></head>
<body>
<form method=$method action="$certification_cgi">
<table>
	<tr>
		<td>{$kamoku_name}を削除しました。</td>
	</tr>
	<tr>
		<td>
			<a href="{$certification_cgi}?teacher={$teacher}&mode=internal">科目一覧に戻る</a>
		</td>
	</tr>
</table>
</form>
</body>
</html>
HTML_END;
}