<?php
require_once('value.php');

$mode = $_POST['mode'];

print <<< HTML_END
<?xml version="1.0" encoding="EUC-JP"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=EUC-JP" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title>����Х������Խ�����</title>
<link rel="stylesheet" href="css/edit.css" type="text/css" />
</head>
<body>
HTML_END;
if($mode == "ed"){
	write();
}
else{
	ed();
}

function write(){
	global $certification_cgi;
	
	if($_POST['teacherID'])		$teacherID = $_POST['teacherID'];
	elseif($_GET['teacherID'])	$teacherID = $_GET['teacherID'];
	$depart = $_POST['depart'];
	
	if($_POST['root'])			$root = $_POST['root'];
	elseif($_GET['root'])		$root = $_GET['root'];
	
	$name_ja				= binarycheck($_POST['name_ja']);
	$name_en				= binarycheck($_POST['name_en']);
	for($i=0;$i<2;$i++){
		$teacher[$i]	= binarycheck($_POST['teacher'.$i]);
		$teacher_attribute[$i]	= $_POST['teacher_attribute'.$i];
		if($teacher_attribute[$i] != "part"){
			$teacher_attribute[$i] = "full";
		}
	}
	$student				= binarycheck($_POST['student']);
	$subject_form			= binarycheck($_POST['subject_form']);
	$choice					= binarycheck($_POST['choice']);
	$credit					= binarycheck($_POST['credit']);
	$outline				= binarycheck($_POST['outline']);
	$object					= binarycheck($_POST['object']);
	$attention_necessary	= binarycheck($_POST['attention_necessary']);
	$attention_others		= binarycheck($_POST['attention_others']);
	$subject_plan_attribute	= binarycheck($_POST['subject_plan_attribute']);
	$endtype				= $_POST['endtype'];
	if($subject_plan_attribute == "list"){
		for($i=0;$i<15;$i++){
			$subject_plan[$i] = binarycheck($_POST['subject_plan'.$i]);
		}
	}
	else{
		$subject_plan = binarycheck($_POST['subject_plan']);
	}
	$granding				= binarycheck($_POST['granding']);
	$relation				= binarycheck($_POST['relation']);
	$text_book				= binarycheck($_POST['text_book']);
	$text_books 			= split("\n",$text_book);
	$ref_book				= binarycheck($_POST['ref_book']);
	$ref_books 				= split("\n",$ref_book);
	$remark					= binarycheck($_POST['remark']);
	$room_num				= binarycheck($_POST['room_num']);
	$e_mail					= binarycheck($_POST['e_mail']);
	
	$writefile				= binarycheck($_POST['writefile']);
	$simplefilename			= binarycheck($_POST['simplefilename']);
	
	$XML = fopen($writefile,"w") or die("open error");
	$buf = <<< XML_END
<?xml version="1.0" encoding="EUC-JP" ?>

<syllabus>
	<subject_code>{$subject_code}</subject_code>
	<subject_name>
		<name_ja>{$name_ja}</name_ja>
		<name_en>{$name_en}</name_en>
	</subject_name>
	<teachers>

XML_END;
	for($i=0;$i<2;$i++){
		$buf .= "\t\t<teacher em_form=\"".$teacher_attribute[$i]."\">".$teacher[$i]."</teacher>\n";
	}
	$buf .= <<< XML_END
	</teachers>
	<student>{$student}</student>
	<subject_form>{$subject_form}</subject_form>
	<choice>{$choice}</choice>
	<credit>{$credit}</credit>
	<outline>

XML_END;
	$buf .= write_print_double($outline);
	$buf .= <<< XML_END
	</outline>
	<object>

XML_END;
	$buf .= write_print_double($object);
	$buf .= <<< XML_END
	</object>
	<attention>
		<necessary>

XML_END;
	$buf .= write_print_double($attention_necessary);
	$buf .= <<< XML_END
		</necessary>
		<others>

XML_END;
	$buf .= write_print_double($attention_others);
	$buf .= <<< XML_END
		</others>
	</attention>
	<subject_plan form="{$subject_plan_attribute}">

XML_END;
	if($subject_plan_attribute == "list"){
		for($i=0;$i<15;$i++){
			$buf .= "\t\t<li><p>".$subject_plan[$i]."</p></li>\n";
		}
	}
	else{
		$buf .= write_print_double($subject_plan);
	}
	$buf .= <<< XML_END
	</subject_plan>
	<granding>

XML_END;
	$buf .= write_print_double($granding);
	$buf .= <<< XML_END
	</granding>
	<relation>

XML_END;
	$buf .=write_print_double($relation);
	$buf .= <<< XML_END
	</relation>
	<text>
		<text_books>

XML_END;
	foreach($text_books as $value){
		$value = rtrim($value);
		$buf .= "\t\t\t<book>".$value."</book>\n";
	}
	$buf .= <<< XML_END
		</text_books>
		<ref_books>

XML_END;
	foreach($ref_books as $value){
		$value = rtrim($value);
		$buf .= "\t\t\t<book>".$value."</book>\n";
	}
	$buf .= <<< XML_END
		</ref_books>
	</text>
	<contact>
		<room_num>{$room_num}</room_num>
		<e_mail>{$e_mail}</e_mail>
	</contact>
	<remark>

XML_END;
	$buf .= write_print_double($remark);
	$buf .= <<< XML_END
	</remark>
</syllabus>
XML_END;
	fwrite($XML,$buf);
	fclose($XML);
	
	changelist($name_ja,$endtype);
	print "<div class=\"head\"><h1>�������᡼������</h1></div>\n";
	make_pdf($depart,$simplefilename);
	
	if($root == 1) $teacherID = "root";
	
	print <<< HTML_END
<p>PDF�ե�������������ޤ�����</p>
<p><a href="{$certification_cgi}?teacher={$teacherID}&mode=internal">���ܰ������̤����</a></p>
</body>
</html>
HTML_END;
}

