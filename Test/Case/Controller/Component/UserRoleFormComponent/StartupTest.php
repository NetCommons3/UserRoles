<?php
/**
 * UserRoleFormComponent::startup()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesNetCommonsControllerTestCase', 'UserRoles.TestSuite');

/**
 * UserRoleFormComponent::startup()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Controller\Component\UserRoleFormComponent
 */
class UserRoleFormComponentStartupTest extends UserRolesNetCommonsControllerTestCase {

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
		//テストコントローラ生成
		$this->generateNc('TestUserRoles.TestUserRoleFormComponent');
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
 * startup()のテスト
 *
 * @return void
 */
	public function testStartup() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_user_role_form_component/index',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/UserRoleFormComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertTrue(in_array('UserRoles.UserRoleForm', $this->controller->helpers, true));
		$this->assertArrayHasKey('defaultRoleOptions', $this->vars);
		$this->assertEquals(
			array('room_administrator', 'chief_editor', 'editor', 'general_user', 'visitor'),
			array_keys($this->vars['defaultRoleOptions'])
		);
	}

/**
 * startup()のテスト(requestActionのテスト)
 *
 * @return void
 */
	public function testStartupRequestAction() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_user_role_form_component/index_request_action',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('Controller/Component/UserRoleFormComponent/index', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$pattern = '/' . preg_quote('Controller/Component/UserRoleFormComponent/index_request_action', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
	}
}
