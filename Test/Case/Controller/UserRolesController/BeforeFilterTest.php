<?php
/**
 * UserRolesController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesNetCommonsControllerTestCase', 'UserRoles.TestSuite');

/**
 * UserRolesController::beforeFilter()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\UserRolesController
 */
class UserRolesControllerBeforeFilterTest extends UserRolesNetCommonsControllerTestCase {

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
 * beforeFilter()アクションのテスト(index()の場合)
 *
 * @return void
 */
	public function testBeforeFilterIndex() {
		//テスト実行
		$this->_testGetAction(array('action' => 'index'), array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertTrue(Hash::check($this->vars['userRoles'], '{n}.UserRole'));
	}

}
