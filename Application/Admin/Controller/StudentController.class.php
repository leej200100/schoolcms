<?php

namespace Admin\Controller;

/**
 * 学生管理
 * @author   Devil
 * @blog     http://gong.gg/
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
class StudentController extends CommonController
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
     * [Index 权限组列表]
     * @author   Devil
     * @blog     http://gong.gg/
     * @version  0.0.1
     * @datetime 2016-12-06T21:31:53+0800
     */
	public function Index()
	{
		/*// 登录校验
		$this->Is_Login();
		
		// 权限校验
		$this->Is_Power();

		// 参数
		$param = array_merge($_POST, $_GET);

		// 模型对象
		$m = M('Admin');

		// 条件
		$where = $this->GetAdminIndexWhere();

		// 分页
		$number = 10;
		$page_param = array(
				'number'	=>	$number,
				'total'		=>	$m->where($where)->count(),
				'where'		=>	$param,
				'url'		=>	U('Admin/Admin/Index'),
			);
		$page = new \My\Page($page_param);

		// 登录校验
		$this->Is_Login();

		// 获取管理员列表
		$list = $m->field(array('id', 'username', 'mobile', 'gender', 'login_total', 'login_time', 'add_time'))->where($where)->limit($page->GetPageStarNumber(), $number)->select();
		
		$role = M('Role')->field(array('id', 'name'))->where(array('is_enable'=>1))->select();
		$this->assign('role', $role);
		$this->assign('param', $param);
		$this->assign('page_html', $page->GetPageHtml());
		$this->assign('list', $list);*/

		$this->display();
	}

	/**
	 * [SaveInfo 学生添加/编辑页面]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function SaveInfo()
	{
		// 学期
		$region_list = M('Region')->field(array('id', 'name'))->where(array('is_enable'=>1))->select();
		$this->assign('region_list', $region_list);

		// 班级
		$c = M('Class');
		$class_list = $c->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>0))->select();
		if(!empty($class_list))
		{
			foreach($class_list as $k=>$v)
			{
				$class_list[$k]['item'] = $c->field(array('id', 'name'))->where(array('is_enable'=>1, 'pid'=>$v['id']))->select();
			}
		}
		$this->assign('class_list', $class_list);

		// 性别
		$this->assign('common_gender_list', L('common_gender_list'));

		// 学生状态
		$this->assign('common_student_state_list', L('common_student_state_list'));

		// 缴费状态
		$this->assign('common_tuition_state_list', L('common_tuition_state_list'));

		$this->display();
	}

	/**
	 * [Save 学生添加/编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-14T21:37:02+0800
	 */
	public function Save()
	{
		// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 添加
		if(empty(I('id')))
		{
			$this->Add();

		// 编辑
		} else {
			$this->Edit();
		}
	}

	/**
	 * [Add 学生添加]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-18T16:20:59+0800
	 */
	private function Add()
	{
		// 学生对象
		$m = M('Student');

		// 数据自动校验
		if($m->create($_POST, 1))
		{
			// 额外数据处理
			$m->add_time	=	time();
			
			// 写入数据库
			if($m->add())
			{
				$this->ajaxReturn(L('common_operation_add_success'));
			} else {
				$this->ajaxReturn(L('common_operation_add_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Edit 学生编辑]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-17T22:13:40+0800
	 */
	private function Edit()
	{
		// 学生对象
		$m = M('Student');

		// 数据自动校验
		if($m->create($_POST, 2))
		{
			// 更新数据库
			if($m->where(array('id'=>I('id'), 'id_card'=>I('id_card')))->save())
			{
				$this->ajaxReturn(L('common_operation_edit_success'));
			} else {
				$this->ajaxReturn(L('common_operation_edit_error'), -100);
			}
		} else {
			$this->ajaxReturn($m->getError(), -1);
		}
	}

	/**
	 * [Delete 学生删除]
	 * @author   Devil
	 * @blog     http://gong.gg/
	 * @version  0.0.1
	 * @datetime 2016-12-15T11:03:30+0800
	 */
	public function Delete()
	{
		print_r($_POST);
		/*// 是否ajax请求
		if(!IS_AJAX)
		{
			$this->error(L('common_unauthorized_access'));
		}

		// 参数是否有误
		if(empty(I('id')) || empty(I('id_card')))
		{
			$this->ajaxReturn(L('common_param_error'), -1);
		}

		// 学生模型
		$s = M('Student');

		// 学生是否存在
		$data = $s->where(array('id'=>I('id'), 'id_card'=>I('id_card')))->find();
		if(empty($data))
		{
			$this->ajaxReturn(L('student_no_exist_error'), -2);
		}

		// 开启事务
		$r->startTrans();

		// 删除学生
		$s_state = $r->where(array('id'=>I('id'), 'id_card'=>I('id_card')))->delete();

		// 删除成绩
		$rp_state = M('RolePower')->where(array('role_id'=>I('id')))->delete();
		if($s_state && $rp_state)
		{
			// 提交事务
			$r->commit();

			$this->ajaxReturn(L('common_operation_delete_success'));
		} else {
			// 回滚事务
			$r->rollback();
			$this->ajaxReturn(L('common_operation_delete_error'), -100);
		}*/
	}
}
?>