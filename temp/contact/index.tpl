<?php include("mailform_header.html"); ?>

<p class="txtalignRight"><a href="http://www.imagica-imageworks.co.jp/company/recruit.html"><img src="btn_rec.gif" alt="採用情報はこちら" /></a></p>

<p class="fl"><strong>弊社のサービス（お見積り・制作依頼など）に関するお問い合わせは下記のフォームよりお願いします。</strong></p>

<form method="post" action="confirm.php" name="toiawase">

<p id="error"></p>

<!-- formArea -->
<div class="formArea">

<!-----------------------------------------------------------
    <tr>のidは"tr"+"対象名"
    入力チェックを行う必要がある場合はonchange="validateForm();"を使う
------------------------------------------------------------->
<table>

<tr id="trcategory">
<th>カテゴリ<span>（必須）</span></th>
<td>
<?php
$c = new category();
$s1 = array("name" => "category", "id" => "selectcategory", "class" => "", "other"=>'');
echo $c -> makeSelectSc($s1, 0);
?>
</td>
</tr>

<tr id="trcompany">
<th>会社名</th>
<td><input class="txtF01" type="text" name="company" id="txtcompany"/></td>
</tr>

<tr id="trusername">
<th>お名前<span>（必須）</span></th>
<td><input class="txtF02" type="text" name="username" id="txtusername"/></td>
</tr>

<tr id="tremail">
<th>メールアドレス<span>（必須）</span></th>
<td><input class="txtF03" type="text" name="email" id="txtemail" />
<dl class="astDl">
<dt>※</dt>
<dd>携帯電話のメールアドレスを入力された方は、@imagica-imageworks.co.jpからのメールを受信できるように設定してください。</dd>
</dl>
</td>
</tr>

<tr id="trtel">
<th>お電話番号</th>
<td><input class="txtF04" type="text" name="tel" id="txttel"/></td>
</tr>

<tr id="trcontent">
<th>お問い合わせ内容<span>（必須）</span></th>
<td>
<textarea rows="20" cols="50" name="content" id="tacontent""></textarea>
</td>
</tr>
</table>

<!-- /formArea --></div>

<div class="pCheck rowElem">
    <input id="chkpolicy" type="checkbox" name="policy" onchange="buttonView();" />
    <label><a href="popup_policy.html" style="text-decoration:underline; color:#00aee3;" target="_blank">プライバシー・ステートメント</a>に同意する</label>
</div>
<input type="hidden" name="pagemode" value="confirm" />
<?php echo getKEYTAG(); ?>
<p class="txtC"><img name="confirm" id="btnconfirm" src="../mailform/images/btn_confirm_disable.gif" alt="確認" onClick="validateForm();" /></p>
</form>

<?php include("mailform_footer.html"); ?>

</body>
</html>