function binarycheck($binary){
	$binary = preg_replace('/</','��',$binary);
	$binary = preg_replace('/>/','��',$binary);
	$binary = preg_replace('/_/','��',$binary);
	$binary = preg_replace('/\"/','��',$binary);
	$binary = preg_replace('/\'/','��',$binary);
	$binary = preg_replace('/#/','��',$binary);
	$binary = preg_replace('/%/','��',$binary);
	return rtrim($binary);
}
function write_print_double($str){
	$result_double = split("\n",$str);
	foreach($result_double as $value){
		$buf .= "\t\t<p>".rtrim($value)."</p>\n";
	}
	return $buf;
}

function ed(){
	global $edit_cgi;
	if($_POST['teacher'])		$teacherID = $_POST['teacher'];
	elseif($_GET['teacher'])	$teacherID = $_GET['teacher'];
	if($_POST['root'])			$root = $_POST['root'];
	elseif($_GET['root'])		$root = $_GET['root'];

	$xmlfile = $_GET{'xmlfile'};
	$depart = $_GET{'depart'};
	
	$XML_D= "./data/".$depart."/xml/";
	$receive_file = $XML_D.$xmlfile;
	
	$writefile = $receive_file;
	$simplefilename = $xmlfile;
	
	$xml = simplexml_load_file($receive_file);
	
	$endtype = getstate($simplefilename,$depart);
	if($endtype == "complete"){
		$end_t_r = "";
		$end_t_c = "checked";
	}
	else{
		$end_t_r = "checked";
		$end_t_c = "";
	}
	
	$name_ja				= xml_encode($xml->subject_name->name_ja);
	$name_en				= $xml->subject_name->name_en;
	$teacher[0]				= xml_encode($xml->teachers->teacher[0]);
	if($xml->teachers->teacher[0]['em_form'] == "part") $te_att0 = "checked";
	$teacher[1]				= xml_encode($xml->teachers->teacher[1]);
	if($xml->teachers->teacher[1]['em_form'] == "part") $te_att1 = "checked";
	$student				= xml_encode($xml->student);
	$subject_form			= xml_encode($xml->subject_form);
	$choice					= xml_encode($xml->choice);
	$credit					= $xml->credit;
	$outline				= readvalue_double($xml->outline->p);
	$object					= readvalue_double($xml->object->p);
	$attention_necessary	= readvalue_double($xml->attention->necessary->p);
	$attention_others		= readvalue_double($xml->attention->others->p);
	$subject_plan_attribute	= $xml->subject_plan['form'];
	if($subject_plan_attribute == "list"){
		for($i=0;$i<=14;$i++){
			$subject_plan[$i] = xml_encode($xml->subject_plan->li[$i]->p);
		}
		$su_pl_att_l = "checked";
		$su_pl_att_f = "";
	}
	else{
		$subject_plan		= xml_encode($xml->subject_plan);
		$su_pl_att_l = "";
		$su_pl_att_f = "checked";
	}
	$granding				= readvalue_double($xml->granding->p);
	$relation				= readvalue_double($xml->relation->p);
	$text_book				= readvalue_double($xml->text->text_books->book);
	$ref_book				= readvalue_double($xml->text->ref_books->book);
	$room_num				= $xml->contact->room_num;
	$e_mail					= xml_encode($xml->contact->e_mail); // "e-mail" ����ɽ������ʤ�
	$others					= xml_encode($xml->contact->others);
	$remark					= readvalue_double($xml->remark->p);
	$page					= $xml->page;
	
	print <<< HTML_END
<div class="head"><h1>����Х������Խ��ڡ���</h1></div><br />
<div align="center"><div class="title"><h1>����Х���Ͽ��ǧ���ѹ��ե�����</h1></div></div><br />
<form action="{$edit_cgi}" method="POST">
<input type="hidden" name="mode" value="ed" />
<input type="hidden" name="teacher_number" value="1" />
<input type="hidden" name="subject_plan_number" value="14" />
<input type="hidden" name="writefile" value="{$writefile}" />
<input type="hidden" name="simplefilename" value="{$simplefilename}" />
<input type="hidden" name="teacherID" value="{$teacherID}" />
<input type="hidden" name="depart" value="{$depart}" />
<input type="hidden" name="root" value="{$root}" />
<table id="edit" cellspacing="0">
<tr>
<th class="column" colspan="8">�����̾�γ�̤ϡҡӤǤ��ꤤ���ޤ���</th>
</tr>
<tr>
	<td class="column">���ܸ����̾<br />�ҵ����̾��</td>
	<td class="in" colspan="3"><input type="text" name="name_ja" size="50" value="{$name_ja}" /></td>
	<td class="column">�Ѹ����̾<br />�ҵ����̾��</td>
	<td class="in" colspan="3"><input type="text" name="name_en" size="50" value="{$name_en}" /></td>
</tr>
<tr>
	<td class="column">ô������</td>
	<td class="in" colspan="3">
	<input type="text" name="teacher0" size="24" value="{$teacher[0]}" /> <input type="checkbox" name="teacher_attribute0" value="part" {$te_att0} /> ����<br />
	<input type="text" name="teacher1" size="24" value="{$teacher[1]}" /> <input type="checkbox" name="teacher_attribute1" value="part" {$te_att1} /> ����</td>
	<td class="column">ô������ɽ�ؤ�<br />Ϣ����</td>
	<td class="in" colspan="3">�����ֹ�<input type="text" size="6" name="room_num" value="{$room_num}" />,<br /> �Żҥ᡼��<input type="text" size="30" name="e_mail" value="{$e_mail}" /></td>
</tr>
<tr>
	<td class="column">�оݳ���</td>
	<td class="in" colspan="3"><input type="text" name="student" size="40" value="{$student}" /></td>	  
	<td class="column">���Ȥη���</td>
	<td class="in" colspan="3">
		<select name="subject_form">
			<option value="{$subject_form}">{$subject_form}</option>
			<option value="�ֵ�">�ֵ�</option>
			<option value="�齬">�齬</option>
			<option value="�¸�">�¸�</option>
			<option value="�µ�">�µ�</option>
			<option value="�ֵ��µ�">�ֵ����µ�</option>
		</select>
	</td>
</tr>
<tr>
<td class="column">����ɬ������</td>
<td class="in" colspan="3">
	<select name="choice">
		<option value="{$choice}">{$choice}</option>
		<option value="����">����</option>
		<option value="ɬ��">ɬ��</option>
		<option value="����ɬ��">����ɬ��</option>
	</select></td>
<td class="column">ñ�̿�</td>
<td class="in" colspan="3">
	<select name="credit">
		<option value="{$credit}">{$credit}</option>
		<option value="2">2</option>
		<option value="1">1</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="8">8</option>
	</select>
</td>
</tr>
<tr>
	<td class="column">��ά</td>
	<td class="in" colspan="7"><textarea cols="103" rows="5" name="outline">{$outline}</textarea></td>
</tr>
<tr>
	<td class="column">���Ȳ��ܤ�<br />��ɸ</td>
	<td class="in" colspan="7"><textarea cols="103" rows="5" name="object">{$object}</textarea></td>
</tr>
<tr>
	<td class="column" rowspan="2">����������</td>
	<td class="column">���������׷��</td>
	<td class="in" colspan="6"><input type="text" name="attention_necessary" value="{$attention_necessary}" size="103" /></td>
</tr>
<tr>
	<td class="column">�ʤ���¾��</td>
	<td class="in" colspan="6"><input type="text" name="attention_others" value="{$attention_others}" size="103" /></td>
</tr>
<tr>
	<td class="column">���ȷײ��<br />������ˡ</td>
	<td class="in" colspan="7">
	<input type="radio" name="subject_plan_attribute" value="list" {$su_pl_att_l} />�������塼������ʿ侩��
	<input type="radio" name="subject_plan_attribute" value="free" {$su_pl_att_f} />��ͳ����
	</td>
</tr>
<tr>
	<td class="column" rowspan="4">���ȷײ�</td>
	<td class="column" colspan="7">�ʥ������塼�������</td>
</tr>
<tr>
	<td colspan="7"><ol>
		<li><input type="text" size="108" name="subject_plan0" value="{$subject_plan[0]}" /></li>
		<li><input type="text" size="108" name="subject_plan1" value="{$subject_plan[1]}" /></li>
		<li><input type="text" size="108" name="subject_plan2" value="{$subject_plan[2]}" /></li>
		<li><input type="text" size="108" name="subject_plan3" value="{$subject_plan[3]}" /></li>
		<li><input type="text" size="108" name="subject_plan4" value="{$subject_plan[4]}" /></li>
		<li><input type="text" size="108" name="subject_plan5" value="{$subject_plan[5]}" /></li>
		<li><input type="text" size="108" name="subject_plan6" value="{$subject_plan[6]}" /></li>
		<li><input type="text" size="108" name="subject_plan7" value="{$subject_plan[7]}" /></li>
		<li><input type="text" size="108" name="subject_plan8" value="{$subject_plan[8]}" /></li>
		<li><input type="text" size="108" name="subject_plan9" value="{$subject_plan[9]}" /></li>
		<li><input type="text" size="108" name="subject_plan10" value="{$subject_plan[10]}" /></li>
		<li><input type="text" size="108" name="subject_plan11" value="{$subject_plan[11]}" /></li>
		<li><input type="text" size="108" name="subject_plan12" value="{$subject_plan[12]}" /></li>
		<li><input type="text" size="108" name="subject_plan13" value="{$subject_plan[13]}" /></li>
		<li><input type="text" size="108" name="subject_plan14" value="{$subject_plan[14]}" /></li>
	</ol></td>
</tr>
<tr>
	<td class="column" colspan="7">�ʼ�ͳ������</td>
</tr>
<tr>
	<td colspan="7" class="in"><textarea cols="103" rows="15" name="subject_plan">
HTML_END;
	if($subject_plan_attribute != "list") print $subject_plan;
	print <<< HTML_END
</textarea></td>
</tr>
<tr>
	<td class="column">����ɾ��</td>
	<td colspan="7" class="in"><textarea cols="103" rows="2" name="granding">{$granding}</textarea></td>
</tr>
<tr>
	<td class="column">��Ϣ���Ȳ���</td>
	<td colspan="7" class="in"><textarea cols="103" rows="2" name="relation">{$relation}</textarea></td>
</tr>
<tr>
	<td class="column"rowspan="2">����</td>
	<td class="column">���ʽ�</td>
	<td colspan="6" class="in"><textarea cols="87" rows="3" name="text_book">{$text_book}</textarea></td>
</tr>
<tr>
	<td class="column">���ͽ�</td>
	<td colspan="6" class="in"><textarea cols="87" rows="3" name="ref_book">{$ref_book}</textarea></td>
</tr>
<tr>
	<td class="column">����</td>
	<td colspan="7" class="in"><textarea cols="103" rows="2" name="remark">{$remark}</textarea></td>
</tr>
<tr>
	<td class="column"rowspan="2">�Խ���˥塼</td>
	<td class="in" colspan="5"><input type="radio" name="endtype" value="reserve" {$end_t_r} />�����¸
		<span class="explan">
		�������Խ����Ƥ���Ū����¸�������<br />����ɤ��γ�ǧ�򤹤�������򤷤Ƥ���������</span>
	</td>
	<td class="column" rowspan="2" align="center"><input type="submit" value="���ϴ�λ" /></td>
	<td class="column" rowspan="2" align="center"><input type="reset" value="���Ƥ��ľ��" /></td>
</tr>
<tr>
	<td class="in" colspan="5"><input type="radio" name="endtype" value="complete" {$end_t_c} />��λ
		<span class="explan">�������Խ�����λ�����Ȥ������򤷤Ƥ���������<br />
		(��������򤷤Ƥ��Խ��ϲ�ǽ�Ǥ��������ܻ��˼���ʹߤ��Խ���̤�ȿ�Ǥ���ʤ���ǽ��������ޤ���)
		</span>
	</td>
</tr>
</table>
</form>
</body>
</html>
HTML_END;
}

