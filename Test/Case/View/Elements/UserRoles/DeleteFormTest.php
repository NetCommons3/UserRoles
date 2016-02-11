<?php
/**
 * View/Elements/UserRoles/delete_formのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/UserRoles/delete_formのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\UserRoles\DeleteForm
 */
class UserRolesViewElementsUserRolesDeleteFormTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

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
		$this->generateNc('TestUserRoles.TestViewElementsUserRolesDeleteForm');
	}

/**
 * View/Elements/UserRoles/delete_formのテスト
 *
 * @return void
 */
	public function testDeleteForm() {
		//テスト実行
		$this->_testNcAction('/test_user_roles/test_view_elements_user_roles_delete_form/delete_form', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserRoles/delete_form', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertContains('dangerZone', $this->view);
		$this->assertInput('form', null, '/user_roles/delete', $this->view);
		$this->assertInput('input', '_method', 'DELETE', $this->view);
		$this->assertInput('input', 'data[UserRole][0][id]', '8', $this->view);
		$this->assertInput('input', 'data[UserRole][0][key]', 'test_user', $this->view);
	}

/**
 * View/Elements/UserRoles/delete_formの削除不可テスト
 *
 * @return void
 */
	public function testDeleteFormIsNotDeletable() {
		//テスト実行
		$this->_testNcAction('/test_user_roles/test_view_elements_user_roles_delete_form/delete_form_is_not_deletable', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserRoles/delete_form', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->assertContains('dangerZone', $this->view);
		$this->assertContains(__d('user_roles', 'Can not be deleted because it has this authority is used.'), $this->view);

		$this->assertNotContains('<form', $this->view);
		$this->assertNotContains('/user_roles/delete', $this->view);
		$this->assertNotContains('_method', $this->view);
		$this->assertNotContains('data[UserRole][0][id]', $this->view);
		$this->assertNotContains('data[UserRole][0][key]', $this->view);
	}

}
