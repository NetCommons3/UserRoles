<?php
/**
 * UserAttributesRole::getUserAttributesRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * UserAttributesRole::getUserAttributesRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserAttributesRole
 */
class UserAttributesRoleGetUserAttributesRoleTest extends NetCommonsGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_attributes_role4edit',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserAttributesRole';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getUserAttributesRole';

/**
 * getUserAttributesRole()のテスト
 *
 * @return void
 */
	public function testGetUserAttributesRole() {
		//データ生成
		$roleKey = 'common_user';

		//期待値のデータ生成
		$id1 = '7';
		$id2 = '8';
		$id3 = '9';
		$actual = array(
			$id1 => array('UserAttributesRole' => array(
				'id' => $id1,
				'role_key' => $roleKey,
				'user_attribute_key' => 'user_attribute_key',
				'self_readable' => true,
				'self_editable' => true,
				'other_readable' => false,
				'other_editable' => false,
			)),
			$id2 => array('UserAttributesRole' => array(
				'id' => $id2,
				'role_key' => $roleKey,
				'user_attribute_key' => 'radio_attribute_key',
				'self_readable' => true,
				'self_editable' => false,
				'other_readable' => false,
				'other_editable' => false,
			)),
			$id3 => array('UserAttributesRole' => array(
				'id' => $id3,
				'role_key' => $roleKey,
				'user_attribute_key' => 'system_attribute_key',
				'self_readable' => false,
				'self_editable' => false,
				'other_readable' => false,
				'other_editable' => false,
			)),
		);

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($roleKey);
		$result = Hash::remove($result, '{n}.{s}.created');
		$result = Hash::remove($result, '{n}.{s}.created_user');
		$result = Hash::remove($result, '{n}.{s}.modified');
		$result = Hash::remove($result, '{n}.{s}.modified_user');

		//チェック
		$this->assertEquals($actual, $result);
	}

/**
 * getUserAttributesRole()のテスト
 *
 * @return void
 */
	public function testGetUserAttributesRoleByNotKey() {
		//データ生成
		$roleKey = 'aaaaaa';

		//期待値のデータ生成
		$actual = array();

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($roleKey);

		//チェック
		$this->assertEquals($actual, $result);
	}

}
