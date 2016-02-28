<?php
/**
 * View/Elements/UserAttributesRoles/render_edit_rowのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/UserAttributesRoles/render_edit_rowのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\UserAttributesRoles\RenderEditRow
 */
class UserRolesViewElementsUserAttributesRolesRenderEditRowTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute_layout',
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
		//テストコントローラ生成
		$this->generateNc('TestUserRoles.TestViewElementsUserAttributesRolesRenderEditRow');
	}

/**
 * View/Elements/UserAttributesRoles/render_edit_rowのテスト
 *
 * @return void
 */
	public function testRenderEditRow() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_view_elements_user_attributes_roles_render_edit_row/render_edit_row',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserAttributesRoles/render_edit_row', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertContains(sprintf(__d('user_attributes', '%s row'), 1), $this->view);
	}

}
