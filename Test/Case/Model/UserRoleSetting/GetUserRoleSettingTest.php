<?php
/**
 * UserRoleSetting::getUserRoleSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * UserRoleSetting::getUserRoleSetting()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRoleSetting
 */
class UserRoleSettingGetUserRoleSettingTest extends NetCommonsGetTest {

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
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserRoleSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getUserRoleSetting';

/**
 * PluginsRoleの評価
 *
 * @param string $result テスト結果
 * @param int $index インデックス
 * @param int $id PluginsRole.id
 * @param string $roleKey 権限キー
 * @param string $pluginKey プラグインキー
 * @return void
 */
	private function __assertPluginsRole($result, $index, $id, $roleKey, $pluginKey) {
		$this->assertEquals($id, $result['PluginsRole'][$index]['PluginsRole']['id']);
		if ($id) {
			$this->assertEquals($pluginKey, $result['PluginsRole'][$index]['PluginsRole']['plugin_key']);
			$this->assertEquals($roleKey, $result['PluginsRole'][$index]['PluginsRole']['role_key']);
		} else {
			$this->assertEquals(null, $result['PluginsRole'][$index]['PluginsRole']['role_key']);
			$this->assertEquals(null, $result['PluginsRole'][$index]['PluginsRole']['plugin_key']);
		}

		$this->assertNotEmpty($result['PluginsRole'][$index]['Plugin']['id']);
		$this->assertEquals($pluginKey, $result['PluginsRole'][$index]['Plugin']['key']);
	}

/**
 * getUserRoleSetting()のテスト
 *
 * @return void
 */
	public function testGetUserRoleSettingByAdmin() {
		//データ生成
		$pluginType = '2';
		$roleKey = 'administrator';

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($pluginType, $roleKey);

		//チェック
		$this->assertEquals(array('UserRoleSetting', 'PluginsRole'), array_keys($result));
		$this->assertEquals($roleKey, $result['UserRoleSetting']['role_key']);
		$this->assertTrue($result['UserRoleSetting']['is_usable_user_manager']);

		$this->assertCount(3, $result['PluginsRole']);

		$this->__assertPluginsRole($result, 0, '3', $roleKey, 'user_manager');
		$this->__assertPluginsRole($result, 1, '1', $roleKey, 'user_roles');
		$this->__assertPluginsRole($result, 2, null, $roleKey, 'test_user_roles');
	}

/**
 * getUserRoleSetting()のテスト
 *
 * @return void
 */
	public function testGetUserRoleSettingByCommonUser() {
		//データ生成
		$pluginType = '2';
		$roleKey = 'common_user';

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($pluginType, $roleKey);

		//チェック
		$this->assertEquals(array('UserRoleSetting', 'PluginsRole'), array_keys($result));
		$this->assertEquals($roleKey, $result['UserRoleSetting']['role_key']);
		$this->assertFalse($result['UserRoleSetting']['is_usable_user_manager']);

		$this->assertCount(3, $result['PluginsRole']);

		$this->__assertPluginsRole($result, 0, null, $roleKey, 'user_manager');
		$this->__assertPluginsRole($result, 1, null, $roleKey, 'user_roles');
		$this->__assertPluginsRole($result, 2, null, $roleKey, 'test_user_roles');
	}

}