function changelist($name_ja,$endtype){
	$data = array();
	$result = array();
	
	$depart = $_POST['depart'];
	$simplefilename = $_POST['simplefilename'];
	
	$XML_PDF_D		= "./data/".$depart."/pdf/";
	$list_txt		= "./data/".$depart."/xml/list.txt";
	$list_txtnew	= "./data/".$depart."/xml/list.new";

	$check = file($list_txt);
	foreach($check as $value){
		array_push($data,rtrim($value));
	}
	foreach($data as $value){
		list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
		$readdata_xml = $kamoku_ID.".xml";
		if($readdata_xml == $simplefilename){
			if($endtype == "complete"){
				$ok = "1";
			}
			else{
				$ok = "0";
			}
			/*
			if($page != $page_r){
				unlink($XML_PDF_D.$kamoku_ID.".pdf");
			}
			*/
			$setdata = $page.":".$ok.":".$kamoku_ID.":".$teacher_ID.":".$name_ja;
			array_push($result,$setdata);
		}
		else{
			array_push($result,$value);
		}
	}
	$OUT = fopen($list_txtnew,"w");
	foreach($result as $value){
		fwrite($OUT,$value."\n");
	}
	fclose($OUT);
	unlink($list_txt);
	rename($list_txtnew,$list_txt);
}

