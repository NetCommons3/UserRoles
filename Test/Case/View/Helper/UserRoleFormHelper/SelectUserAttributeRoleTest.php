<?php
/**
 * UserRoleFormHelper::selectUserAttributeRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');
App::uses('UserAttributeFixture', 'UserAttributeFixtures.Test/Fixture');
App::uses('UserRoleSettingFixture', 'UserAttributeFixtures.Test/Fixture');
App::uses('UserAttributesRoleFixture', 'UserRoles.Test/Fixture');
App::uses('UserRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserRoleFormHelper::selectUserAttributeRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\View\Helper\UserRoleFormHelper
 */
class UserRoleFormHelperSelectUserAttributeRoleTest extends NetCommonsHelperTestCase {

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
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - requestData $this->request->dataのマージする値
 *  - userAttribute $userAttributeにマージする値
 *  - asserts チェック内容
 *
 * @return array テストデータ
 */
	public function dataProvider() {
		$results = array();

		// * 会員管理が使える、管理者のみ読み取り可、他人の項目を読み取り可
		$index = 0;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('other_editable' => true, 'other_readable' => true),
			'UserRoleSetting' => array('is_usable_user_manager' => true),
			'UserAttribute' => array(),
			'UserAttributeSetting' => array('only_administrator_readable' => true),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => true,
			'options' => array('other_not_readable', 'other_readable', 'other_editable'),
			'optionSelected' => 'other_editable'
		);
		// * 会員管理が使えない、管理者のみ読み取り可、他人の項目を読み取り不可
		$index = 1;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('other_editable' => false, 'other_readable' => false),
			'UserRoleSetting' => array('is_usable_user_manager' => false),
			'UserAttribute' => array(),
			'UserAttributeSetting' => array('only_administrator_readable' => true),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => true,
			'options' => array('other_not_readable'),
			'optionSelected' => 'other_not_readable'
		);
		// * 会員管理が使えない、管理者以外も読み取り可、他人の項目を読み取り可
		$index = 2;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('other_editable' => false, 'other_readable' => true),
			'UserRoleSetting' => array('is_usable_user_manager' => false),
			'UserAttribute' => array(),
			'UserAttributeSetting' => array('only_administrator_readable' => false),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => false,
			'options' => array('other_not_readable', 'other_readable'),
			'optionSelected' => 'other_readable'
		);
		// * 会員管理が使えない、管理者以外も読み取り可、他人の項目を読み取り不可
		$index = 3;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('other_editable' => false, 'other_readable' => false),
			'UserRoleSetting' => array('is_usable_user_manager' => false),
			'UserAttribute' => array(),
			'UserAttributeSetting' => array('only_administrator_readable' => false),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => false,
			'options' => array('other_not_readable', 'other_readable'),
			'optionSelected' => 'other_not_readable'
		);
		// * ハンドル
		$index = 4;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('user_attribute_key' => 'handlename', 'other_editable' => false, 'other_readable' => true),
			'UserRoleSetting' => array('is_usable_user_manager' => false),
			'UserAttribute' => array('key' => 'handlename'),
			'UserAttributeSetting' => array('only_administrator_readable' => false),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => false,
			'options' => array('other_readable'),
			'optionSelected' => 'other_readable'
		);
		// * パスワード
		$index = 5;
		$results[$index]['data'] = array(
			'UserAttributesRole' => array('other_editable' => false, 'other_readable' => false),
			'UserRoleSetting' => array('is_usable_user_manager' => false),
			'UserAttribute' => array(),
			'UserAttributeSetting' => array(
				'data_type_key' => 'password',
				'only_administrator_readable' => false
			),
		);
		$results[$index]['asserts'] = array(
			'selectDisabled' => false,
			'options' => array('other_not_readable'),
			'optionSelected' => 'other_not_readable'
		);

		return $results;
	}

