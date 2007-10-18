<?php
require_once("value.php");

if($_POST['teacher'])		$teacher = $_POST['teacher'];
elseif($_GET['teacher'])	$teacher = $_GET['teacher'];

if($_POST['password'])		$password = crypt($_POST['password'],'$1$');

if($_POST['mode'])			$mode = $_POST['mode'];
elseif($_GET['mode'])		$mode = $_GET['mode'];

print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>
HTML_END;

if($teacher != "root"){
	print $teacher . "さんの";
}

print <<< HTML_END
科目一覧</title>
<link rel="stylesheet" href="css/SCS.css" type="text/css">
</head>
<body>
HTML_END;

// ログイン中の処理
if($mode == "internal"){
	makelist();
	print "</body>\n</html>";
}

// top.cgiからの場合の処理
else{
	// 未入力がある場合の処理
	if(($teacher == NULL) || ($password == NULL)){
		print "教員IDまたはパスワードが未入力です。\n";
		print "</body>\n</html>";
		exit("Input Error\n");
	}
	
	$teacher_exist = 0;
	$user = file($FILE_PASSWD) or die("open Error\n");
	foreach($user as $value){
		list($list_id,$list_pass) = explode(":",$value);
		if($list_id == $teacher) $teacher_exist = 1;
		$checkpass[$list_id] = rtrim($list_pass);
	}
	
	// パスワードの照合
	if($password == $checkpass[$teacher]){
		makelist();
	}
	elseif($teacher_exist==0){
		print "教員IDが存在しません．\n";
	}
	else{
		print $teacher . "さんのパスワードが違います．\n";
	}
	print "</body>\n</html>";
}

function makelist(){
	global $teacher,$maintenance_cgi,$user_delete_cgi;
	
	if($teacher == "root"){
		print <<< HTML_END
<div class="head"><h1>科目一覧</h1>
<div align="right"><a class="attent" href={$user_delete_cgi}?teacher=root&owner=root>教員を選んで削除</a></div></div>

HTML_END;
	}
	else{
		print <<< HTML_END
<div class="head"><h1>{$teacher}さんの科目一覧</h1>
<div align="right"><a class="attent" href={$user_delete_cgi}?teacher={$teacher}>教員削除</a></div></div>

HTML_END;
	}
	
	$list_depart = array(	"communication" => "情報通信工学科",
							"systems" => "情報システム工学科",
							"master-c" => "電子情報通信工学専攻",
							"master-s" => "機械情報システム工学専攻",
							"doctor" => "システム工学専攻");
	
	foreach($list_depart as $depart => $depart_ja){
		list_handle($depart,$depart_ja);
	}
	
	print <<< HTML_END
<div id="right">
	<a href={$maintenance_cgi}?teacher={$teacher}>パスワード変更</a><br>
	<a href=./index.php>ログアウト</a>
</div>

HTML_END;
}

function list_handle($depart,$depart_ja){
	global $teacher,$maintenance_cgi,$kamoku_regist_cgi,$kamoku_delete_cgi,$edit_cgi;
	$list_txt			= "./data/".$depart."/xml/list.txt";
	$XML_PDF_D			= "./data/".$depart."/pdf/";
	$data = array();
	
	if($teacher == "root"){
		$root = 1;
	}else{
		$root = 0;
	}
	
	if($teacher == "root"){
		print <<< HTML_END
<div align="center">
<div class="title"><h1>{$depart_ja}</h1></div>
</div>
<table>
<tr class="ttitle">
	<td class="Grootsubjectname">科目名</td><td class="Grootid">教員ID(Mail)</td><td>更新完了</td><td>編集</td><td>削除</td>
</tr>

HTML_END;
		$check = file($list_txt);
		foreach($check as $value){
			array_push($data,rtrim($value));
		}
		sort($data);
		foreach($data as $value){
			list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
			if(file_exists($XML_PDF_D . $kamoku_ID . ".pdf")){
				$pdfexist = 1;
			}else{
				$pdfexist = 0;
			}
			
			if(!($kamoku_ID == "" || $teacher_ID == "" || $kamoku_name == "")){
				print <<< HTML_END
<tr>
	<td class="subjectname">
HTML_END;
			}
			if($pdfexist != 0){
				print <<< HTML_END
<a href="{$XML_PDF_D}{$kamoku_ID}.pdf" target="_blank">{$kamoku_name}</a>

HTML_END;
			}
			else{
				print $kamoku_name;
			}
			if(file_exists($XML_PDF_D.$kamoku_ID.".html")){
				print <<< HTML_END
(<a href="{$XML_PDF_D}{$kamoku_ID}.html" target="_blank">html</a>)

HTML_END;
			}
			
			print <<< HTML_END
</td>
	<td>{$teacher_ID}</td>

HTML_END;
			if($ok == "0")	print "\t<td class=\"uncomp\">未完了</td>\n";
			else			print "\t<td class=\"comp\">完了</td>\n";
			print <<< HTML_END
	<td><a href={$edit_cgi}?xmlfile={$kamoku_ID}.xml&teacher={$teacher_ID}&depart={$depart}&root={$root}>編集</a></td>
	<td><a href={$kamoku_delete_cgi}?teacher={$teacher}&kamokuID={$kamoku_ID}&depart={$depart}&root={$root}>削除</a></td>
</tr>

HTML_END;
		}
	}
	else{ // general user
		print <<< HTML_END
<div align="center">
<div class="title"><h1>{$depart_ja}</h1></div>
</div>
<table>
<tr class="ttitle">
	<td class="Gnormalsubjectname">科目名</td><td>編集</td><td>印刷イメージ</td><td>更新完了</td><td>削除</td>
</tr>

HTML_END;
		$check = file($list_txt);// or die("open error");
		foreach($check as $value){
			list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
			$kamoku_name = rtrim($kamoku_name);
			if($teacher == $teacher_ID){
				print <<< HTML_END
<tr>
	<td class="subjectname">{$kamoku_name}</td>
	<td><a href={$edit_cgi}?xmlfile={$kamoku_ID}.xml&teacher={$teacher}&depart={$depart}>編集</a></td>
	<td>
HTML_END;
				if(file_exists($XML_PDF_D.$kamoku_ID.".pdf")){
					print <<< HTML_END
<a href="{$XML_PDF_D}{$kamoku_ID}.pdf" target="_blank">PDF</a>

HTML_END;
				}
				if(file_exists($XML_PDF_D.$kamoku_ID.".html")){
					print <<< HTML_END
(<a href="{$XML_PDF_D}{$kamoku_ID}.html" target="_blank">html</a>)

HTML_END;
				}
				print "</td>\n";
				if($ok == "0")	print "\t<td class=\"uncomp\">未完了</td>\n";
				else			print "\t<td class=\"comp\">完了</td>\n";
				print <<< HTML_END
	<td><a href={$kamoku_delete_cgi}?teacher={$teacher}&kamokuID={$kamoku_ID}&depart={$depart}>削除</a></td>
</tr>

HTML_END;
			}
		}
	}

// 共通項目
	print <<< HTML_END
</table>
</div>
<div id="right">
	<a href={$kamoku_regist_cgi}?teacher={$teacher}&depart={$depart}&root={$root}>新規科目登録</a><br>
</div>

HTML_END;
}
?>