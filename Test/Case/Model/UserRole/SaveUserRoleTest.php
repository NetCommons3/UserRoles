<?php
/**
 * UserRole::saveUserRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UserRole4testFixture', 'UserRoles.Test/Fixture');

/**
 * UserRole::saveUserRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRole
 */
class UserRoleSaveUserRoleTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_role4test',
		'plugin.user_roles.user_attributes_role',
		'plugin.user_roles.user_role_setting',
		'plugin.plugin_manager.plugins_role',
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
	protected $_modelName = 'UserRole';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveUserRole';

/**
 * テストデータセット
 *
 * @param bool $isNew 新規かどうか
 * @return void
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
	private function __data($isNew = false) {
		$data['UserRole'][0] = (new UserRole4testFixture())->records[6];
		$data['UserRole'][1] = (new UserRole4testFixture())->records[7];

		if ($isNew) {
			$data['UserRole'] = Hash::insert($data['UserRole'], '{n}.id', null);
			$data['UserRole'] = Hash::insert($data['UserRole'], '{n}.key', null);
			$data['UserRole'] = Hash::insert($data['UserRole'], '{n}.name', 'Add name');
			$data['UserRoleSetting']['is_usable_user_manager'] = false;
		} else {
			$data['UserRole'] = Hash::insert($data['UserRole'], '{n}.name', 'Edit name');
		}
		$data['UserRole'] = Hash::remove($data['UserRole'], '{n}.is_system');

		$data['UserRoleSetting']['origin_role_key'] = UserRole::USER_ROLE_KEY_COMMON_USER;

		return $data;
	}

/**
 * 期待値用データ取得
 *
 * @return void
 */
	private function __actual() {
		$model = $this->_modelName;

		$actual = array();
		$actual['UserRole'] = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$actual = Hash::remove($actual, '{s}.{n}.{s}.modified');
		$actual = Hash::remove($actual, '{s}.{n}.{s}.modified_user');

		return $actual;
	}

/**
 * チェック用データ取得
 *
 * @return void
 */
	private function __expected() {
		$model = $this->_modelName;

		$expected = array();
		$expected['UserRole'] = $this->$model->find('all', array(
			'recursive' => -1,
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::remove($expected, '{s}.{n}.{s}.modified');
		$expected = Hash::remove($expected, '{s}.{n}.{s}.modified_user');

		return $expected;
	}

/**
 * Saveのテスト(新規)
 *
 * @return void
 */
	//public function testSave4New() {
	//	$model = $this->_modelName;
	//	$method = $this->_methodName;
	//
	//	$mockMethods = array(
	//		//'saveDefaultUserRoleSetting',
	//		'saveDefaultUserAttributesRole',
	//		'saveDefaultPluginsRole',
	//	);
	//	$this->_mockForReturnTrue($model, 'UserRoles.UserRole', $mockMethods);
	//
	//	//テストデータ生成
	//	$data = $this->__data(true);
	//
	//	//期待値のデータ取得
	//	$actual = $this->__actual();
	//	$actual = Hash::remove($actual, '{s}.{n}.{s}.created_user');
	//	$actual = Hash::remove($actual, '{s}.{n}.{s}.created');
	//	foreach ($data['UserRole'] as $userRole) {
	//		$userRole['id'] = (string)(count($actual['UserRole']) + 1);
	//		$userRole['key'] = OriginalKeyBehavior::generateKey($this->$model->alias, $this->$model->useDbConfig);
	//		$userRole['is_system'] = false;
	//
	//		$actual['UserRole'][] = array('UserRole' => $userRole);
	//	}
	//
	//	//テスト実行
	//	$result = $this->$model->$method($data);
	//	$this->assertNotEmpty($result);
	//
	//	//戻り値チェック
	//	$this->assertCount(2, $result);
	//	$this->assertCount(2, Hash::extract($result, '{n}.UserRole'));
	//	$this->assertNotEmpty($result[0]['UserRole']['id']);
	//	$this->assertNotEmpty($result[0]['UserRole']['key']);
	//	$this->assertNotEmpty($result[1]['UserRole']['id']);
	//	$this->assertNotEmpty($result[1]['UserRole']['key']);
	//
	//	//データチェック
	//	$expected = $this->__expected();
	//	$expected = Hash::remove($expected, '{s}.{n}.{s}.created_user');
	//	$expected = Hash::remove($expected, '{s}.{n}.{s}.created');
	//
	//	$this->assertEquals($actual, $expected);
	//}

/**
 * Saveのテスト(更新)
 *
 * @return void
 */
	public function testSave4Update() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$mockMethods = array(
			//'saveDefaultUserRoleSetting',
			'saveDefaultUserAttributesRole',
			'saveDefaultPluginsRole',
		);
		$this->_mockForReturn($model, 'UserRoles.UserRole', $mockMethods, true, 0);

		//テストデータ生成
		$data = $this->__data(false);

		//期待値のデータ取得
		$actual = $this->__actual();
		$actual = Hash::insert($actual, 'UserRole.6.UserRole.name', 'Edit name');
		$actual = Hash::insert($actual, 'UserRole.7.UserRole.name', 'Edit name');

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertNotEmpty($result);

		//戻り値チェック
		$this->assertCount(2, $result);
		$this->assertCount(2, Hash::extract($result, '{n}.UserRole'));

		//データチェック
		$expected = $this->__expected();

		$this->assertEquals($actual, $expected);
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
		$data = array();
		$data['UserRole'][0] = (new UserRole4testFixture())->records[6];
		$data['UserRole'][1] = (new UserRole4testFixture())->records[7];

		return array(
			array($data, 'UserRoles.UserRole', 'save'),
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
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->_mockForReturnFalse($model, $mockModel, $mockMethod);

		$this->setExpectedException('InternalErrorException');
		$this->$model->$method($data);
	}

/**
 * SaveのValidationError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド(省略可：デフォルト validates)
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnValidationError() {
		$data = array();
		$data['UserRole'][0] = (new UserRole4testFixture())->records[6];
		$data['UserRole'][1] = (new UserRole4testFixture())->records[7];

		return array(
			array($data, 'UserRoles.UserRole', 'validateMany'),
		);
	}

/**
 * SaveのValidationErrorテスト
 *
 * @param array $data 登録データ
 * @param string $mockModel Mockのモデル
 * @param string $mockMethod Mockのメソッド
 * @dataProvider dataProviderSaveOnValidationError
 * @return void
 */
	public function testSaveOnValidationError($data, $mockModel, $mockMethod = 'validates') {
		$model = $this->_modelName;
		$method = $this->_methodName;

		$this->_mockForReturnFalse($model, $mockModel, $mockMethod);
		$result = $this->$model->$method($data);
		$this->assertFalse($result);
	}

}
