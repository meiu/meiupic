<?php 
defined('IN_MWEB') or die('access denied');

/*二级菜单开始*/
$submenu = array(
    '基本' => array('index'),
    '自定义路由' => array('route','routeadd','routeedit'),
    '后台菜单' => array('menu'),
    '系统信息及缓存' => array('system')
);
$view->assign('submenu',$submenu);
/*二级菜单结束*/

$act = getGet('a','index');
$view->assign('act',$act);

switch ($act) {
    case 'index':
        $m_setting = M('settings');
        if(isPost()){
            $settings = getPost('setting');
            foreach ($settings as $key => $value) {
                $data = array(
                    'name' => $key,
                    'value' => trim($value),
                    'autoload' => 'yes'
                );
                $m_setting->insert($data,true);
            }
            //刷新缓存
            app('base')->refreshSettings();
            alert('修改设置成功！',true,U('base','setting'));
        }

        $settings_info = app('base')->getAllSettings();
        $view->assign('settings_info',$settings_info);
        $view->display('setting.php');
        break;
    case 'route':
        $page = getGet('page',1);
        $m_route = M('routes');

        if(isPost()){
            $sort = getPost('sort');
            foreach ($sort as $key => $value) {
                $m_route->update($key,array('sort'=>$value));
            }
            app('base')->refreshRoute();
            alert('更新排序成功！',true,U('base','setting','a=route&page='.$page));
        }

        $totalCount = $m_route->count();
        $pageurl = U('base','setting','a=route&page=%page%');

        $pager = new Pager($page,C('pageset.admin',15),$totalCount,$pageurl);
        $pager->config(C('page'));
        $limit = $pager->getLimit();

        $rows = $m_route->findAll(array(
            'start' => $limit['start'],
            'limit' => $limit['limit'],
            'order' => 'sort asc'
        ));
        $view->assign('rows',$rows);

        $view->assign('pagestr',$pager->html());

        $view->display('setting_route.php');
        break;
    case 'routeedit':

        $id = intval(getGet('id'));
        $m_route = M('routes');
        if(isPost()){
            $route = trim(getPost('route'));
            $params = trim(getPost('params'));

            if(empty($route)){
                alert('请输入路由！');
            }
            if(empty($params)){
                alert('请输入映射参数！');
            }
            //判断隐射参数
            parse_str($params,$params_arr);
            if(!isset($params_arr['app']) || !isset($params_arr['m'])){
                alert('映射参数必须包含app和m参数！');
            }


            $data = array(
                'route' => $route,
                'params' => $params
            );

            if($m_route->update($id,$data)){
                app('base')->refreshRoute();
                alert('编辑成功！',true,U('base','setting','a=route'));
            }else{
                alert('编辑失败！');
            }
        }

        $route_info = $m_route->load($id);

        if(!$route_info){
            alert('您要编辑的路由不存在！');
        }

        $view->assign('route_info',$route_info);
        $view->display('route_edit.php');
        break;
     case 'routeadd':
        $m_route = M('routes');

        if(isPost()){
            $route = trim(getPost('route'));
            $params = trim(getPost('params'));

            if(empty($route)){
                alert('请输入路由！');
            }
            if(empty($params)){
                alert('请输入映射参数！');
            }
            //判断隐射参数
            parse_str($params,$params_arr);
            if(!isset($params_arr['app']) || !isset($params_arr['m'])){
                alert('映射参数必须包含app和m参数！');
            }

            $data = array(
                'route'  => $route,
                'params' => $params,
                'sort'   => 50
            );

            if($m_route->insert($data)){
                app('base')->refreshRoute();
                alert('添加路由规则成功！',true,U('base','setting','a=route'));
            }else{
                alert('添加路由规则失败！');
            }
        }

        $route_info = $m_route->loadDefault();

        $view->assign('route_info',$route_info);
        $view->display('route_edit.php');
        break;
    case 'routedel':
        $id = intval(getGet('id'));
        $m_route = M('routes');
        if($m_route->delete($id)){
            app('base')->refreshRoute();
            alert('删除成功！',true,U('base','setting','a=route'));
        }else{
            alert('删除失败！');
        }
        break;
    case 'system':
        $view->display('setting_system.php');
        break;
    case 'clearcache':
        $cache->clean();
        alert('清空缓存成功！',true);
        break;
    case 'menu':
        if(isPost()){
            $sort = getPost('sort');
            $enable = getPost('enable');

            $menuslist = app('base')->getSetting('admin_menu',true);
            foreach ($menuslist as $key => $value) {
                if(isset($value['mod'])){
                    $menuslist[$key]['sort'] = $sort[$value['app'].'_'.$value['mod']];
                    $menuslist[$key]['enable'] = isset($enable[$value['app'].'_'.$value['mod']])?true:false;
                }else{
                    $menuslist[$key]['sort'] = $sort[$value['app']];
                    $menuslist[$key]['enable'] = isset($enable[$value['app']])?true:false;
                }
            }
            $baseapp = app('base');
            usort($menuslist,'sortFields');
            app('base')->setSetting('admin_menu',$menuslist);

            alert('更新排序成功！',true,'js_reload');
        }
        $menulist = app('base')->getSetting('admin_menu',true);
        $view->assign('menulist',$menulist);
        $view->display('setting_menu.php');
        break;
    case 'menudel':
        $appid = getGet('appid');
        $menuslist = app('base')->getSetting('admin_menu',true);
        foreach ($menuslist as $key => $value) {
            if($value['app'] == $appid && !$value['fixed']){
                unset($menuslist[$key]);
            }
        }
        app('base')->setSetting('admin_menu',$menuslist);
        alert('删除成功！',true,'js_reload');
        break;
}