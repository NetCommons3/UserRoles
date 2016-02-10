<?php
/**
 * beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('UserRole', 'UserRoles.Model');

/**
 * beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRolesAppController
 */
class UserRolesAppControllerBeforeFilterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserRoles', 'TestUserRoles');
		$this->generateNc('TestUserRoles.TestUserRolesAppControllerIndex');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * beforeFilter()のテスト
 *
 * @return void
 */
	public function testBeforeFilter() {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testNcAction('/test_user_roles/test_user_roles_app_controller_index/index', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('Controller/UserRolesAppController', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
	}

/**
 * ログインなしのテスト
 *
 * @return void
 */
	public function testBeforeFilterNoLogin() {
		$this->setExpectedException('ForbiddenException');

		//テスト実行
		$this->_testNcAction('/test_user_roles/test_user_roles_app_controller_index/index', array(
			'method' => 'get'
		));
	}

}
