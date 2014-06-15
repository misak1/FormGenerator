function elm(id){
	return document.getElementById(id);
}
function validateForm(){
	var canpost = true;
	var spaces = " \b\t\v\n\r\f\'\"\\\0";
	var err = "";
	var ccategory = '';
	var cuser = '';
	var cemail = '';
	var ccontent = '';

	// カテゴリのチェック
	if(parseInt(elm('selectcategory').value) == 0){
		canpost = false;
		ccategory = "errorCol";
		err += "カテゴリを選択してください。<br />\r\n";
	}
	
	// お名前のチェック
	if(isNullOrInvalid(elm('txtusername').value, spaces)){
		canpost = false;
		cuser = "errorCol";
		err += "お名前は必須項目です。<br />\r\n";
	}

	// メールアドレスのチェック
	if(isNullOrInvalid(elm('txtemail').value, spaces)){
		canpost = false;
		cemail = "errorCol";
		err += "メールアドレスは必須項目です。<br />\r\n";
	}
	else if(! isValidMailAddress(elm('txtemail').value)){
		canpost = false;
		cemail = "errorCol";
		err += "メールアドレスをご確認ください。<br />\r\n";
	}

	// お問い合わせ内容のチェック
	if(isNullOrInvalid(elm('tacontent').value, spaces)){
		canpost = false;
		ccontent = "errorCol";
		err += "お問い合わせ内容は必須項目です。<br />\r\n";
	}

	// プライバシーポリシーのチェック
	if(!elm('chkpolicy').checked){
		canpost = false;
		err += "プライバシーポリシーにチェックしてください。<br />\r\n";
	}

	if(canpost){
		elm('btnconfirm').src = '../iw_mailform/images/btn_confirm.gif';
		document.toiawase.submit();
	}
	else{
		elm('btnconfirm').src = '../iw_mailform/images/btn_confirm.gif';
	}

	elm('trcategory').className = ccategory;
	elm('trusername').className = cuser;
	elm('tremail').className = cemail;
	elm('trcontent').className = ccontent;
	elm('error').innerHTML = err;
}

function validateForm2(){
	var canpost = true;
	var spaces = " \b\t\v\n\r\f\'\"\\\0";
	var err = "";
	var ccategory = '';
	var cuser = '';
	var cemail = '';
	var ccontent = '';

	// カテゴリのチェック
	if(parseInt(elm('selectcategory').value) == 0){
		canpost = false;
		ccategory = "errorCol";
		err += "カテゴリを選択してください。<br />\r\n";
	}
	
	// お名前のチェック
	if(isNullOrInvalid(elm('txtusername').value, spaces)){
		canpost = false;
		cuser = "errorCol";
		err += "お名前は必須項目です。<br />\r\n";
	}

	// メールアドレスのチェック
	if(isNullOrInvalid(elm('txtemail').value, spaces)){
		canpost = false;
		cemail = "errorCol";
		err += "メールアドレスは必須項目です。<br />\r\n";
	}
	else if(! isValidMailAddress(elm('txtemail').value)){
		canpost = false;
		cemail = "errorCol";
		err += "メールアドレスをご確認ください。<br />\r\n";
	}

	// お問い合わせ内容のチェック
	if(isNullOrInvalid(elm('tacontent').value, spaces)){
		canpost = false;
		ccontent = "errorCol";
		err += "お問い合わせ内容は必須項目です。<br />\r\n";
	}

	// プライバシーポリシーのチェック
	if(!elm('chkpolicy').checked){
		canpost = false;
		err += "プライバシーポリシーにチェックしてください。<br />\r\n";
	}

	if(canpost){
		elm('btnconfirm').src = '../iw_mailform/images/btn_confirm.gif';
		document.toiawase2.submit();
	}
	else{
		elm('btnconfirm').src = '../iw_mailform/images/btn_confirm.gif';
	}

	elm('trcategory').className = ccategory;
	elm('trusername').className = cuser;
	elm('tremail').className = cemail;
	elm('trcontent').className = ccontent;
	elm('error').innerHTML = err;
}


