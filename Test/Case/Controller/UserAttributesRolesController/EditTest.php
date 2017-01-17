<?php
/**
 * UserAttributesRolesController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesNetCommonsControllerTestCase', 'UserRoles.TestSuite');

/**
 * UserAttributesRolesController::edit()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserAttributesRolesController
 */
class UserAttributesRolesControllerEditTest extends UserRolesNetCommonsControllerTestCase {

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
	protected $_controller = 'user_attributes_roles';

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
 * @param string $roleKey 会員権限キー
 * @param int $id1 UserAttributesRole.id
 * @param int $id2 UserAttributesRole.id
 * @param int $id3 UserAttributesRole.id
 * @return void
 */
	private function __data($roleKey, $id1, $id2, $id3) {
		$data = array(
			'save' => null,
			'UserAttributesRole' => array(
				$id1 => array('UserAttributesRole' => array(
					'id' => $id1,
					'role_key' => $roleKey,
					'user_attribute_key' => 'user_attribute_key',
					'other_user_attribute_role' => 'other_readable',
				)),
				$id2 => array('UserAttributesRole' => array(
					'id' => $id2,
					'role_key' => $roleKey,
					'user_attribute_key' => 'radio_attribute_key',
					'other_user_attribute_role' => 'other_not_readable',
				)),
				$id3 => array('UserAttributesRole' => array(
					'id' => $id3,
					'role_key' => $roleKey,
					'user_attribute_key' => 'system_attribute_key',
					'other_user_attribute_role' => 'other_not_readable',
				)),
			)
		);

		return $data;
	}

/**
 * GETリクエストテストのチェック
 *
 * @param string $roleKey 会員権限キー
 * @param bool $userManager 会員管理が使えるかどうか
 * @param int $id1 UserAttributesRole.id
 * @param int $id2 UserAttributesRole.id
 * @param int $id3 UserAttributesRole.id
 * @return void
 */
	private function __assertEdit($roleKey, $userManager, $id1, $id2, $id3) {
		//request->dataのチェック
		$this->assertEquals(
			array('UserRole', 'UserRoleSetting', 'PluginsRole', 'UserAttributesRole', 'UserAttribute'), array_keys($this->controller->data)
		);
		$this->assertEquals($roleKey, Hash::get($this->controller->data, 'UserRole.key'));
		$this->assertEquals($roleKey, Hash::get($this->controller->data, 'UserRoleSetting.role_key'));
		$this->assertCount(3, Hash::get($this->controller->data, 'PluginsRole'));
		$this->assertCount(3, Hash::get($this->controller->data, 'UserAttributesRole'));
		$this->assertEquals($this->vars['userAttributes'], Hash::get($this->controller->data, 'UserAttribute'));

		if ($userManager) {
			$this->assertEquals($roleKey, Hash::get($this->controller->data, 'PluginsRole.0.PluginsRole.role_key'));
		} else {
			$this->assertEquals(null, Hash::get($this->controller->data, 'PluginsRole.0.PluginsRole.role_key'));
		}
		$this->assertEquals('user_manager', Hash::get($this->controller->data, 'PluginsRole.0.Plugin.key'));
		$this->assertEquals('user_roles', Hash::get($this->controller->data, 'PluginsRole.1.Plugin.key'));
		$this->assertEquals('test_user_roles', Hash::get($this->controller->data, 'PluginsRole.2.Plugin.key'));

		$this->__assertEditUserAttributesRole($roleKey, $id1, 'user_attribute_key');
		$this->__assertEditUserAttribute(1, 'user_attribute_key');
		$this->__assertEditUserAttributesRole($roleKey, $id2, 'radio_attribute_key');
		$this->__assertEditUserAttribute(2, 'radio_attribute_key');
		$this->__assertEditUserAttributesRole($roleKey, $id3, 'system_attribute_key');
		$this->__assertEditUserAttribute(3, 'system_attribute_key');

		//viewのチェック
		$this->assertInput('form', null, '/user_roles/user_attributes_roles/edit/' . $roleKey, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);

		$name1 = 'data[UserAttributesRole][' . $id1 . '][UserAttributesRole][other_user_attribute_role]';
		$name2 = 'data[UserAttributesRole][' . $id2 . '][UserAttributesRole][other_user_attribute_role]';
		$name3 = 'data[UserAttributesRole][' . $id3 . '][UserAttributesRole][other_user_attribute_role]';
		if ($userManager) {
			$pattern = '<select name="' . preg_quote($name1, '/') . '".*?disabled="disabled".*?>';
			$this->assertRegExp($pattern, $this->view);

			$pattern = '<select name="' . preg_quote($name2, '/') . '".*?disabled="disabled".*?>';
			$this->assertRegExp($pattern, $this->view);

			$pattern = '<select name="' . preg_quote($name3, '/') . '".*?disabled="disabled".*?>';
			$this->assertRegExp($pattern, $this->view);

			$buttonDisabled = 'true';
		} else {
			$pattern = '<select name="' . preg_quote($name1, '/') . '".*?>';
			$this->assertRegExp($pattern, $this->view);

			$pattern = '<select name="' . preg_quote($name2, '/') . '".*?>';
			$this->assertRegExp($pattern, $this->view);

			$pattern = '<select name="' . preg_quote($name3, '/') . '".*?disabled="disabled".*?>';
			$this->assertRegExp($pattern, $this->view);

			$buttonDisabled = 'false';
		}
	}

/**
 * UserAttributesRoleのチェック
 *
 * @param string $roleKey 会員権限キー
 * @param int $id UserAttributesRole.id
 * @param string $userAttrKey UserAttributes.key
 * @return void
 */
	private function __assertEditUserAttributesRole($roleKey, $id, $userAttrKey) {
		$this->assertEquals(
			$id, Hash::get($this->controller->data, 'UserAttributesRole.' . $id . '.UserAttributesRole.id')
		);
		$this->assertEquals(
			$roleKey, Hash::get($this->controller->data, 'UserAttributesRole.' . $id . '.UserAttributesRole.role_key')
		);
		$this->assertEquals(
			$userAttrKey, Hash::get($this->controller->data, 'UserAttributesRole.' . $id . '.UserAttributesRole.user_attribute_key')
		);
	}

/**
 * UserAttributesのチェック
 *
 * @param int $index インデックス
 * @param string $userAttrKey UserAttributes.key
 * @return void
 */
	private function __assertEditUserAttribute($index, $userAttrKey) {
		$this->assertEquals(
			$userAttrKey, Hash::get($this->controller->data, 'UserAttribute.1.1.' . $index . '.UserAttribute.key')
		);
		$this->assertEquals(
			$userAttrKey, Hash::get($this->controller->data, 'UserAttribute.1.1.' . $index . '.UserAttributeSetting.user_attribute_key')
		);
		$this->assertEquals(
			$userAttrKey, Hash::get($this->controller->data, 'UserAttribute.1.1.' . $index . '.UserAttributesRole.user_attribute_key')
		);
	}

/**
 * edit()アクションのテスト(サイト管理者権限)
 *
 * @return void
 */
	public function testEditGetByAdmin() {
		$roleKey = 'administrator';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => $roleKey), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertEdit($roleKey, true, '4', '5', '6');
	}

