<?php
/**
 * UserRoleSetting::saveUserRoleSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('UserRoleSettingFixture', 'UserRoles.Test/Fixture');

/**
 * UserRoleSetting::saveUserRoleSetting()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRoleSetting
 */
class UserRoleSettingSaveUserRoleSettingTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
	protected $_methodName = 'saveUserRoleSetting';

/**
 * テストデータセット
 *
 * @param int $value 値
 * @return void
 */
	private function __data($value) {
		$data = array (
			'UserRoleSetting' => array (
				'id' => '2', 'role_key' => 'administrator', 'origin_role_key' => 'administrator', 'use_private_room' => $value,
			),
		);

		return $data;
	}

/**
 * 期待値用データ取得
 *
 * @param bool $value use_private_room値
 * @return void
 */
	private function __actual($value) {
		$model = $this->_modelName;

		$actual = array();
		$actual = $this->$model->find('first', array(
			'recursive' => -1,
			'conditions' => array('role_key' => 'administrator'),
		));
		$actual = Hash::insert($actual, 'UserRoleSetting.use_private_room', $value);
		$actual = Hash::remove($actual, 'UserRoleSetting.modified');
		$actual = Hash::remove($actual, 'UserRoleSetting.modified_user');

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
		$expected = $this->$model->find('first', array(
			'recursive' => -1,
			'conditions' => array('role_key' => 'administrator'),
		));
		$expected = Hash::remove($expected, 'UserRoleSetting.modified');
		$expected = Hash::remove($expected, 'UserRoleSetting.modified_user');

		return $expected;
	}

/**
 * Saveのテスト(新規)
 *
 * @return void
 */
	public function testSave4New() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テストデータ生成
		$data = $this->__data('1');

		//期待値のデータ取得
		$actual = $this->__actual(true);

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertTrue($result);

		//データチェック
		$expected = $this->__expected();
		$this->assertEquals($actual, $expected);
	}

/**
 * Saveのテスト(削除)
 *
 * @return void
 */
	public function testSave4Delete() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テストデータ生成
		$data = $this->__data('0');

		//期待値のデータ取得
		$actual = $this->__actual(false);

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertTrue($result);

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
		$data = $this->__data('1');

		return array(
			array($data, 'UserRoles.UserRoleSetting', 'save'),
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
		$data = $this->__data('1');

		return array(
			array($data, 'UserRoles.UserRoleSetting'),
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
