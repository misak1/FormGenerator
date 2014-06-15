<?php 
/**
 * ページ情報を取得する。
 * @param $record_count 総レコード数
 * @param $pagesize １ページあたりのレコード数
 * @param $page 要求ページ
 * @return
 *   $ary['record_count']; レコードカウント
 *   $ary['page_size'];    指定したページあたり件数
 *   $ary['page_total'];   見つけた FAQ のページ数
 *   $ary['current_page']; ページ位置
 *   $ary['start']; 開始レコード
 *   $ary['end'];   終了レコード
 */
function page_data($record_count, $pagesize, $page) {
    $ary = array();
    $ary['record_count'] = $record_count;
    $ary['page_size'] = 0; // 指定したページあたり件数($reqから取得)
    $ary['page_total'] = 0; // 見つけた FAQ のページ数($reqから取得)
    $ary['current_page'] = 0; // 現在のページ位置($reqから取得)

    /**
     * データ検証
     */
    if (empty($page) || !isNaturalNumber($page)) {
        // pageは0以下を設定されない, 自然数でない
        $page = 1;
    }
    if (empty($pagesize) || !isNaturalNumber($pagesize, TRUE)) {
        // pagesizeは数値がセットされる, (自然数 or 0)でない
        $pagesize = 0;
    }

    /**
     * $ary['page_total']の取得
     */
    if ($pagesize == 0) {
        // pagesize に満たない場合、page に 1 が指定され
        $ary['page_total'] = 1;
    } else {
        // 1以上の場合は、その項目数を１ページの上限とする。
        $div = intval(ceil($record_count / $pagesize));
        $ary['page_total'] = $div;
        if ($ary['page_total'] <= 0) {
            $ary['page_total'] = 1;
        }
    }

    /**
     * $ary['current_page']の取得
     */
    if ($page >= $ary['page_total']) {
        $ary['current_page'] = $ary['page_total'];
    } else {
        // $pageが自然数で最大値を超えない場合はそのまま設定される。
        $ary['current_page'] = $page;
    }

    /**
     * $ary['page_size']の取得
     */
    if ($pagesize == 0) {
        // pagesize にゼロが指定されたら、pagesize の上限がないものとして処理
        $ary['page_size'] = $record_count;
    } else {
        // 1以上の場合は、その項目数を１ページの上限とする。
        $mod = intval($record_count % $pagesize);
        if ($mod == 0) {
            // 割り切れる場合
            $ary['page_size'] = $pagesize;
        } else {
            // 割り切れない場合
            if ($ary['current_page'] < $ary['page_total']) {
                $ary['page_size'] = $pagesize;
            } else {
                // 最後のページ
                $ary['page_size'] = $mod;
            }
        }
    }

    /**
     * start - end
     */
    $end = $ary['current_page'] * $pagesize;
    $start = $end - $pagesize;
    if ($pagesize == 0) {
        $end = $record_count;
    }
    if ($pagesize == 0) {
        $start = 0;
    }
    if ($start <= 0) {
        $start = 0;
    }
    $ary['start'] = $start;
    $ary['end'] = $end;

    return $ary;
}

/**
 * 自然数チェック {1,2,...}
 * $isZero 0を許容するか否か
 */
function isNaturalNumber($input, $isZero = FALSE) {
    $x = ($isZero) ? 0 : 1;
    if (strval($input) === strval(intval($input))) {

        if ($input < $x) {
            return FALSE;
        }
        return TRUE;
    } else {
        return FALSE;
    }
}
?>
