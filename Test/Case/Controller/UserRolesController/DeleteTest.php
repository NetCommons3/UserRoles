<?php
/**
 * UserRolesController::delete()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * UserRolesController::delete()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRolesController
 */
class UserRolesControllerDeleteTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute4edit',
		'plugin.user_attributes.user_attribute_choice4edit',
		'plugin.user_attributes.user_attribute_layout',
		'plugin.user_attributes.user_attribute_setting4edit',
		'plugin.user_roles.plugin4test',
		'plugin.user_roles.plugins_role4test',
		'plugin.user_roles.user_attributes_role4edit',
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
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'user_roles';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);
		parent::tearDown();
	}

/**
 * delete()アクションのGETパラメータテスト
 *
 * @return void
 */
	public function testGet() {
		//テスト実行
		$this->_testGetAction(array('action' => 'delete'), null, 'BadRequestException', 'view');
	}

/**
 * delete()アクションのGETパラメータテスト(JSON形式)
 *
 * @return void
 */
	public function testGetJson() {
		//テスト実行
		$this->_testGetAction(array('action' => 'delete'), null, 'BadRequestException', 'json');
	}

/**
 * delete()アクションのテスト
 *
 * @return void
 */
	public function testDelete() {
		$this->_mockForReturnTrue('UserRoles.UserRole', 'deleteUserRole');

		//テスト実行
		$this->_testPostAction('delete', array('UserRole' => array(array('key' => 'common_user'))), array('action' => 'delete'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_roles/index', $header['Location']);
	}

/**
 * delete()アクションのValidationエラーテスト
 *
 * @return void
 */
	public function testDeleteOnValidationError() {
		$this->_mockForReturnFalse('UserRoles.UserRole', 'deleteUserRole');

		//テスト実行
		$this->_testPostAction('delete', array('UserRole' => array(array('key' => 'common_user'))), array('action' => 'delete'), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$this->assertTextContains('/user_roles/user_roles/edit/common_user', $header['Location']);
	}

}
