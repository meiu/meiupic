<?php
/**
 * 相册基本数据调用
 * 可以在模版中调用
 */

defined('IN_MWEB') || exit('Access denied!');

class x_album_helper{

    static function cate_list(){
        $list = app('album')->getCateList();
        return array('list'=>$list);
    }

    /**
     * 作品列表
     * 例如 album::works(4,'cate_id=1&tag=美景,美女')
     */
    static function works($num=20,$query=''){
        global $_G;

        if(is_array($query)){
            $params = $query;
            $query = implode('&', $query);
        }else{
            parse_str($query,$params);
        }

        if(isset($params['cache']) && $params['cache']>0){
            $cacheTime = $params['cache'];
        }else{
            $cacheTime = 0;
        }

        $returnData = false;
        if($cacheTime > 0){
            $cacheKey = md5('albumlist_'.$num.'_'.$query);
            $cache = Cache::instance();
            $returnData = $cache->get($cacheKey);
        }

        if(!$returnData){
            $m_album = M('albums');

            if(isset($params['sort']) && in_array($params['sort'], array('desc','asc'))){
                $sort = $params['sort'];
            }else{
                $sort = 'desc';
            }

            if(isset($params['orderby'])){
                $order_sql = 'a.'.$params['orderby'].' '.$sort;
            }else{
                $order_sql = 'a.id desc';
            }

            $where = 'a.deleted=0 AND a.priv_type=0';//不取私密的作品
            if($params['recommended']){
                $where .= ' AND a.recommended=1';
            }
            if($params['uid']){
                $where .= ' AND a.uid='.intval($params['uid']);
            }
            if($params['name']){
                $where .= " AND a.name like '%".$m_album->escape($params['name'],false)."%'";
            }
            if($params['cate_id']){
                $catids = app('album')->catIds($params['cate_id']);
                $where .= ' AND a.cate_id in ('.implode(',', $catids).')';
            }
            $table = '#album as a';
            //筛选tags
            if($params['tag']){
                if(preg_match('/^[0-9,]+$/', $params['tag'])){
                    $tags_arr=explode(',',$params['tag']);
                    $tags_arr=array_map('intval',$tags_arr);
                }else{
                    $tags_sarr=explode(',',$params['tag']);
                    $tags_sarr=array_map('safestr',$tags_sarr);
                    //取出TagId列表
                    $tags_arr = M('album_tags')->select(array('fields'=>'id','where'=>"name in ('".implode("','", $tags_sarr)."')"))->getCol();
                }
                if($tags_arr){
                    $table = '#album_tag_rels as r inner join #albums as a on r.rel_id = a.id';
                    $where .= ' AND r.type="album" AND r.tag_id in ('.implode(',', $tags_arr).')';
                }
            }
            
            $limit['start'] = 0;
            $limit['limit'] = $num;

            $cateIndex = app('album')->getCateIndex();
            $rows = $m_album->findAll(array(
                'fields' => 'a.*',
                'table' => $table,
                'where' => $where,
                'start' => $limit['start'],
                'limit' => $limit['limit'],
                'order' => $order_sql
            ));

            if($_G['user']['id']){
                $likeIndexs = M('album_likes')->select('uid='.$_G['user']['id'].' and album_id in ('.implode(',',array_column($rows, 'id')).')')->getAsIndex('album_id');
            }
            $userInfos = M('users')->select(array('where' =>'id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')','fields'=>'id,username,mobile,email,nickname,description,friends,followers,gender,points,regtime,regip,logintime,loginip,facever,bgver'))->getAsIndex('id');
            foreach ($rows as $key => $value) {
                $rows[$key]['cate_name'] = $cateIndex[$value['cate_id']]?$cateIndex[$value['cate_id']]['name']:'';
                $rows[$key]['user'] = $userInfos[$value['uid']];
                $rows[$key]['liked'] = 0;
                if($_G['user']['id'] && isset($likeIndexs[$value['id']])){
                    $rows[$key]['liked'] = 1;
                }
                $rows[$key]['url'] = U('album','album_detail','id='.$value['id']);
            }
            $data['list'] = $rows;
            
            $returnData = $data;
            unset($data);
            if($cacheTime > 0){
                $cache->set($cacheKey,$returnData,array('life_time' => $cacheTime));
            }
        }
        return $returnData;
    }

    //作品基本介绍
    static function work_info($album_id){
        $m_album = M('albums');
        $album_info = $m_album->load($album_id);

        $liked = M('album_likes')->findRow('uid='.$_G['user']['id'].' and album_id='.intval($album_id));

        $album_info['liked'] = $liked?1:0;
        $user = M('users')->load($album_info['uid'],'id,username,mobile,email,nickname,description,friends,followers,gender,points,regtime,regip,logintime,loginip,facever,bgver');
        $album_info['user'] = $user;
        $album_info['url'] = U('album','album_detail','id='.$album_info['id']);
        return $album_info;
    }

    /**
     * 作品中的图片列表
     */
    static function work_photos($album_id){
        $m_photo = M('album_photos');
        //取出所有的照片吧
        $photos = $m_photo->findAll(array(
            'where' => "album_id=".$album_id." AND deleted=0",
            'fields' => 'id,path,width,height,exif',
            'order' => 'sort asc,id asc'
        ));

        $exif_lib = new exif;
        foreach ($photos as $key => $value) {
            if($value['exif']){
                $photos[$key]['exif'] = $exif_lib->parse_exif(unserialize($value['exif']));
            }
        }
        $data['list'] = $photos;
        return $data;
    }
    /**
     * 获取最新的几个图集列表
     */
    static function sets($num=20){
        $m_album_sets = M('album_sets');
        $where = 'priv_type=0 AND photos_num>0';

        $rows = $m_album_sets->findAll(array(
            'where' => $where,
            'start' => 0,
            'limit' => $num,
            'order' => 'id desc'
        ));

        $userInfos = M('users')->select(array('where' =>'id in ('.implode(',',array_unique(array_column($rows, 'uid'))).')','fields'=>'id,username,mobile,email,nickname,description,friends,followers,gender,points,regtime,regip,logintime,loginip,facever,bgver'))->getAsIndex('id');
        foreach ($rows as $key => $value) {
            $rows[$key]['user'] = $userInfos[$value['uid']];
            $rows[$key]['url'] = U('album','sets_photos','id='.$value['id']);
        }
        $data['list'] = $rows;
        return $data;
    }
    /**
     * 图集中的图片列表
     */
    static function sets_photos($set_id,$num=10){
        $m_photo = M('album_photos');
        //取出所有的照片吧
        $photos = $m_photo->findAll(array(
            'field'=>'p.*',
            'table' => '#album_set_photos as sp inner join #album_photos as p on sp.photo_id = p.id',
            'where' => "sp.set_id=".$set_id." AND p.deleted=0",
            'fields' => 'p.id,p.path,p.album_id,p.uid,p.width,p.height',
            'order' => 'sp.add_time desc',
            'start' => 0,
            'limit' => $num,
        ));

        $data['list'] = $photos;
        return $data;
    }

}