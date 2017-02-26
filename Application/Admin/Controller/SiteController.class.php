<?php

namespace Admin\Controller;

/**
 * 站点设置
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class SiteController extends CommonController
{
	/**
	 * [_initialize 前置操作-继承公共前置方法]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-03T12:39:08+0800
	 */
	public function _initialize()
	{
		// 调用父类前置方法
		parent::_initialize();

		// 登录校验
		$this->Is_Login();

		// 权限校验
		$this->Is_Power();
	}

	/**
     * [Index 配置列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		// 时区
		$this->assign('site_timezone_list', L('site_timezone_list'));

		// 站点状态
		$this->assign('site_site_state_list', L('site_site_state_list'));

		// 配置信息
		$data = M('Config')->getField('only_tag,name,describe,value,error_tips');
		$this->assign('data', $data);
		
		$this->display('Index');
	}

	/**
	 * [Save 配置数据保存]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2017-01-02T23:08:19+0800
	 */
	public function Save()
	{
		// 站点logo
		if(!empty($_FILES['home_site_logo_img']['tmp_name']))
		{
			list($type, $suffix) = explode('/', $_FILES['home_site_logo_img']['type']);
			$path = 'Public/Upload/Home/image/';
			if(!is_dir($path))
			{
				mkdir(ROOT_PATH.$path, 0777, true);
			}
			$filename = time().'_logo.'.$suffix;
			$home_site_logo = $path.$filename;
			if(move_uploaded_file($_FILES['home_site_logo_img']['tmp_name'], ROOT_PATH.$home_site_logo))
			{
				$_POST['home_site_logo'] = '/'.$home_site_logo;
			}
		}

		// 基础配置
		$this->MyConfigSave();
	}
}
?>