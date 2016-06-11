<?php
/**
 * UserRoleFormHelper::selectOriginUserRoles()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * UserRoleFormHelper::selectOriginUserRoles()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Helper\UserRoleFormHelper
 */
class UserRoleFormHelperSelectOriginUserRolesTest extends NetCommonsHelperTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_role4test',
	);

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

		//テストデータ生成
		$this->UserRole = ClassRegistry::init('UserRoles.UserRole');
		$userRoles = $this->UserRole->find('all', array(
			'recursive' => -1,
			'fields' => array('key', 'name', 'description'),
			'conditions' => array(
				'language_id' => Current::read('Language.id')
			),
			'order' => array('id' => 'asc')
		));

		$viewVars = array(
			'userRoles' => Hash::combine($userRoles, '{n}.UserRole.key', '{n}.UserRole.name'),
			'userRolesDescription' => Hash::combine($userRoles, '{n}.UserRole.key', '{n}.UserRole.description')
		);
		$viewVars = Hash::remove($viewVars, '{s}.' . UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR);
		$requestData = array(
			'UserRoleSetting' => array(
				'origin_role_key' => UserRole::USER_ROLE_KEY_COMMON_USER,
			)
		);

		//Helperロード
		$this->loadHelper('UserRoles.UserRoleForm', $viewVars, $requestData);
	}

/**
 * selectOriginUserRoles()のテスト
 *
 * @return void
 */
	public function testSelectOriginUserRoles() {
		//データ生成
		$fieldName = 'UserRoleSetting.origin_role_key';
		$attributes = array();

		//テスト実施
		$result = $this->UserRoleForm->selectOriginUserRoles($fieldName, $attributes);

		//チェック
		$this->assertInput('select', 'data[UserRoleSetting][origin_role_key]', null, $result);
		$this->assertNotContains(UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR, $result);
		$this->assertInput('option', 'administrator', null, $result);
		$this->assertInput('option', 'common_user', 'selected', $result);
		$this->assertInput('option', 'test_user', null, $result);
		$this->assertNotContains(__d('user_roles', 'Role description'), $result);
	}

/**
 * selectOriginUserRoles()のテスト(description属性追加)
 *
 * @return void
 */
	public function testSelectOriginUserRolesAddDescription() {
		//データ生成
		$fieldName = 'UserRoleSetting.origin_role_key';
		$attributes = array(
			'description' => true,
		);

		//テスト実施
		$result = $this->UserRoleForm->selectOriginUserRoles($fieldName, $attributes);

		//チェック
		$this->assertInput('select', 'data[UserRoleSetting][origin_role_key]', null, $result);
		$this->assertNotContains(UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR, $result);
		$this->assertInput('option', 'administrator', null, $result);
		$this->assertInput('option', 'common_user', 'selected', $result);
		$this->assertInput('option', 'test_user', null, $result);
	}

}
