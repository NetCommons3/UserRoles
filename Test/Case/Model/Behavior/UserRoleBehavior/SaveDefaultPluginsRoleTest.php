<?php
/**
 * UserRoleBehavior::saveDefaultPluginsRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * UserRoleBehavior::saveDefaultPluginsRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\Behavior\UserRoleBehavior
 */
class UserRoleBehaviorSaveDefaultPluginsRoleTest extends NetCommonsModelTestCase {

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
 * 期待値用データ取得
 *
 * @param string $roleKey 権限キー
 * @return void
 */
	private function __actual($roleKey) {
		$actual = $this->TestModel->PluginsRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey),
			'order' => array('id' => 'asc'),
		));
		$actual = Hash::remove($actual, '{n}.{s}.id');
		$actual = Hash::remove($actual, '{n}.{s}.created');
		$actual = Hash::remove($actual, '{n}.{s}.created_user');
		$actual = Hash::remove($actual, '{n}.{s}.modified');
		$actual = Hash::remove($actual, '{n}.{s}.modified_user');

		return $actual;
	}

/**
 * チェック用データ取得
 *
 * @param string $roleKey 権限キー
 * @return void
 */
	private function __expected($roleKey) {
		$expected = $this->TestModel->PluginsRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey),
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::remove($expected, '{n}.{s}.id');
		$expected = Hash::remove($expected, '{n}.{s}.created');
		$expected = Hash::remove($expected, '{n}.{s}.created_user');
		$expected = Hash::remove($expected, '{n}.{s}.modified');
		$expected = Hash::remove($expected, '{n}.{s}.modified_user');

		return $expected;
	}

/**
 * saveDefaultPluginsRole()テストのDataProvider
 *
 * ### 戻り値
 *  - data User role data
 *
 * @return array データ
 */
	public function dataProvider() {
		$result = array();
		$result[0]['data'] = array('UserRoleSetting' => array(
			'origin_role_key' => 'administrator',
			'role_key' => 'test_admin_user2',
		));
		$result[1]['data'] = array('UserRoleSetting' => array(
			'origin_role_key' => 'common_user',
			'role_key' => 'test_general_user2',
		));

		return $result;
	}

/**
 * saveDefaultPluginsRole()のテスト
 *
 * @param array $data User role data
 * @dataProvider dataProvider
 * @return void
 */
	public function testSaveDefaultPluginsRole($data) {
		//テスト実施
		$result = $this->TestModel->saveDefaultPluginsRole($data);

		//期待値データ取得
		$actual = $this->__actual($data['UserRoleSetting']['origin_role_key']);
		$actual = Hash::insert($actual, '{n}.{s}.role_key', $data['UserRoleSetting']['role_key']);

		//チェック用データ取得
		$expected = $this->__expected($data['UserRoleSetting']['role_key']);

		//チェック
		$this->assertTrue($result);
		$this->assertEquals($expected, $actual);
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		$data = array('UserRoleSetting' => array(
			'origin_role_key' => 'administrator',
			'role_key' => 'test_admin_user2',
		));

		return array(
			array($data, 'PluginManager.PluginsRole', 'saveMany'),
		);
	}

/**
 * SaveのExceptionErrorテスト
 *
 * @param array $data 登録データ
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnExceptionError
 * @return void
 */
	public function testSaveOnExceptionError($data, $mockModel, $mockMethod) {
		$this->_mockForReturnFalse('TestModel', $mockModel, $mockMethod);

		$this->setExpectedException('InternalErrorException');
		$this->TestModel->saveDefaultPluginsRole($data);
	}

}
