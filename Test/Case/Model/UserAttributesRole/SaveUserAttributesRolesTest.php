<?php
/**
 * UserAttributesRole::saveUserAttributesRoles()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');
App::uses('UserAttributesRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserAttributesRole::saveUserAttributesRoles()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserAttributesRole
 */
class UserAttributesRoleSaveUserAttributesRolesTest extends NetCommonsSaveTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
	protected $_methodName = 'saveUserAttributesRoles';

/**
 * テストデータセット
 *
 * @return void
 */
	private function __data() {
		$data = array('UserAttributesRole' => array(
			array('UserAttributesRole' => array(
				'id' => '2',
				'role_key' => 'administrator',
				'user_attribute_key' => 'user_attribute_key',
				'self_readable' => false,
				'self_editable' => false,
				'other_readable' => false,
				'other_editable' => false,
			)),
			array('UserAttributesRole' => array(
				'id' => null,
				'role_key' => 'administrator',
				'user_attribute_key' => 'test_attribute_key',
				'self_readable' => true,
				'self_editable' => true,
				'other_readable' => false,
				'other_editable' => false,
			)),
		));

		return $data;
	}

/**
 * 期待値用データ取得
 *
 * @param array $data テストデータ
 * @return void
 */
	private function __actual($data) {
		$model = $this->_modelName;

		$actual = $this->$model->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => 'administrator'),
			'order' => array('id' => 'asc'),
		));
		$this->assertCount(1, $actual);
		$actual = Hash::remove($actual, '{n}.{s}.created');
		$actual = Hash::remove($actual, '{n}.{s}.created_user');
		$actual = Hash::remove($actual, '{n}.{s}.modified');
		$actual = Hash::remove($actual, '{n}.{s}.modified_user');

		$checked = array(
			array('UserAttributesRole' => array(
				'id' => '2',
				'role_key' => 'administrator',
				'user_attribute_key' => 'user_attribute_key',
				'self_readable' => true,
				'self_editable' => true,
				'other_readable' => true,
				'other_editable' => true,
			)),
		);
		$this->assertEquals($checked, $actual);

		$actual = Hash::merge($actual, $data['UserAttributesRole']);
		$actual = Hash::insert($actual, '1.UserAttributesRole.id', '5');

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
		$expected = $this->$model->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => 'administrator'),
			'order' => array('id' => 'asc'),
		));
		$expected = Hash::remove($expected, '{n}.{s}.created');
		$expected = Hash::remove($expected, '{n}.{s}.created_user');
		$expected = Hash::remove($expected, '{n}.{s}.modified');
		$expected = Hash::remove($expected, '{n}.{s}.modified_user');

		return $expected;
	}

/**
 * Save用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array テストデータ
 */
	public function dataProviderSave() {
		$results = array();
		$results[0] = array($this->__data());

		return $results;
	}

/**
 * Saveのテスト
 *
 * @param array $data 登録データ
 * @dataProvider dataProviderSave
 * @return void
 */
	public function testSave($data) {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//チェック用データ取得
		$actual = $this->__actual($data);

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertTrue($result);

		//登録データ取得
		$expected = $this->__expected();

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
		return array(
			array($this->__data(), 'UserRoles.UserAttributesRole', 'saveMany'),
		);
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
		return array(
			array($this->__data(), 'UserRoles.UserAttributesRole', 'validateMany'),
		);
	}

}
