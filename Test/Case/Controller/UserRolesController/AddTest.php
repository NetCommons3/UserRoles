<?php
/**
 * UserRolesController::add()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserRolesController::add()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRolesController
 */
class UserRolesControllerAddTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_attributes_role',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_roles';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		//ログイン
		TestAuthGeneral::login($this);
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
 * テストデータセット
 *
 * @return void
 */
	private function __data() {
		$data = array(
			'save' => null,
			'active_lang_id' => Current::read('Language.id'),
			'UserRole' => array(
				0 => array(
					'id' => null, 'language_id' => '1', 'key' => null, 'type' => '1', 'name' => 'Add name', 'description' => 'Add user description',
				),
				1 => array(
					'id' => null, 'language_id' => '2', 'key' => null, 'type' => '1', 'name' => 'Add name', 'description' => 'Add user description',
				)
			),
			'UserRoleSetting' => array(
				'origin_role_key' => UserRole::USER_ROLE_KEY_COMMON_USER,
			),
		);

		return $data;
	}

/**
 * GETリクエストテストのチェック
 *
 * @return void
 */
	private function __assertAdd() {
		//チェック
		$this->assertInput('form', null, '/user_roles/user_roles/add', $this->view);
		$this->assertInput('input', '_method', 'POST', $this->view);
		$this->assertInput('select', 'data[UserRoleSetting][origin_role_key]', null, $this->view);
		$pattern = '/<select.*?name="' . preg_quote('data[UserRoleSetting][origin_role_key]', '/') . '".*?' .
					'disabled="disabled".*?id="UserRoleSettingOriginRoleKey".*?>/';
		$this->assertNotRegExp($pattern, $this->view);

		$this->assertNotContains('/user_roles/user_role_settings/edit', $this->view);
		$this->assertNotContains('/user_roles/user_attributes_roles/edit', $this->view);

		$this->assertArrayHasKey('UserRole', $this->controller->data);
		$this->assertCount(2, $this->controller->data['UserRole']);
		$this->assertArrayHasKey('key', $this->controller->data['UserRole'][0]);
		$this->assertArrayHasKey('key', $this->controller->data['UserRole'][1]);
		$this->assertArrayHasKey('UserRoleSetting', $this->controller->data);
		//$this->assertArrayHasKey('role_key', $this->controller->data['UserRoleSetting']);
		$this->assertEquals(UserRole::USER_ROLE_KEY_COMMON_USER, $this->controller->data['UserRoleSetting']['origin_role_key']);
	}

/**
 * add()アクションのGETリクエストのテスト
 *
 * @return void
 */
	public function testAddGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'add'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertAdd();
	}

/**
 * add()アクションのPOSTリクエストのテスト
 *
 * @return void
 */
	public function testAddPost() {
		$this->_mockForReturn('UserRoles.UserRole', 'saveUserRole', array(
			0 => array('UserRole' => array(
				'key' => 'add_user',
				'language_id' => Current::read('Language.id'),
			)
		)));

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('post', $data, array('action' => 'add'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_role_settings/edit/add_user', $header['Location']);
	}

/**
 * UserAttribute->validationErrorsのテスト
 *
 * @return void
 */
	public function testAddPostValidateError() {
		//テスト実行
		$data = $this->__data();
		$data = Hash::remove($data, 'UserRole.{n}.name');
		$this->_testPostAction('post', $data, array('action' => 'add'), null, 'view');

		//チェック
		$this->__assertAdd();
		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('user_roles', 'User role name')),
			$this->view
		);
	}

}
