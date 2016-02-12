<?php
/**
 * UserRole::verifyDeletable()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * UserRole::verifyDeletable()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRole
 */
class UserRoleVerifyDeletableTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user4test',
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
	protected $_methodName = 'verifyDeletable';

/**
 * 削除権限ありテスト
 *
 * @return void
 */
	public function testDeletable() {
		//データ生成
		$roleKey = 'test_user';

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($roleKey);

		//チェック
		$this->assertTrue($result);
	}

/**
 * 削除権限なしテストのDataProvider
 *
 * ### 戻り値
 *  - roleKey 権限キー
 *
 * @return array データ
 */
	public function dataProvider() {
		return array(
			// * システム権限
			array('role_key' => 'common_user'),
			// * システム権限
			array('role_key' => 'test_user_2'),
		);
	}

/**
 * 削除権限なしテスト
 *
 * @param string $roleKey 権限キー
 * @dataProvider dataProvider
 * @return void
 */
	public function testNotDeletable($roleKey) {
		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$result = $this->$model->$methodName($roleKey);

		//チェック
		$this->assertFalse($result);
	}

/**
 * 不正リクエスト(BadRequestException)のテスト
 *
 * @return void
 */
	public function testBadRequestExceptionError() {
		$this->setExpectedException('BadRequestException');

		//テストデータ
		$roleKey = 'aaaa';

		//テスト実施
		$model = $this->_modelName;
		$methodName = $this->_methodName;
		$this->$model->$methodName($roleKey);
	}

/**
 * User->findがExceptionエラー(InternalErrorException)のテスト
 *
 * @return void
 */
	public function testInternalErrorExceptionError() {
		$this->setExpectedException('InternalErrorException');
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//テストデータ
		$this->_mockForReturnFalse($model, 'Users.User', 'find');
		$roleKey = 'test_user_2';

		//テスト実施
		$this->$model->$methodName($roleKey);
	}

}
