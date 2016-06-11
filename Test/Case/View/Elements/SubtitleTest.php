<?php
/**
 * View/Elements/subtitleのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/subtitleのテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Elements\Subtitle
 */
class UserRolesViewElementsSubtitleTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestUserRoles.TestViewElementsSubtitle');
	}

/**
 * View/Elements/subtitleのテスト
 *
 * @return void
 */
	public function testSubtitle() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_view_elements_subtitle/subtitle',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/subtitle', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertContains('<h2>TestSubtitle</h2>', $this->view);
	}

/**
 * subtitleなしテスト
 *
 * @return void
 */
	public function testNoSubtitle() {
		//テスト実行
		$this->_testGetAction('/test_user_roles/test_view_elements_subtitle/no_subtitle',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/subtitle', '/') . '/';
		$this->assertRegExp($pattern, $this->view);
		$this->assertNotContains('<h2>TestSubtitle</h2>', $this->view);
	}

}