function isNullOrInvalid(s, killtargets){
	// 空文字列か、killtargets だけからなる文字列なら true を返す
	var ret = true;

	if(s === ''){
		ret = true;
	}
	else{
		allinvalid = true;
		for(p = 0; p < s.length; p++){
			c = s.charAt(p);
			if(killtargets.indexOf(c) == -1){
				// killtargets 以外の文字を含むから false
				allinvalid = false;
				break;
			}
		}
		ret = allinvalid;
	}
	return ret;
}
function isNullOrNotFollow(s, allowtargets){
	// 空文字列か、allowtargets 以外を含む文字列なら true を返す
	var ret = true;
	if(s === ''){
		ret = true;
	}
	else{
		allvalid = false;
		for(p = 0; p < s.length; p++){
			c = s.charAt(p);
			if(allowtargets.indexOf(c) == -1){
				// allowtargets 以外の文字を含むから true
				allvalid = true;
				break;
			}
		}
		ret = allvalid;
	}
	return ret;
}
function isValidLocalPart(s){
	// てきとーな addr-spec の local-part 適合性チェック
	// -1 ... 空文字列か、64文字を超えている
	//  0 ... local-part ではない
	//  1 ... dot-atom な local-part
	//  2 ... quoted-string な local-part
	//  3 ... obs-local-part な local-part
	//  4 ... quasi-obs-local-part な local-part
	var ret = 0;
	
	if(s !== '' && s.length <= 64){
		if(s.match(/^[!#-'*+\-/-9=?A-Z^-~]+(\.[!#-'*+\-/-9=?A-Z^-~]+)*$/)){
			// (1) dot-atom なら local-part
			ret = 1;
		}
		else if(s.match(/^\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\"$/)){
			// (2) quoted-string なら local-part
			ret = 2;
		}
		else if(s.match(/^([!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\")(\.([!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\"))*$/)){
			// (3) obs-local-part なら local-part
			ret = 3;
		}
		else if(s.match(/^([.!#-'*+\-/-9=?A-Z^-~]+|\"([ \t]|[!#-\[\]-~]|\\.)*[ \t]*\")+$/)){
			// (4) rfc違反だが、docomo アドレス対応
			ret = 4;
		}
	}
	else{
		ret = -1;
	}
	return ret;
}
function isValidDomainPart(s){
	// てきとーな addr-spec の domain-part 適合性チェック
	//   4 ... gTLD なサブドメイン
	//   3 ... ccTLD なサブドメイン
	//   2 ... gTLD なトップドメイン
	//   1 ... ccTLD なトップドメイン
	//   0 ... 空文字列か 255文字以上
	//  -1 ... '.' がなく、64文字以上
	//  -2 ... '.' がなく、ccTLD でも gTLD でもない
	//  -3 ... '.' で分割して要素が２つ未満... あるはずない
	//  -4 ... '.' で分割して空の要素がある
	//  -5 ... '.' で分割して63文字を超える
	//  -6 ... '.' で分割して rfc1035 な <label> にマッチしない
	var ret = 0;
	if(s !== '' && s.length <= 255){
		if(0 <= s.indexOf('.')){
			labels = s.split('.');
			if(2 <= labels.length){
				failed = false;
				for(i = 0; i < labels.length; i++){
					if(labels[i] === ''){
						// 空要素なら即座に判定終了
						failed = true;
						ret = -4;
						break;
					}
					else if(63 < labels[i].length){
						// label が 63文字を超えてはならない
						failed = true;
						ret = -5;
						break;
					}
					else if(!labels[i].match(/^[a-zA-Z]+[\-a-zA-Z0-9]*[a-zA-Z0-9]*$/)){
						// rfc1035 な <label> でないなら判定終了
						failed = true;
						ret = -6;
						break;
					}
				}
				if(!failed){
					// 一応 TLD であるかどうかは確認しよう
					r = isTLD(labels[labels.length - 1]);
					if(r == "ccTLD"){
						ret = 3;
					}
					else if(r == "gTLD"){
						ret = 4;
					}
				}
			}
			else{
				ret = -3;
			}
		}
		else if(s.length < 64){
			// 今のところは cctld か gtld じゃないとダメとしておこう
			r = isTLD(s);
			if(r == "ccTLD"){
				ret = 1;
			}
			else if(r == "gTLD"){
				ret = 2;
			}
			else{
				ret = -2;
			}
		}
		else{
			ret = -1;
		}
	}
	return ret;
}
function isValidMailAddress(s){
	// てきとーなメールアドレスチェック
	var ret = false;
	if(s.length <= 256 && 0 <= s.indexOf('@')){
		// '@' で分割して要素は二つでないといけないことにする
		lst = s.split('@');
		if(lst.length == 2 && lst[0] !== '' && lst[1] !== '' && lst[0].length <= 64 && lst[1].length <= 255){
			islp = isValidLocalPart(lst[0]);
			isdp = isValidDomainPart(lst[1]);
			if(0 < islp && 0 < isdp){
				ret = true;
			}
		}
	}
	return ret;
}
function isTLD(s){
	// 文字列 s が TLD かどうかを返す
	// gTLD なら「gTLD」、sTLD なら「sTLD」、ccTLD なら「ccTLD」、arpa なら「arpa」を返す
	// 2013/03/07 wikipedia で確認
	var arpa =	  'arpa';
	var cctld =	  '|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|'
			+ '|ba|bb|bd|be|bf|bg|bh|bi|bj|bl|bm|bn|bo|br|bs|bt|bu|bv|bw|by|bz|'
			+ '|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|'
			+ '|dd|de|dg|dj|dk|dm|do|dz|'
			+ '|ec|ee|eg|eh|er|es|et|eu|'
			+ '|fi|fj|fk|fm|fo|fr|'
			+ '|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|'
			+ '|hk|hm|hn|hr|ht|hu|'
			+ '|id|ie|il|im|in|io|iq|ir|is|it|'
			+ '|je|jm|jo|jp|'
			+ '|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|'
			+ '|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|'
			+ '|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|'
			+ '|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|'
			+ '|om|'
			+ '|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|'
			+ '|qa|'
			+ '|re|ro|rs|ru|rw|'
			+ '|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|ss|st|su|sv|sy|sz|'
			+ '|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|'
			+ '|ua|ug|uk|um|us|uy|uz|'
			+ '|va|vc|ve|vg|vi|vn|vu|'
			+ '|wf|ws|'
			+ '|ye|yt|yu|'
			+ '|za|zm|zw|';
	var gtld =	  '|aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|xxx|';
	var rtld =	  '|example|invalid|localhost|test|';

	var ss = '|' + s.toLowerCase() + '|';
	var ret = 'NO';
	if(0 <= cctld.indexOf(ss)){
		ret = 'ccTLD';
	}
	else if(0 <= gtld.indexOf(ss)){
		ret = 'gTLD';
	}
	else if(ss == arpa){
		ret = 'arpa';
	}
	return ret;
}
