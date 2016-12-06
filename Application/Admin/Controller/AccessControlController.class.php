<?php

namespace Admin\Controller;

/**
 * 权限控制
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class AccessControlController extends CommonController
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
    }

    /**
     * [Index 权限管理页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:55:53+0800
     */
	public function Index()
	{
		$this->display();
	}

    /**
     * [RoleInfo 角色管理页面]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-03T12:55:53+0800
     */
	public function RoleInfo()
	{
		$this->display();
	}
}
?>