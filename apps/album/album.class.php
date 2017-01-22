<?php

class AlbumClass{
	public function getTree($catid = 0){
        $tree = $this->cateTree();
        return $this->_sub($tree,$catid);
    }
    //递归分类私有函数
    private function _sub($arr,$parent){
        if(isset($arr[$parent])){
            $tarr = $arr[$parent];
            foreach($tarr as $k=>$v){
                $extra = array(
                    'catename'=>$v['name']
                );
                if($v['dirname']){
                    $extra['dirname'] =$v['dirname'];
                }

                $tarr[$k]['url'] = U('album','cate','id='.$v['id'],$extra);
                $tarr[$k]['sub'] = $this->_sub($arr,$v['id']);
            }
            return $tarr;
        }else{
            return false;
        }
    }
    //按照深度组成一维数组
    private function _deep($catid,$tree,$deep=0,$adds=''){
        if(!isset($tree[$catid])) return array();
        $okArr = array();
        $lnum = count($tree[$catid]);
        foreach($tree[$catid] as $key=>$v){
            $j=$k='';
            $v['deep'] = $deep;
            if($key+1 == $lnum){
                $j .= '└';
            }else{
                $j .= '├';
                $k = $adds ? '│' : '';
            }
            $spacer = $adds ? $adds.$j : '';
            $v['spacer'] = $spacer;//spacer用于显示层级关系
            $okArr[$v['id']] = $v;
            $child = $this->_deep($v['id'],$tree,$deep+1,$adds.$k.'&nbsp;');
            if($child) $okArr = $okArr+$child;
        }
        return $okArr;
    }
    //全部分类
    public function cateTree(){
        $cache = Cache::instance();

        $data = $cache->get('album:cate_tree');
        if(!$data){
            $m_catalog = M('album_cate');
            
            $all = $m_catalog->findAll(array('fields'=>'id,pid,name,dirname,status,sort','order'=>'sort asc,id asc'));
            $sArr = array();
            foreach($all as $cate){
                $sArr[$cate['pid']][] = $cate;
            }
            unset($all);
            $data = $sArr;
            $cache->set('album:cate_tree',$data,array('life_time' => 86400*5));//默认时间改得长一点，5天
        }
        return $data;
    }
    //子分类的id
    public function childIds($catid,$tree){
        if(!isset($tree[$catid])) return array();
        $okArr = array();
        foreach($tree[$catid] as $k=>$v){
            $okArr[] = $v['id'];
            $child = $this->childIds($v['id'],$tree);
            if($child) $okArr = array_merge($okArr,$child);
        }
        return $okArr;
    }
    //当前分类及子分类的id
    public function catIds($id){
        $tree = $this->cateTree();
        return array_merge(array($id),$this->childIds($id,$tree));
    }

    //重新刷新分类缓存
    public function refreshCate(){
        $cache = Cache::instance();
        $cache->remove('album:cate_tree');
    }
    //获取分类索引（TODO:以后可以进一步优化）
    public function getCateIndex(){
        $tree = $this->cateTree();
        $data = array();
        foreach ($tree as $value) {
            foreach ($value as $v) {
                $data[$v['id']] = $v;
            }
        }
        unset($tree);
        return $data;
    }
    //获取分类列表
    public function getCateList($catid = 0){
        $tree = $this->cateTree();
        return $this->_deep($catid,$tree);
    }

    //检查dirname重名
    public function checkDirname($name,$nowid=''){
        $m_catalogs = M('album_cate');
        $where = "dirname=".$m_catalogs->escape($name);
        if($nowid){
            $where .= " and id<>".intval($nowid);
        }
        $data = $m_catalogs->findRow($where);
        if($data){
            return true;
        }
        return false;
    }

    //更新相册图片数量
    public function updatePhotoNum($album_id){
        $num = M('album_photos')->count('album_id='.intval($album_id).' and deleted=0');
        return M('albums')->update($album_id,array('photos_num'=>$num));
    }

    //更新相册封面
    public function updateCover($album_id,$photo_id=0){
        if($photo_id){
            $photoInfo = M('album_photos')->load($photo_id);
        }else{
            $photoInfo = M('album_photos')->findRow(array(
                'where'=>'album_id='.intval($album_id).' and deleted=0',
                'limit' => 1
            ));
        }
        if($photoInfo){
            return M('albums')->update($album_id,array('cover_id'=>$photoInfo['id'],'cover_path'=>$photoInfo['path']));
        }

        return true;
    }
    //检查相册封面
    public function checkCover($album_id){
        $albumInfo = M('albums')->load(intval($album_id));
        if(!$albumInfo){
            return false;
        }
        $coverInfo = M('album_photos')->load($albumInfo['cover_id']);
        if($coverInfo && $coverInfo['deleted']==0 && $photo['album_id'] == $album_id){
            return ture;
        }
        return $this->updateCover($album_id);
    }
}