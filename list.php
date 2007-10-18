<?php
require_once('value.php');

print <<< HTML_END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>登録済の科目一覧</title>
<link rel="stylesheet" href="css/SCS.css" type="text/css">
</head>
<body>
<div class="head_list"><h1>登録済の科目一覧(PDF閲覧のみ可能)</h1></div>

HTML_END;

$output_depart = array(	"communication" => "情報通信工学科",
						"systems" => "情報システム工学科",
						"master-c" => "電子情報通信工学専攻",
						"master-s" => "機械情報システム工学専攻",
						"doctor" => "システム工学専攻");

foreach($output_depart as $depart => $depart_ja){
	$list_txt	= "./data/".$depart."/xml/list.txt";
	$XML_D		= "./data/".$depart."/xml/";
	$XML_PDF_D	= "./data/".$depart."/pdf/";
	
	$check = file($list_txt);// or die("open error");
	$sorting = array();
	$lineout = array();
	
	foreach($check as $value){
		$line = explode(":",rtrim($value));
		$tmp = $line[0];
		$line[0] = $line[3];
		$line[3] = $tmp;
		array_push($sorting,$line);
	}
	sort($sorting);
	foreach($sorting as $value){
		$tmp = $value[0];
		$value[0] = $value[3];
		$value[3] = $tmp;
		$imp_value = implode(":",$value);
		array_push($lineout,$imp_value);
	}
	
	print <<< HTML_END
	<div align="center"><div class="title_list"><h1>{$depart_ja}</h1></div></div>
	<table>
	<tr class="ttitle">
		<td class="subjectname">科目名</td>
		<td class="Grootid">教員ID(Mail)</td>
		<td>最終更新日</td>
	</tr>

HTML_END;

	foreach($lineout as $value){
		list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
		$pdfexist = "";
		if(!file_exists($XML_PDF_D.$kamoku_ID.".pdf")) $pdfexist = "None";
		
		if(!($kamoku_ID == "" || $teacher_ID == "" || $kamoku_name == "")){
			print "\t<tr>\n\t\t<td class=\"subjectname\">";
			if($pdfexist != "None"){
				print "<a href=\"".$XML_PDF_D.$kamoku_ID.".pdf\" target=\"_blank\">".$kamoku_name."</a>";
			}
			else{
				print $kamoku_name;
			}
			print "</td>\n\t\t<td class=\"Grootid\">";
			
			if($teacher_ID != "root"){
				print $teacher_ID;
			}
			else{
				print "管理者権限";
			}
			print "</td>\n\t\t<td>";
			
			if($pdfexist != "None"){
				$modtime = filemtime($XML_PDF_D.$kamoku_ID.".pdf");
				print date("Y")."年".date("n")."月".date("j")."日";
			}
			print "</td>\n\t</tr>\n";
		}
	}
	print "</table><br><br>\n";
}

print <<< HTML_END
<a href="{$top_cgi}">トップページに戻る</a>
</body>
</html>
HTML_END;
