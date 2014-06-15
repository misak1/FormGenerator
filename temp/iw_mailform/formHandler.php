<?php
require_once ('contactlib.php');
startSession();
require_once ('reply.class.php');
require_once ('member.class.php');
require_once ('maillog.class.php');
require_once ('classCategory.php');
require_once ('classTemplate.php');
require_once ('replycategorymember.class.php');
require_once ('categorytemplate.class.php');

$classCategory = new classCategory();
$classTemplate = new classTemplate();
$replycategorymember = new replycategorymember();
$categorytemplate = new categorytemplate();
$reply = new reply();
$member = new member();

$category = isset($_SESSION['category']) ? $_SESSION['category'] : '';
$company = isset($_SESSION['company']) ? $_SESSION['company'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$tel = isset($_SESSION['tel']) ? $_SESSION['tel'] : '';
$content = isset($_SESSION['content']) ? $_SESSION['content'] : '';
$policy = isset($_SESSION['policy']) ? $_SESSION['policy'] : '';

foreach ($_POST as $k => $v) {
	writeLog("_POST[" . $k . "]=$v");
}
foreach ($_SESSION as $k => $v) {
	writeLog("_SESSION[" . $k . "]=$v");
}

if (!isValidPage()) {
	writeLog("key not match, redirect to index");
	doRedirect("./");
} else {
	$pagemode = (isset($_POST['pagemode'])) ? $_POST['pagemode'] : 'index';
	writeLog("key matches: pagemode=" . $pagemode);
	if ($pagemode === 'confirm') {
		writeLog("pagemode is confirm");
		$category = (isset($_POST['category'])) ? $_POST['category'] : 0;
		$company = (isset($_POST['company'])) ? $_POST['company'] : '';
		$username = (isset($_POST['username'])) ? $_POST['username'] : '';
		$email = (isset($_POST['email'])) ? $_POST['email'] : '';
		$tel = (isset($_POST['tel'])) ? $_POST['tel'] : '';
		$content = (isset($_POST['content'])) ? $_POST['content'] : '';
		$policy = (isset($_POST['policy'])) ? 1 : 0;
		$_SESSION['category'] = $category;
		$_SESSION['company'] = $company;
		$_SESSION['username'] = $username;
		$_SESSION['email'] = $email;
		$_SESSION['tel'] = $tel;
		$_SESSION['content'] = $content;
		$_SESSION['policy'] = $policy;

		$err = validateForm($category, $company, $username, $email, $tel, $content, $policy);
		writeLog("err=$err");
		if ($err === '') {
			include ('../contact-2014/template/confirm.html');
		} else {
			writeLog("問題があるから差し戻す");
			include ('../contact-2014/template/edit.html');
		}
	} elseif ($pagemode === 'edit') {
		writeLog("pagemode = edit, username=$username");
		include ('../contact-2014/template/edit.html');
	} elseif ($pagemode === 'finish') {
		$r = new reply();
		$mlog = new maillog();
		// TODO

		$replyLs = $reply -> selectIdReply();
		$memberLs = $member -> selectIdName();

		//カテゴリーからfrom,Bcc先を取得
		$dataHs = $replycategorymember -> getReplyCategoryMember();
		foreach ($dataHs as $data) {
			if (!$fromAddress) {
				$fromAddress = $replyLs[$data['reply_id']];
				$Address_str = $data['member_id'];
			}
			if ($data['category_id'] === "$category") {
				$fromAddress = $replyLs[$data['reply_id']];
				$Address_str = $data['member_id'];
				break;
			}
		}
		$eachmemberLs = explode(',', $Address_str);
		$aryToAdmin = array();
		foreach ($eachmemberLs as $eachmember) {
			$aryToAdmin[] = $memberLs[$eachmember];
		}
		//管理者宛メールテンプレート、ユーザ宛メールテンプレートをDBより取得
		$categorytemplateLs = $categorytemplate -> getCategoryTemplate();
		foreach ($categorytemplateLs as $eachtemplate) {
			if ($toUserTemplate == "" && $eachtemplate["type"] === "1") {
				$toUserTemplate = $eachtemplate["template_id"];
			} elseif ($toAdminTemplate == "" && $eachtemplate["type"] === "2") {
				$toAdminTemplate = $eachtemplate["template_id"];
			}
			if ($eachtemplate["category_id"] === $category) {
				if ($eachtemplate["type"] === "1") {
					$toUserTemplate = $eachtemplate["template_id"];
				} elseif ($eachtemplate["type"] === "2") {
					$toAdminTemplate = $eachtemplate["template_id"];
				}
			}
		}

		//管理者宛メールテンプレート、ユーザ宛メールテンプレートからメールタイトルと本文を取得
		$classtemplate = new classTemplate();
		$templateLs = $classtemplate -> getTemplate();
		foreach ($templateLs as $template) {
			if ($template['template_id'] === $toUserTemplate) {
				$toUserSubject = $template['mail_subject'];
				$toUserMbody = $template['mail_body'];
			} elseif ($template['template_id'] === $toAdminTemplate) {
				$toAdminSubject = $template['mail_subject'];
				$toAdminMbody = $template['mail_body'];
			}
		}

		$from = $fromAddress;
		$sender = $fromAddress;
		$aryTo[] = $email;

		$toAdminHeader = classFunction::create_mailHeader($from, $sender, $aryToAdmin, $toAdminSubject);
		$toUserHeader = classFunction::create_mailHeader($from, $sender, $aryTo, $toUserSubject);

		// 2013-05-26 categoryid→category名を取得する個所を追加
		$classcategory = new ClassCategory();
		$category_name = $classcategory -> selectNameFromID($category);

		$toAdminBody = classFunction::create_mailBody($category_name, $company, $username, $email, $tel, $content, $toAdminMbody);
		$toAdminBodyEx = classFunction::create_mailBodyEx($category_name, $company, $username, $email, $tel, $content, $toAdminMbody);
		$toUserBody = classFunction::create_mailBody($category_name, $company, $username, $email, $tel, $content, $toUserMbody);

		$mlog -> insertMailLog($from, $sender, $email, $toAdminSubject, $toAdminHeader, $toAdminBody);
		classFunction::sendMail($toAdminHeader, $toAdminBodyEx);
		classFunction::sendMail($toUserHeader, $toUserBody);

		include ('../contact-2014/template/finish.html');
	}
}
?>