/**
 * selectUserAttributeRole()のテスト
 *
 * @param array $data マージする値
 * @param array $asserts チェック内容
 * @dataProvider dataProvider
 * @return void
 */
	public function testSelectUserAttributeRole($data, $asserts) {
		//テストデータ生成
		$viewVars = array();
		$requestData = array(
			'UserAttributesRole' => array('3' => array(
				'UserAttributesRole' => (new UserAttributesRoleFixture())->records[2]
			)),
			'UserRoleSetting' => (new UserRoleFixture())->records[0]
		);
		$requestData['UserAttributesRole']['3']['UserAttributesRole'] = Hash::merge(
			$requestData['UserAttributesRole']['3']['UserAttributesRole'],
			$data['UserAttributesRole']
		);
		$requestData['UserRoleSetting'] = Hash::merge($requestData['UserRoleSetting'], $data['UserRoleSetting']);

		//Helperロード
		$this->loadHelper('UserRoles.UserRoleForm', $viewVars, $requestData);

		//データ生成
		$userAttribute['UserAttribute'] = Hash::merge(
			(new UserAttributeFixture())->records[1], $data['UserAttribute']
		);
		$userAttribute['UserAttributeSetting'] = Hash::merge(
			(new UserAttributeSettingFixture())->records[0], $data['UserAttributeSetting']
		);

		//テスト実施
		$result = $this->UserRoleForm->selectUserAttributeRole($userAttribute);
		debug($result);

		//チェック
		$id = '3';

		// * UserAttributesRoleのhiddenのチェック
		if ($data['UserRoleSetting']['is_usable_user_manager']) {
			//会員管理が使える人
			$this->assertNotContains('data[UserAttributesRole][' . $id . '][UserAttributesRole][id]', $result);
			$this->assertNotContains('data[UserAttributesRole][' . $id . '][UserAttributesRole][role_key]', $result);
			$this->assertNotContains('data[UserAttributesRole][' . $id . '][UserAttributesRole][user_attribute_key]', $result);
		} else {
			//会員管理が使えない人
			$this->assertInput(
				'input', 'data[UserAttributesRole][' . $id . '][UserAttributesRole][id]', $id, $result
			);
			$this->assertInput(
				'input', 'data[UserAttributesRole][' . $id . '][UserAttributesRole][role_key]', 'common_user', $result
			);
			$this->assertInput(
				'input', 'data[UserAttributesRole][' . $id . '][UserAttributesRole][user_attribute_key]', $userAttribute['UserAttribute']['key'], $result
			);
		}
		// * selectタグのチェック
		$name = 'data[UserAttributesRole][' . $id . '][UserAttributesRole][other_user_attribute_role]';
		if ($asserts['selectDisabled']) {
			$pattern = '<select name="' . preg_quote($name, '/') . '".*?disabled="disabled".*?>';
		} else {
			$pattern = '<select name="' . preg_quote($name, '/') . '".*?>';
		}
		$this->assertRegExp($pattern, $result);

		// * optionタグのvalue=other_not_readableのチェック
		$this->__assertOption($result, 'other_not_readable', $asserts['options'], $asserts['optionSelected']);

		// * optionタグのvalue=other_readableのチェック
		$this->__assertOption($result, 'other_readable', $asserts['options'], $asserts['optionSelected']);

		// * optionタグのvalue=other_editableのチェック
		$this->__assertOption($result, 'other_editable', $asserts['options'], $asserts['optionSelected']);
	}

/**
 * selectUserAttributeRole()のテスト
 *
 * @param string $result テスト結果
 * @param string $value オプションの値
 * @param array $options オプション配列
 * @param string $selected selectしている値
 * @dataProvider dataProvider
 * @return void
 */
	public function __assertOption($result, $value, $options, $selected) {
		if (in_array($value, $options, true)) {
			if ($selected === $value) {
				$this->assertContains('<option value="' . $value . '" selected="selected">', $result);
			} else {
				$this->assertContains('<option value="' . $value . '">', $result);
			}
		} else {
			$this->assertNotContains('value="' . $value . '"', $result);
		}
	}

}
