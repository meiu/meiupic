 <?php
defined('IN_MWEB') or die('access denied');

require_once('_submenu.php');

class AlbumCate extends Adminbase{
	function indexAct(){
        $m_catalog = M('album_cate');

        if(isPost()){
            $sort = getPost('sort');
            foreach ($sort as $key => $value) {
                $m_catalog->update($key,array('sort'=>$value));
            }
            app('album')->refreshCate();
            alert('更新排序成功！',true,U('album','cate'));
        }

        $catelist = app('album')->getCateList(0);

        $this->view->assign('catelist',$catelist);
        $this->view->display('cate.php');
    }

    function delAct(){
        $id = intval(getGet('id'));

        $m_catalog = M('album_cate');
        $catainfo = $m_catalog->load($id);
        if(!$catainfo){
            alert('请分类已被删除请勿重复操作！');
        }
        if($m_catalog->count('pid='.$id) > 0){
            alert('请先删除子分类！');
        }

        if($m_catalog->delete($id)){
            app('album')->refreshCate();
            
            alert('删除成功！',true,U('album','cate'));
        }else{
            alert('删除失败！');
        }
    }

    function addAct(){
        $pid = intval(getGet('pid'));
        
        if(isPost()){
            $data = array();
            $data['name'] = trim(getPost('name'));
            $data['pid'] = intval(getPost('pid'));
            $data['dirname'] = trim(getPost('dirname'));
            $data['status'] = intval(getPost('status'));
            $data['sort'] = intval(getPost('sort'));

            if(!$data['name']){
                alert('请输入分类名！');
            }
            if($data['dirname'] && app('album')->checkDirname($data['dirname'])){
                alert('目录/别名重复！');
            }

            $m_catalog = M('album_cate');

            if($m_catalog->insert($data)){
                app('album')->refreshCate();
                
                alert('添加成功！',true,U('album','cate'));
            }else{
                alert('添加失败！');
            }
        }
        $m_catalog = M('album_cate');
        $cateinfo = $m_catalog->loadDefault();
        $parent_cates = app('album')->getCateList();

        $this->view->assign('pid',$pid);
        $this->view->assign('cateinfo',$cateinfo);
        $this->view->assign('parent_cates',$parent_cates);
        $this->view->display('cate_edit.php');
    }

    function editAct(){
        $id = intval(getGet('id'));

        if(isPost()){
            $data = array();
            $data['name'] = trim(getPost('name'));
            $data['pid'] = intval(getPost('pid'));
            $data['dirname'] = trim(getPost('dirname'));
            $data['status'] = intval(getPost('status'));
            $data['sort'] = intval(getPost('sort'));

            if(!$data['name']){
                alert('请输入分类名！');
            }
            if($data['dirname'] && app('album')->checkDirname($data['dirname'],$id)){
                alert('目录/别名重复！');
            }

            $m_catalog = M('album_cate');

            if($m_catalog->update($id,$data)){
                app('album')->refreshCate();
                
                alert('编辑成功！',true,U('album','cate'));
            }else{
                alert('编辑失败！');
            }
        }
        $m_catalog = M('album_cate');
        $cateinfo = $m_catalog->load($id);
        $parent_cates = app('album')->getCateList();

        $this->view->assign('pid',$cateinfo['pid']);
        $this->view->assign('cateinfo',$cateinfo);
        $this->view->assign('parent_cates',$parent_cates);
        $this->view->display('cate_edit.php');
    }
}