<?php
/**
 * UserRole::deleteUserRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsDeleteTest', 'NetCommons.TestSuite');
App::uses('UserRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserRole::deleteUserRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserRole
 */
class UserRoleDeleteUserRoleTest extends NetCommonsDeleteTest {

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
	protected $_methodName = 'deleteUserRole';

/**
 * Delete用DataProvider
 *
 * ### 戻り値
 *  - data: 削除データ
 *  - associationModels: 削除確認の関連モデル array(model => conditions)
 *
 * @return array テストデータ
 */
	public function dataProviderDelete() {
		$results = array();
		$results[0] = array(array('key' => 'test_user'), array(
			'UserRoleSetting' => array('role_key' => 'test_user'),
			'UserAttributesRole' => array('role_key' => 'test_user'),
		));

		return $results;
	}

/**
 * ExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderDeleteOnExceptionError() {
		$data = array('key' => 'test_user');

		return array(
			array($data, 'UserRoles.UserRole', 'deleteAll'),
			array($data, 'UserRoles.UserAttributesRole', 'deleteAll'),
			array($data, 'UserRoles.UserRoleSetting', 'deleteAll'),
		);
	}

/**
 * 削除不可のデータのテスト(verifyDeletable()=false)
 *
 * @return void
 */
	public function testNotDeletable() {
		$model = $this->_modelName;
		$method = $this->_methodName;

		//テストデータ作成
		$data = array('key' => 'common_user');

		//テスト実行
		$result = $this->$model->$method($data);
		$this->assertFalse($result);
	}

}
