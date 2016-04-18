<?php
/**
 * UserRoleSettingsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserRoleSettingsController::edit()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRoleSettingsController
 */
class UserRoleSettingsControllerEditTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.plugin4test',
		'plugin.user_roles.plugins_role4test',
		'plugin.user_roles.user_attributes_role',
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
	protected $_controller = 'user_role_settings';

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
		$data = array (
			'UserRoleSetting' => array (
				'id' => '2', 'role_key' => 'administrator', 'origin_role_key' => 'administrator', 'use_private_room' => '1',
			),
			'PluginsRole' => array (
				0 => array (
					'PluginsRole' => array(
						'id' => '3', 'is_usable_plugin' => '1', 'role_key' => 'administrator', 'plugin_key' => 'user_manager',
					),
				),
				1 => array (
					'PluginsRole' => array (
						'id' => '1', 'is_usable_plugin' => '1', 'role_key' => 'administrator', 'plugin_key' => 'user_roles',
					),
				),
				2 => array (
					'PluginsRole' => array (
						'id' => null, 'is_usable_plugin' => '1', 'role_key' => 'administrator', 'plugin_key' => 'test_user_roles',
					),
				),
			),
		);

		return $data;
	}

/**
 * PluginsRoleのテストチェック
 *
 * @param int $index インデックス
 * @param int $id PluginsRole.id
 * @param string $roleKey 権限キー
 * @param string $pluginKey プラグインキー
 * @param string $usable 使用有無チェックボックス (e.g.) checked="checked" value="1"
 * @return void
 */
	private function __assertPluginsRole($index, $id, $roleKey, $pluginKey, $usable) {
		$pattern = '/<input.*?name="' . preg_quote('data[PluginsRole][' . $index . '][PluginsRole][is_usable_plugin]', '/') . '" ' . $usable . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertInput('input', 'data[PluginsRole][' . $index . '][PluginsRole][id]', $id, $this->view);
		$this->assertInput('input', 'data[PluginsRole][' . $index . '][PluginsRole][role_key]', $roleKey, $this->view);
		$this->assertInput('input', 'data[PluginsRole][' . $index . '][PluginsRole][plugin_key]', $pluginKey, $this->view);
	}

/**
 * UserRoleSettingのテストチェック
 *
 * @param int $id UserRoleSetting.id
 * @param string $roleKey 権限キー
 * @param string $usable0 使用しないの値 (e.g.) value="0" checked="checked"
 * @param string $usable1 使用するの値 (e.g.) value="1" checked="checked"
 * @return void
 */
	private function __assertUserRoleSetting($id, $roleKey, $usable0, $usable1) {
		$this->assertInput('input', 'data[UserRoleSetting][id]', $id, $this->view);
		$this->assertInput('input', 'data[UserRoleSetting][role_key]', $roleKey, $this->view);
		$this->assertInput('input', 'data[UserRoleSetting][origin_role_key]', $roleKey, $this->view);
		$pattern = '/<input.*?name="' . preg_quote('data[UserRoleSetting][use_private_room]', '/') . '".*?' . $usable1 . ' \/>/';
		$this->assertRegExp($pattern, $this->view);
		$pattern = '/<input.*?name="' . preg_quote('data[UserRoleSetting][use_private_room]', '/') . '".*?' . $usable0 . ' \/>/';
		$this->assertRegExp($pattern, $this->view);
	}

/**
 * edit()アクションのテスト(サイト管理者の設定)
 *
 * @return void
 */
	public function testEditGetByAdmin() {
		$roleKey = 'administrator';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => $roleKey), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertInput('form', null, '/user_roles/user_role_settings/edit/' . $roleKey, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);

		$this->__assertUserRoleSetting('2', $roleKey, 'value="0"', 'value="1" checked="checked"');
		$this->__assertPluginsRole('0', '3', $roleKey, 'user_manager', 'checked="checked" value="1"');
		$this->__assertPluginsRole('1', '1', $roleKey, 'user_roles', 'checked="checked" value="1"');
		$this->__assertPluginsRole('2', null, $roleKey, 'test_user_roles', 'value="1"');
	}

/**
 * edit()アクションのテスト(一般権限の設定)
 *
 * @return void
 */
	public function testEditGetByCommonUser() {
		$roleKey = 'common_user';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => $roleKey), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertInput('form', null, '/user_roles/user_role_settings/edit/' . $roleKey, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);

		$this->__assertUserRoleSetting('3', $roleKey, 'value="0"', 'value="1" checked="checked"');
		$this->__assertPluginsRole('0', null, $roleKey, 'user_manager', 'disabled="disabled" value="1"');
		$this->__assertPluginsRole('1', null, $roleKey, 'user_roles', 'disabled="disabled" value="1"');
		$this->__assertPluginsRole('2', null, $roleKey, 'test_user_roles', 'disabled="disabled" value="1"');
	}

/**
 * 不正アクションのテスト
 *
 * @return void
 */
	public function testEditGetOnExceptionError() {
		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => 'aaaaa'), null, 'BadRequestException', 'view');
	}

/**
 * 不正アクションのテスト(JSON形式)
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
		$this->_mockForReturnTrue('UserRoles.UserRoleSetting', 'saveUserRoleSetting');
		$roleKey = 'administrator';

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_attributes_roles/edit/' . $roleKey, $header['Location']);
	}

/**
 * edit()のPOSTの不正リクエストのテスト
 *
 * @return void
 */
	public function testEditPostOnValidationError() {
		$this->_mockForReturnFalse('UserRoles.UserRoleSetting', 'saveUserRoleSetting');
		$roleKey = 'administrator';

		//テスト実行
		$data = $this->__data();
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_role_settings/edit/' . $roleKey, $header['Location']);
	}

}
