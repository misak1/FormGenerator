<?php
class classTemplate {
    
    public function getTemplate() {
        global $log;
        $link = classDb::connect();
        $sql = sprintf("SELECT `template_id`, `mail_subject`, `mail_body` FROM  `mf_ttemplate`;");
        $sql = Util::deleteCRLF($sql);
        $log->info($sql);
        $result = mysql_query($sql, $link);
        $arrayUser = array();
        while ($row = mysql_fetch_assoc($result)) {
            $maillog = array();
            $maillog['template_id']   = $row['template_id'];
            $maillog['mail_subject']  = $row['mail_subject'];
            $maillog['mail_body']     = $row['mail_body'];
            $maillog['effective_flg'] = $row['effective_flg'];
            $arrayUser[] = $maillog;
        }
        classDb::close($link);
        return $arrayUser;
    }

}
?>