/**
 * edit()アクションのテスト(一般権限)
 *
 * @return void
 */
	public function testEditGetByCommonUser() {
		$roleKey = 'common_user';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'key' => $roleKey), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertEdit($roleKey, false, '7', '8', '9');
	}

/**
 * edit()のPOSTリクエストのテスト
 *
 * @return void
 */
	public function testEditPost() {
		$roleKey = 'common_user';
		$this->_mockForReturnTrue('UserRoles.UserAttributesRole', 'saveUserAttributesRoles');

		//テスト実行
		$data = $this->__data($roleKey, '7', '8', '9');
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_roles/index/', $header['Location']);
	}

/**
 * edit()のPOSTリクエストのテスト
 *
 * @return void
 */
	public function testEditPostValidateError() {
		$roleKey = 'common_user';
		$this->_mockForReturnFalse('UserRoles.UserAttributesRole', 'saveUserAttributesRoles');

		//テスト実行
		$data = $this->__data($roleKey, '7', '8', '9');
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_attributes_roles/edit/' . $roleKey, $header['Location']);
	}

/**
 * 不正POSTリクエスト(UserAttributesRoleなし)のテスト
 *
 * @return void
 */
	public function testEditPostNoAttributesRole() {
		$roleKey = 'common_user';

		//テスト実行
		$data = null;
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), 'BadRequestException', 'view');
	}

/**
 * 不正POSTリクエスト(UserAttributesRoleなし)のテスト(JSON形式)
 *
 * @return void
 */
	public function testEditPostNoAttributesRoleJson() {
		$roleKey = 'common_user';

		//テスト実行
		$data = null;
		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), 'BadRequestException', 'json');
	}

/**
 * UserAttributesRole.other_user_attribute_roleなしのテスト
 *
 * @return void
 */
	public function testEditPostNotKeyOtherUserAttributeRole() {
		$this->_mockForReturnTrue('UserRoles.UserAttributesRole', 'saveUserAttributesRoles');
		$roleKey = 'common_user';

		//テスト実行
		$data = $this->__data($roleKey, '7', '8', '9');
		$data = Hash::remove($data, 'UserAttributesRole.9.UserAttributesRole.other_user_attribute_role');

		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), null, 'view');

		$id = '7';
		$otherUserAttrRole = 'other_readable';
		$this->__assertEditUserAttributesRole($roleKey, $id, 'user_attribute_key');
		$this->assertEquals(
			$otherUserAttrRole, Hash::get($this->controller->data, 'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role')
		);

		$id = '8';
		$otherUserAttrRole = 'other_not_readable';
		$this->__assertEditUserAttributesRole($roleKey, $id, 'radio_attribute_key');
		$this->assertEquals(
			$otherUserAttrRole, Hash::get($this->controller->data, 'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role')
		);

		$id = '9';
		$this->assertArrayNotHasKey($id, $this->controller->data['UserAttributesRole']);
	}

/**
 * UserAttributesRole.other_user_attribute_roleが不正値のテスト
 *
 * @return void
 */
	public function testEditPostBadKeyOtherUserAttributeRole() {
		$roleKey = 'common_user';

		//テスト実行
		$data = $this->__data($roleKey, '7', '8', '9');
		$data = Hash::insert($data, 'UserAttributesRole.9.UserAttributesRole.other_user_attribute_role', 'other_editable');

		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), 'BadRequestException', 'view');
	}

/**
 * UserAttributesRole.other_user_attribute_roleが不正値のテスト(JSON形式)
 *
 * @return void
 */
	public function testEditPostBadKeyOtherUserAttributeRoleJson() {
		$roleKey = 'common_user';

		//テスト実行
		$data = $this->__data($roleKey, '7', '8', '9');
		$data = Hash::insert($data, 'UserAttributesRole.9.UserAttributesRole.other_user_attribute_role', 'other_editable');

		$this->_testPostAction('put', $data, array('action' => 'edit', 'key' => $roleKey), 'BadRequestException', 'json');
	}

}
