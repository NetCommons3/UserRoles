<?php
/**
 * UserRoleBehavior::savePluginsRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * UserRoleBehavior::savePluginsRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\Behavior\UserRoleBehavior
 */
class UserRoleBehaviorSavePluginsRoleTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.plugin4test',
		'plugin.user_roles.plugins_role4test',
		'plugin.user_roles.user_role4test',
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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserRoles', 'TestUserRoles');
		$this->TestModel = ClassRegistry::init('TestUserRoles.TestUserRoleBehaviorModel');
	}

/**
 * savePluginsRole()テストのDataProvider
 *
 * ### 戻り値
 *  - roleKey roles.key
 *  - pluginKey plugins.key
 *  - count 実行前の件数
 *
 * @return array データ
 */
	public function dataProvider() {
		return array(
			array('roleKey' => 'administrator', 'pluginKey' => 'user_manager', 'count' => 1),
			array('roleKey' => 'administrator', 'pluginKey' => 'test_user_roles', 'count' => 0),
		);
	}

/**
 * savePluginsRole()のテスト
 *
 * @param string $roleKey roles.key
 * @param string $pluginKey plugins.key
 * @param int $count 実行前の件数
 * @dataProvider dataProvider
 * @return void
 */
	public function testSavePluginsRole($roleKey, $pluginKey, $count) {
		if ($pluginKey === 'user_manager') {
			$this->_mockForReturn('TestModel', 'UserRoles.UserAttributesRole', 'saveUserAttributesRoles', true, 1);
		} else {
			$this->_mockForReturn('TestModel', 'UserRoles.UserAttributesRole', 'saveUserAttributesRoles', true, 0);
		}

		//実行前のチェック
		$PluginsRole = ClassRegistry::init('PluginManager.PluginsRole');
		$before = $PluginsRole->find('count', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey, 'plugin_key' => $pluginKey),
		));
		$this->assertEquals($count, $before);

		//テスト実施
		$result = $this->TestModel->savePluginsRole($roleKey, $pluginKey);
		$this->assertTrue($result);

		//チェック
		$after = $this->TestModel->PluginsRole->find('count', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey, 'plugin_key' => $pluginKey),
		));
		$this->assertEquals(1, $after);
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - roleKey roles.key
 *  - pluginKey plugins.key
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		return array(
			array('roleKey' => 'administrator', 'pluginKey' => 'user_manager',
				'UserRoles.PluginsRole', 'save'),
			array('roleKey' => 'administrator', 'pluginKey' => 'user_manager',
				'UserRoles.UserAttributesRole', 'saveUserAttributesRoles'),
		);
	}

/**
 * SaveのExceptionErrorテスト
 *
 * @param string $roleKey roles.key
 * @param string $pluginKey plugins.key
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnExceptionError
 * @return void
 */
	public function testSaveOnExceptionError($roleKey, $pluginKey, $mockModel, $mockMethod) {
		$this->_mockForReturnFalse('TestModel', $mockModel, $mockMethod);

		$this->setExpectedException('InternalErrorException');
		$this->TestModel->savePluginsRole($roleKey, $pluginKey);;
	}

}
