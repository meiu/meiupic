<?php

class x_comment_helper{
	//载入评论
    public static function comment($mod,$rel_id){
        $setting = app('base')->getSetting('comment_setting',true);

        $html = '<link rel="stylesheet" href="'.S('comment','css/style.css').'" type="text/css" />'."\n";
        if(!$setting['open_comment']){
            $html .= '<div class="mc-thread"><div class="comment-closed">评论已关闭！</div></div>';
            return $html;
        }
        $html .= '<script type="text/javascript" src="'.S('comment','js/comment.js').'"></script>'."\n";
        $html .= '<div class="mc-thread" data-thread-mod="'.$mod.'" data-id="'.$rel_id.'" ';
        $html .= 'data-options=\'{"login_url":"'.U('user','login').'","logout_url":"'.U('user','login','a=logout').'","list_url":"'.U('comment','list','mod='.$mod.'&id='.$rel_id).'","add_url":"'.U('comment','add','mod='.$mod.'&id='.$rel_id.'&isajax=1').'","appraise_url":"'.U('comment','appraise','mod='.$mod.'&id='.$rel_id.'&isajax=1').'","captcha_url":"'.U('base','captcha').'"}\'';
        $html .= '><div class="mc-comment-loading">加载评论中...</div></div>'."\n";
        return $html;
    }
}