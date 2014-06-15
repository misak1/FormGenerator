<?php
/**
 * 公明党アプリ用Utility
 */
class UtilTemplate {

	// FORMテンプレート
	// デフォルト "\n<form{attributes}>\n<table border=\"0\">\n{content}\n</table>\n</form>"
	public static $FO_CLEAR = '
	<form{attributes}>
	{content}
	</form>';

	public static $FO_DETAIL_TWITTER = '
				<form class="form-horizontal" {attributes}>
					<fieldset>
						<legend>アカウント登録・編集</legend>
						<div align="right">
							<a href="%s" target="_blank" class="btn btn-small"><i class="icon-share"></i>表示</a></td>
						</div>
						{content}
						<div class="form-actions" id="pushBtnArea">
							<button type="submit" name="save" value="save" class="btn btn-primary mr20">保存する</button>
							<button type="button" name="delete" value="delete" class="delete btn btn-danger">削除する</button>
							<input type="hidden" name="action" value="" id="submit_action"/>
						</div>
						
					</fieldset>
				</form>';

	public static $FO_DETAIL_NEWS_FLASH = '
				<form class="form-horizontal" {attributes}>
					<fieldset>
						<legend>速報登録・編集</legend>
						{content}
						<div class="form-actions" id="pushBtnArea">
							<button type="submit" name="save" value="save" class="btn btn-primary mr20">保存する</button>
							<button type="button" name="delete" value="delete" class="delete btn btn-danger">削除する</button>
							<input type="hidden" name="action" value="" id="submit_action"/>
						</div>
						
					</fieldset>
				</form>';

	public static $FO_DETAIL_MENU = '
				<form class="form-horizontal" {attributes}>
					<fieldset>
						<legend>メニュー登録・編集</legend>
						{content}
						<div class="form-actions" id="pushBtnArea">
							<button type="submit" name="save" value="save" class="btn btn-primary mr20">保存する</button>
							<button type="button" name="delete" value="delete" class="delete btn btn-danger">削除する</button>
							<input type="hidden" name="action" value="" id="submit_action"/>
						</div>
						
					</fieldset>
				</form>';

	public static $FO_PUSH_DL = '
				<form class="form-horizontal" {attributes}>
					<fieldset>
						{content}
						<div class="form-actions" id="pushBtnArea">
							<button type="submit" name="save" value="save" class="btn btn-primary mr20">CSVダウンロード</button>
						</div>
						
					</fieldset>
				</form>';

	// ELEMENTテンプレート
	// デフォルト "\n\t<tr>\n\t\t<td align=\"right\" valign=\"top\"><!-- BEGIN required --><span style=\"color: #ff0000\">*</span><!-- END required -->{element}</td>\n\t\t<td valign=\"top\" align=\"left\"><!-- BEGIN error --><span style=\"color: #ff0000\">{error}</span><br /><!-- END error -->\t<b>{label}</b></td>\n\t</tr>", "news[Y]"
	public static $EL_LABEL_RIGHT = '
	{element}{label}
	';

	public static $EL_LABEL_LEFT = '
	{label}{element}
	';

	public static $EL_MESAGE001 = '
						<span class="form-title">日付で絞り込む</span>
							{element}{label}
	';

	public static $EL_MESAGE002 = '
						<span class="form-title">過去の注目の記事</span>
							{element}{label}
	';

	// Twitterとメニューは共通
	public static $EL_DETAIL_TWITTER = '
						<div class="control-group">
							<label class="control-label" for="input01">{label}</label>
							<div class="controls">
								{element}
							</div>
						</div>';

	public static $EL_DETAIL_NEWS_ST1 = '
						<div class="control-group">
							<label class="control-label" for="input01">表示開始日</label>
							<div class="controls">
							{element}
							{label}
	';

	public static $EL_DETAIL_NEWS_ST2 = '
						<div class="control-group">
							<label class="control-label" for="input01">表示終了日</label>
							<div class="controls">
							{element}
							{label}
	';

	// NEWS_FLASHとPUSH_DOWNLOAD共通
	public static $EL_DETAIL_NEWS_END = '
							{element}
							{label}
							</div>
						</div>
	';

	public static $EL_DETAIL_PUSH_DL_ST1 = '
						<div class="control-group">
							<label class="control-label" for="input01">開始日</label>
							<div class="controls">
							{element}
							{label}
	';

	public static $EL_DETAIL_PUSH_DL_ST2 = '
						<div class="control-group">
							<label class="control-label" for="input01">終了日</label>
							<div class="controls">
							{element}
							{label}
	';
}
?>