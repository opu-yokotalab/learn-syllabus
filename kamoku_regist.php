<?php
require_once('value.php');

$mode = $_POST{'mode'};

if($_POST['teacher'])		$teacher = $_POST['teacher'];
elseif($_GET['teacher'])	$teacher = $_GET['teacher'];

if($_POST['depart'])		$depart = $_POST['depart'];
elseif($_GET['depart'])		$depart = $_GET['depart'];

$list_txt		= "./data/".$depart."/xml/list.txt";
$list_txtnew	= "./data/".$depart."/xml/list.new";
$XML_D			= "./data/".$depart."/xml/";

$sorting = array();
$lineout = array();

// 科目名入力時の処理
if($mode != "internal"){
	print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>新規科目登録</title>
</head>
<body>
<center>
<div class="head"><h1>新規科目の登録</h1></div>
<form action="$kamoku_regist_cgi" method="POST">
<table id="box">
<tr>
	<td class="title">科目名</td>
	<td class="content"><input type=text name=xmlfilename size="20"></td>
</tr>
<tr>
	<td class="content" colspan="2">(例) データ工学</td>
</tr>
</table>
<input type="hidden" name="teacher" value="{$teacher}">
<input type="hidden" name="mode" value="internal">
<input type="hidden" name="depart" value="{$depart}">
<input type="submit" value="登録">
</form>
</center>
</body>
</html>
HTML_END;
}

// 科目名入力後の処理
else{
	$xmlfilename = rtrim($_POST['xmlfilename']);
	
	if($xmlfilename == ""){
		print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>エラー</title>
</head>
<body>
<form action="{$kamoku_regist_cgi}" method="POST">
	<table>
		<tr>
			<td>登録名が空白です．</td>
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
	else{
		// list.txtに科目ID，教員名，科目名を書き込む．
		$xmlfile = $teacher . time();
		$OUT = fopen($list_txt,'a') or die("open error");
		fwrite($OUT,"00:0:".$xmlfile.":".$teacher.":".$xmlfilename."\n");
		fclose($OUT);
		
		// list.txtのソート
		$data = file($list_txt) or die("open error");
		foreach($data as $value){
			$value = rtrim($value);
			$line = split(":",$value);
			$tmp = $line[0];
			$line[0] = $line[2];
			$line[2] = $tmp;
			array_push($sorting,$line);
		}
		sort($sorting);
		foreach($sorting as $value){
			$tmp = $value[0];
			$value[0] = $value[2];
			$value[2] = $tmp;
			$imp_value = implode(":",$value);
			array_push($lineout,$imp_value);
		}
		$OUT = fopen($list_txtnew,"w");
		foreach($lineout as $value){
			fwrite($OUT,$value."\n");
		}
		fclose($OUT);
		unlink($list_txt);
		rename($list_txtnew,$list_txt);
		
		// XMLの作成
		$XML = fopen($XML_D.$xmlfile.".xml","w") or die("open error");
		$buf = <<< XML_END
<?xml version="1.0" encoding="EUC-JP" ?>
    
<syllabus>
	<subject_code> </subject_code>
	<subject_name>
		<name_ja>{$xmlfilename}</name_ja>
		<name_en> </name_en>
	</subject_name>
	<teachers>
		<teacher em_form="full"></teacher>
		<teacher em_form="full"></teacher>
	</teachers>
	<student> </student>
	<subject_form> </subject_form>
	<choice> </choice>
	<credit> </credit>
	<outline><p> </p></outline>
	<object><p> </p></object>
	<attention>
		<necessary><p> </p></necessary>
		<others><p></p></others>
	</attention>
	<subject_plan form="list">
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
		<li><p> </p></li>
	</subject_plan>
	<granding><p> </p></granding>
	<relation><p> </p></relation>
	<text>
		<text_books>
			<book> </book>
		</text_books>
		<ref_books>
			<book> </book>
	</ref_books>
	</text>
	<contact>
		<room_num> </room_num>
		<e-mail> </e-mail>
		<others> </others>
	</contact>
	<remark><p> </p></remark>
	<page></page>
</syllabus>
XML_END;
		fwrite($XML,$buf);
		fclose($XML);
		
		// HTML部分（"科目名"を登録しました、と表示）
		print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="css/maintenance.css" type="text/css">
<title>登録完了</title>
</head>
<body>
<form action="{$certification_cgi}" method="POST">
	<table>
		<tr>
			<td>{$xmlfilename}を登録しました。</td>
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
}
?>