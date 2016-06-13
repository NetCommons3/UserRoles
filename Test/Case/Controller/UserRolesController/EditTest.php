<?php
/**
 * UserRolesController::edit()のテスト
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
 * UserRolesController::edit()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRolesController
 */
class UserRolesControllerEditTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute4edit',
		'plugin.user_attributes.user_attribute_choice4edit',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4edit',
		'plugin.user_roles.plugin4test',
		'plugin.user_roles.plugins_role4test',
		'plugin.user_roles.user_attributes_role4edit',
		'plugin.user_roles.user_role4test',
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
				array(
					'id' => '7', 'language_id' => '2', 'key' => 'test_user', 'type' => '1',
					'name' => 'Test user', 'description' => 'Test user description',
				),
				array(
					'id' => '8', 'language_id' => '1', 'key' => 'test_user', 'type' => '1',
					'name' => 'Test user', 'description' => 'Test user description',
				),
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
 * @param string $roleKey 会員権限キー
 * @param bool $isSystem システム権限かどうか
 * @return void
 */
	private function __assertEdit($roleKey, $isSystem) {
		//チェック
		$this->assertInput('form', null, '/user_roles/user_roles/edit/' . $roleKey, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);

		if ($isSystem) {
			$this->assertNotContains('dangerZone', $this->view);
		} else {
			$this->assertContains('dangerZone', $this->view);
		}

		$this->assertArrayHasKey('UserRole', $this->controller->data);
		$this->assertCount(2, $this->controller->data['UserRole']);
		$this->assertArrayHasKey('key', $this->controller->data['UserRole'][0]);
		$this->assertArrayHasKey('key', $this->controller->data['UserRole'][1]);
		$this->assertArrayHasKey('UserRoleSetting', $this->controller->data);

		$this->assertEquals(!$isSystem, $this->vars['isDeletable']);
	}

/**
 * edit()アクションのGETリクエストテストのDataProvider
 *
 * ### 戻り値
 *  - roleKey 会員権限キー
 *  - roleName 権限名
 *  - isSystem システム権限かどうか
 *
 * @return array データ
 */
	public function dataProvider() {
		return array(
			// * 管理者権限
			array('roleKey' => UserRole::USER_ROLE_KEY_ADMINISTRATOR, 'roleName' => 'Site administrator', 'isSystem' => true),
			// * 一般権限
			array('roleKey' => UserRole::USER_ROLE_KEY_COMMON_USER, 'roleName' => 'Common user', 'isSystem' => true),
			// * システム以外の権限(作成した権限)
			array('roleKey' => 'test_user', 'roleName' => 'Test user', 'isSystem' => false),
		);
	}

/**
 * edit()アクションのGETリクエストテスト
 *
 * @param string $roleKey 会員権限キー
 * @param string $roleName 権限名
 * @param bool $isSystem システム権限かどうか
 * @dataProvider dataProvider
 * @return void
 */
	public function testEditGet($roleKey, $roleName, $isSystem) {
		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => $roleKey), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertEdit($roleKey, $isSystem);
		$this->assertEquals($roleName, $this->vars['subtitle']);
	}

/**
 * edit()アクションのGETリクエストテスト(不正アクセス)
 *
 * @return void
 */
	public function testEditGetOnExceptionError() {
		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => 'aaaaa'), null, 'BadRequestException', 'view');
	}

/**
 * edit()アクションのGETリクエストテスト(不正アクセス)(JSON形式)
 *
 * @return void
 */
	public function testEditGetOnExceptionErrorJson() {
		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => 'aaaaa'), null, 'BadRequestException', 'json');
	}

/**
 * edit()のPOSTリクエストのテスト
 *
 * @return void
 */
	public function testEditPost() {
		$this->_mockForReturn('UserRoles.UserRole', 'saveUserRole', array(
			0 => array('UserRole' => array('language_id' => '2', 'key' => 'test_user'))
		));

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => 'test_user'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_roles/index', $header['Location']);
	}

/**
 * UserAttribute->validationErrorsのテスト
 *
 * @return void
 */
	public function testEditPostValidateError() {
		//テスト実行
		$data = $this->__data();
		$data = Hash::remove($data, 'UserRole.{n}.name');
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => 'test_user'), null, 'view');

		//チェック
		$this->__assertEdit('test_user', false);
		$this->assertEquals('Test user', $this->vars['subtitle']);

		$this->assertTextContains(
			sprintf(__d('net_commons', 'Please input %s.'), __d('user_roles', 'User role name')),
			$this->view
		);
	}

}
