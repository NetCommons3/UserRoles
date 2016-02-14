<?php
/**
 * UserRoleFormComponentテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * UserRoleFormComponentテスト用Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\test_app\Plugin\UserRoles\Controller
 */
class TestUserRoleFormComponentController extends AppController {

/**
 * 使用コンポーネント
 *
 * @var array
 */
	public $components = array(
		'UserRoles.UserRoleForm'
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->autoRender = true;
	}

/**
 * requestActionのテスト用アクション
 *
 * @return void
 */
	public function index_request_action() {
		$this->autoRender = true;
		$this->requestAction('/' . $this->params['plugin'] . '/' . $this->params['controller'] . '/index');
	}

}
