<?php
/**
 * View/Elements/UserAttributesRoles/render_edit_colのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/UserAttributesRoles/render_edit_colのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\UserAttributesRoles\RenderEditCol
 */
class UserRolesViewElementsUserAttributesRolesRenderEditColTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute_layout',
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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'UserRoles', 'TestUserRoles');
		//テストコントローラ生成
		$this->generateNc('TestUserRoles.TestViewElementsUserAttributesRolesRenderEditCol');
	}

/**
 * View/Elements/UserAttributesRoles/render_edit_colのテスト
 *
 * @return void
 */
	public function testRenderEditCol() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_view_elements_user_attributes_roles_render_edit_col/render_edit_col',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/UserAttributesRoles/render_edit_col', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$name = 'data[UserAttributesRole][7][UserAttributesRole][other_user_attribute_role]';
		$pattern = '<select name="' . preg_quote($name, '/') . '".*?>';
		$this->assertRegExp($pattern, $this->view);
	}

}
