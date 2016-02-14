<?php
/**
 * View/Elements/tabsのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/tabsのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\Tabs
 */
class UserRolesViewElementsTabsTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestUserRoles.TestViewElementsTabs');
	}

/**
 * View/Elements/tabsのテスト(addの場合のテスト)
 *
 * @return void
 */
	public function testTabsAdd() {
		//テスト実行
		$this->_testNcAction('/test_user_roles/test_view_elements_tabs/add', array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('View/Elements/tabs', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->view = str_replace("\n", '', $this->view);
		$this->view = str_replace("\t", '', $this->view);

		$this->assertNotContains('/user_roles/edit/test_user"', $this->view);
		$this->assertNotContains('/user_role_settings/edit/test_user"', $this->view);
		$this->assertNotContains('/user_attributes_roles/edit/test_user"', $this->view);

		$pattern = '/<li class="active"><a href=".*' . preg_quote('/user_roles/add', '/') . '">/';
		$this->assertRegExp($pattern, $this->view);
	}

/**
 * View/Elements/tabsのテスト(editの場合のテスト)用DataProvider
 *
 * ### 戻り値
 *  - role 会員権限、nullはログインなし
 *  - exception Exception文字列
 *
 * @return array
 */
	public function dataProvider() {
		return array(
			array('user_roles'),
			array('user_role_settings'),
			array('user_attributes_roles'),
		);
	}

/**
 * View/Elements/tabsのテスト(editの場合のテスト)
 *
 * @param string $testController アクティブになっているコントローラ
 * @dataProvider dataProvider
 * @return void
 */
	public function testTabsEdit($testController) {
		//テストコントローラ生成
		$this->generateNc('TestUserRoles.TestViewElementsTabs');

		//テスト実行
		$this->_testNcAction('/test_user_roles/test_view_elements_tabs/edit/' . $testController, array(
			'method' => 'get'
		));

		//チェック
		$pattern = '/' . preg_quote('View/Elements/tabs', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		$this->view = str_replace("\n", '', $this->view);
		$this->view = str_replace("\t", '', $this->view);

		$this->assertContains('/user_roles/edit/test_user"', $this->view);
		$this->assertContains('/user_role_settings/edit/test_user"', $this->view);
		$this->assertContains('/user_attributes_roles/edit/test_user"', $this->view);

		$pattern = '/<li class="active"><a href=".*' . preg_quote('/' . $testController . '/edit/test_user', '/') . '">/';
		$this->assertRegExp($pattern, $this->view);
	}

}