function getstate($simplefilename,$depart){
	$list_txt = "./data/".$depart."/xml/list.txt";
	$data = array();
	
	$check = file($list_txt) or die("open error");
	foreach($check as $value){
		array_push($data,rtrim($value));
	}
	foreach($data as $value){
		list($page,$ok,$kamoku_ID,$teacher_ID,$kamoku_name) = explode(":",$value);
		$readdata_xml = $kamoku_ID.".xml";
		if($readdata_xml == $simplefilename){
			if($ok == "1"){
				$endtype = "complete";
			}
			else{
				$endtype = "reserve";
			}
			return $endtype;
		}
	}
}

function xml_encode($value){
	return mb_convert_encoding($value, "EUC-JP", "UTF-8");
}

function readvalue_double($tag){
	foreach($tag as $value){
		$result .= xml_encode($value)."\n";
	}
	return $result;
}

function make_pdf($depart,$simplefilename){
	$XML_D		= "./data/".$depart."/xml/";
	$XML_PDF_D	= "./data/".$depart."/pdf/";
	$workdir	= "./data/".$depart."/workdir";
	list($filename,$ext) = explode(".xml",$simplefilename);
	
	$xmlfile = $XML_D.$simplefilename;
	$xslfile = "./syllabus_pdf.xsl";
	$texfile = $workdir."/".$filename.".tex";
	$dvifile = $workdir."/".$filename.".dvi";
	$pdffile = $XML_PDF_D.$filename.".pdf";
	
	$xml = new DOMDocument;
	$xml->load($xmlfile);
	
	$xsl = new DOMDocument;
	$xsl->load($xslfile);
	
	$proc = new XSLTProcessor;
	$proc->importStyleSheet($xsl);
	
	$OUT = fopen($texfile,"w");
	$buf = $proc->transformToXML($xml);
	fwrite($OUT,$buf);
	fclose($OUT);
	
	exec('platex -kanji=euc -output-directory='.$workdir." ".$texfile);
	exec('dvipdfmx -o '.$pdffile." ".$dvifile);
}