<?php
/**
 * $lang(言語)に対する$value(配列の値)を返す。
 * ※デフォルト言語は'ja'
 * 言語が定義されていて辞書に存在しない場合は$key(配列の添字)を返す。
 */
function _L($phrase, $lang = 'ja') {
	if (isset($_GET['lang'])) {
		switch ($_GET['lang']) {
			case 'en' :
				$lang = 'en';
				break;
			default :
				break;
		}
	}

	$dictionary = array();
	
	/* form text */
	$dictionary['ja']['F_CONTACT_EXPLAIN'] = '<p class="fl"><strong>弊社のサービス（お見積り・制作依頼など）に関するお問い合わせは下記のフォームよりお願いします。</strong></p>';
	$dictionary['en']['F_CONTACT_EXPLAIN'] = '<p class="fl"><strong>For inquiry, please complete the following fields and click "Send".</strong></p>';
	
	$dictionary['ja']['F_CATEGORY'] = 'お問い合わせ内容<span>（必須）</span>';
	$dictionary['en']['F_CATEGORY'] = '';
	
	$dictionary['ja']['F_COMPANY'] = '会社名';
	$dictionary['en']['F_COMPANY'] = 'Company/Organization';
	
	$dictionary['ja']['F_FULLNAME'] = 'お名前（必須）';
	$dictionary['en']['F_FULLNAME'] = 'Name(*)';
	
	$dictionary['ja']['F_E-MAIL'] = 'メールアドレス（必須）';
	$dictionary['en']['F_E-MAIL'] = 'E-Mail(*)';
	
	$dictionary['ja']['F_E-MAIL_EXPLAIN'] = '<dl class="astDl"><dt>※</dt><dd>携帯電話のメールアドレスを入力された方は、@imagica-imageworks.co.jpからのメールを受信できるように設定してください。</dd></dl>';
	$dictionary['en']['F_E-MAIL_EXPLAIN'] = '';
	
	$dictionary['ja']['F_TEL'] = 'お電話番号';
	$dictionary['en']['F_TEL'] = 'Phone';
	
	$dictionary['ja']['F_QUESTION'] = 'ご質問（必須）';
	$dictionary['en']['F_QUESTION'] = 'Inquiry/Message(*)';
	
	$dictionary['ja']['F_POLICY'] = 'プライバシー・ポリシー';
	$dictionary['en']['F_POLICY'] = '';
	
	$dictionary['ja']['F_COMPLETE'] = <<<EOL
<p class="fl"><strong>フォームの送信は無事完了しました。</strong></p>
<p>お問い合わせいただきまして誠にありがとうございました。</p><br />
<p>後日、改めて担当者よりメールにてご連絡をさせていただきます。<br />
1週間たってもお返事が届かない場合は恐れ入りますが、再度お問い合わせください。</p>
EOL;
	$dictionary['en']['F_COMPLETE'] = <<<EOL
<p class="fl"><strong>Submit the form was successfully completed. </strong></p>
<p>Thank you very much I received your inquiry. </p><br />
<p>At a later date, we will contact you by e-mail from the person again. <br />
Excuse me if the answer still have not received it after one week, please contact us again.</p>
EOL;
	
	/* Administration tool text */
	$dictionary['ja']['SITENAME'] = '問い合わせ管理システム';
	$dictionary['en']['SITENAME'] = 'InQuiry Administration tool';

	$dictionary['ja']['COPYWRITE'] = '&copy;IMAGICA Imageworks,Inc. All Rights Reserved.';
	$dictionary['en']['COPYWRITE'] = '&copy;IMAGICA Imageworks,Inc. All Rights Reserved.';
	
	$dictionary['ja']['LOGIN'] = 'ログイン';
	$dictionary['ja']['LOGINID'] = 'ログインID';
	$dictionary['ja']['PASSWORD'] = 'パスワード';
	$dictionary['ja']['DASHBOARD'] = 'ダッシュボード';
	$dictionary['ja']['EDIT1_TITLE'] = '返信用メールフォーム';
	$dictionary['ja']['EDIT2_TITLE'] = 'メールテンプレート編集';
	$dictionary['ja']['EDIT3_TITLE'] = 'メールグループ編集';
	$dictionary['ja']['EDIT4_TITLE'] = 'メールアドレス,カテゴリ,メンバー紐付け';
	$dictionary['ja']['EDIT5_TITLE'] = 'グループメンバー編集';
	$dictionary['ja']['EDIT6_TITLE'] = '送信メール閲覧';
	$dictionary['ja']['EDIT7_TITLE'] = 'カテゴリ編集';
	$dictionary['ja']['EDIT8_TITLE'] = 'メンバー編集';
	$dictionary['ja']['EDIT9_TITLE'] = 'カテゴリ,メールテンプレート紐付け';
	$dictionary['ja']['EDIT10_TITLE'] = 'メールアドレス編集';
	$dictionary['ja']['EDIT11_TITLE'] = '受信メール閲覧';
	$dictionary['ja']['EDIT1_LIST1'] = '差出元アドレスリスト';
	$dictionary['ja']['EDIT2_LIST1'] = 'メールテンプレートリスト';
	$dictionary['ja']['EDIT3_LIST1'] = 'メールグループリスト';
	$dictionary['ja']['EDIT4_LIST1'] = 'メールアドレスカテゴリーメンバーリスト';
	$dictionary['ja']['EDIT3_LIST1'] = 'メールグループリスト';
	$dictionary['ja']['EDIT4_LIST1'] = '';
	$dictionary['ja']['EDIT5_LIST1'] = '送信メールリスト';
	$dictionary['ja']['EDIT6_LIST1'] = '';
	$dictionary['ja']['EDIT7_LIST1'] = 'カテゴリリスト';
	$dictionary['ja']['EDIT8_LIST1'] = 'メンバーリスト';
	$dictionary['ja']['EDIT9_LIST1'] = 'カテゴリーテンプレートリスト';
	$dictionary['ja']['EDIT10_LIST1'] = 'メールアドレスリスト';
	$dictionary['ja']['MAIL_ADDRESS'] = 'メールアドレス';
	$dictionary['ja']['MAIL_FROM'] = '差出元アドレス';
	$dictionary['ja']['MAIL_TO'] = '宛先アドレス';
	$dictionary['ja']['MAIL_SUBJECT'] = '件名';
	$dictionary['ja']['MAIL_BODY'] = '本文';
	$dictionary['ja']['NEXT'] = '次へ';
	$dictionary['ja']['BACK'] = '戻る';
	$dictionary['ja']['CONFIRM'] = '確認';

	return (!array_key_exists($phrase, $dictionary[$lang])) ? $phrase : $dictionary[$lang][$phrase];
}
?>