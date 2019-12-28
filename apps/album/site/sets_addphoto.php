<?php 
defined('IN_MWEB') or die('access denied');

checkLogin();

$photo_id = intval(getGet('photo_id'));
if(isPost()){
    $set_id = intval(getRequest('set_id'));
    if(!$photo_id){
        alert('请选择图片！');
    }
    if(!$set_id){
        alert('请选择图集！');
    }
    $setInfo = M('album_sets')->load($set_id);
    if(!$setInfo){
        alert('图集不存在！');
    }
    if($setInfo['uid']!=$_G['user']['id']){
        alert('非法操作，目标图集不属于您！');
    }
    //判断是否添加过
    if(M('album_set_photos')->findRow('set_id='.$set_id.' AND photo_id='.$photo_id)){
        alert('已经添加过，请勿重复添加！');
    }

    $updata = array('photo_id'=>$photo_id,'set_id'=>$set_id,'uid'=>$_G['user']['id'],'add_time'=>CURRENT_TIME);

    $photoInfo = M('album_photos')->load($photo_id);

    if( M('album_set_photos')->insert($updata) ){
        M('album_sets')->update($set_id,array('cover_id'=>$photo_id,'cover_path'=>$photoInfo['path'],'photos_num'=>array('exp','photos_num+1')) );

        alert('添加成功！',true);
    }else{
        alert('添加失败！');
    }
}
$page = getGet('page',1);

$search['photo_id'] = $photo_id;
$search['name'] = trim(getRequest('name'));
$search['winid'] = trim(getRequest('winid'));

$m_sets = M('album_sets');
$where = 'uid='.intval($_G['user']['id']);
if( $search['name'] ){
    $keyword = trim($search['name'],'*');
    $where .= " and name like '%".$m_sets->escape($keyword,false)."%'";
}
$totalCount = $m_sets->count($where);
$pageurl = U('album','sets_addphoto',array_merge($search,array('page'=>'%page%')));

$pager = new Pager($page,12,$totalCount,$pageurl);
$pager->config(C('adminpage'));
$limit = $pager->getLimit();
$view->assign('pagestr',$pager->html());

$rows = $m_sets->findAll(array(
    'where' => $where,
    'start' => $limit['start'],
    'limit' => $limit['limit'],
    'order' => 'id desc'
));

$view->assign('rows',$rows);
$view->assign('search',$search);
$view->assign('totalCount',$totalCount);
$view->display('album/sets_addphoto.php');