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
<title>新規登録</title>
</head>
<body>
<h2>【教員新規登録】</h2>
<div id="main">
 <form action="{$user_regist_cgi}" method="POST">
 <div id="box">
  <div class="title">教員ID登録</div>
  <div class="content"><span class="u">メールアドレスを入力してください。</span><br>
   <input type="text" name="teacher" size="40"></div>
  <div class="title">パスワード設定</div>
  <div class="content">半角英数字<span class="red">20字</span>まで<br>
   <input type="password" name="passwd" size="20"></div>
  <div class="title">パスワードの再入力</div>
  <div class="content">確認のためもう一度入力してください。<br>
   <input type="password" name="check_passwd" size="20"></div>
  </div>
  <p><input type="submit" value="登録"><input type="reset" value="リセット"></p>
  <input type="hidden" name="in" value="1">
 </form>
</div>
<a href="./">戻る</a>
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
	
	// 全角文字の存在判定
	
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

function sorting_passfile(){	// ソート
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
<title>新規登録</title>
</head>
<body>
  <h2 style="color:deeppink;">教員ID:{$_POST['teacher']}</h2>登録完了しました。
  <p><a href=$top_cgi>トップページへ</a></p>
 </form>
</body>
</html>
HTML_END;
}

function error($error_flag){
	$error_msg = array();
	
	if($error_flag[0])	$error_msg[0] = '入力された教員はすでに登録されています．';
	if($error_flag[1])	$error_msg[1] = '教員IDが入力されていません．';
	if($error_flag[2])	$error_msg[2] = 'パスワードが入力されていません．';
	if($error_flag[3])	$error_msg[3] = '確認のパスワードと一致しません．';
	if($error_flag[4])	$error_msg[4] = '教員IDに日本語または全角文字が含まれています．';
	
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
<p>以下の項目を確認してください。</p>
HTML_END;
	foreach($error_flag as $key => $value){
		if($value==1) print "・".$error_msg[$key]."<br>\n";
	}
    print <<< HTML_END
<p>登録に失敗しました。</p>
<p>前画面に戻り、もう一度入力してください。</p>
<p><a href="./regist.php">戻る</a></p>
</body>
</html>
HTML_END;

	exit;
}
?>