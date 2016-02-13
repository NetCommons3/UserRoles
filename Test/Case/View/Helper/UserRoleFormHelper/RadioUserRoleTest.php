<?php
/**
 * UserRoleFormHelper::radioUserRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * UserRoleFormHelper::radioUserRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Helper\UserRoleFormHelper
 */
class UserRoleFormHelperRadioUserRoleTest extends NetCommonsHelperTestCase {

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
	}

/**
 * radioUserRole()のテストのDataProvider
 *
 * ### 戻り値
 *  - roleKey 権限キー
 *
 * @return array データ
 */
	public function dataProvider() {
		return array(
			// * 一般権限、プラグイン、属性無し
			array('roleKey' => 'common_user', 'isPlugin' => true, 'attributes' => array()),
			// * 一般権限、プラグインでない、属性無し
			array('roleKey' => 'common_user', 'isPlugin' => false, 'attributes' => array()),
			// * 一般権限、プラグイン、属性あり
			array('roleKey' => 'common_user', 'isPlugin' => true, 'attributes' => array('value' => '0')),
			// * 一般権限、プラグインでない、属性あり
			array('roleKey' => 'common_user', 'isPlugin' => false, 'attributes' => array('value' => '0')),
			// * サイト管理権限、プラグイン、属性無し
			array('roleKey' => 'administrator', 'isPlugin' => true, 'attributes' => array()),
			// * サイト管理権限、プラグインでない、属性無し
			array('roleKey' => 'administrator', 'isPlugin' => false, 'attributes' => array()),
			// * サイト管理権限、プラグイン、属性あり
			array('roleKey' => 'administrator', 'isPlugin' => true, 'attributes' => array('value' => '0')),
			// * サイト管理権限、プラグインでない、属性あり
			array('roleKey' => 'administrator', 'isPlugin' => false, 'attributes' => array('value' => '0')),
		);
	}

/**
 * radioUserRole()のテスト
 *
 * @param string $roleKye 権限キー
 * @param bool $isPlugin プラグインかどうかのフラグ
 * @param array $attributes 属性
 * @dataProvider dataProvider
 * @return void
 */
	public function testRadioUserRole($roleKey, $isPlugin, $attributes) {
		//テストデータ生成
		$viewVars = array();
		$requestData = array(
			'UserRoleSetting' => array('origin_role_key' => $roleKey),
			'Model' => array('field' => Hash::get($attributes, 'value', '1')),
		);

		//Helperロード
		$this->loadHelper('UserRoles.UserRoleForm', $viewVars, $requestData);

		//データ生成
		$fieldName = 'Model.field';

		//テスト実施
		$result = $this->UserRoleForm->radioUserRole($fieldName, $isPlugin, $attributes);

		//チェック
		if ($isPlugin && $roleKey === 'common_user') {
			if ($attributes) {
				$this->assertContains('<input type="hidden" name="data[Model][field]" value="0" id="ModelField"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField1" value="1" disabled="disabled"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField0" value="0" disabled="disabled" checked="checked"', $result);
			} else {
				$this->assertContains('<input type="hidden" name="data[Model][field]" value="1" id="ModelField"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField1" value="1" disabled="disabled" checked="checked"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField0" value="0" disabled="disabled"', $result);
			}
		} else {
			if ($attributes) {
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField1" value="1"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField0" value="0" checked="checked"', $result);
			} else {
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField1" value="1" checked="checked"', $result);
				$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField0" value="0"', $result);
			}
		}
	}

/**
 * radioUserRole()のテスト[サイト管理者＋プラグインでない]
 *
 * @return void
 */
	public function testAdminWOIsPlguin() {
		//テストデータ生成
		$viewVars = array(
			'UserRoleSetting' => array('origin_role_key' => 'administrator')
		);
		$requestData = array(
			'UserRoleSetting' => array('origin_role_key' => 'administrator'),
			'Model' => array('field' => '1'),
		);

		//Helperロード
		$this->loadHelper('UserRoles.UserRoleForm', $viewVars, $requestData);

		//データ生成
		$fieldName = 'Model.field';
		$isPlugin = false;
		$attributes = array();

		//テスト実施
		$result = $this->UserRoleForm->radioUserRole($fieldName, $isPlugin, $attributes);

		//チェック
		$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField1" value="1" checked="checked" />', $result);
		$this->assertContains('<input type="radio" name="data[Model][field]" id="ModelField0" value="0" />', $result);
	}

}
