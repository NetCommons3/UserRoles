<?php
/**
 * UserAttributesRoleBehavior::defaultUserAttributeRole()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');
App::uses('UserAttribute', 'UserAttributes.Model');
App::uses('DataType', 'DataTypes.Model');

/**
 * UserAttributesRoleBehavior::defaultUserAttributeRole()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\Behavior\UserAttributesRoleBehavior
 */
class UserAttributesRoleBehaviorDefaultUserAttributeRoleTest extends NetCommonsCakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_attributes.user_attribute',
		'plugin.user_attributes.user_attribute_setting',
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
		$this->TestModel = ClassRegistry::init('TestUserRoles.TestUserAttributesRoleBehaviorModel');
	}

/**
 * defaultUserAttributeRole()テストのDataProvider
 *
 * ### 戻り値
 *  - userAttrSetting 配列：ユーザ属性設定データ、文字列：ユーザ属性キー
 *  - enableUserManager 有効かどうか
 *  - expected 期待値
 *
 * @return array データ
 */
	public function dataProvider() {
		$data = array();
		// * パスワード＝自分／他人とも読み取り不可
		$data[0]['userAttrSetting'] = array(
			'user_attribute_key' => UserAttribute::PASSWORD_FIELD,
			'data_type_key' => DataType::DATA_TYPE_PASSWORD,
		);
		$data[0]['enableUserManager'] = true;
		$data[0]['expected'] = array(
			'self_readable' => false, 'self_editable' => true, 'other_readable' => false, 'other_editable' => true
		);

		// * ラベル項目＝自分／他人とも書き込み不可
		$data[1]['userAttrSetting'] = array(
			'user_attribute_key' => 'test2',
			'data_type_key' => DataType::DATA_TYPE_LABEL,
		);
		$data[1]['enableUserManager'] = true;
		$data[1]['expected'] = array(
			'self_readable' => true, 'self_editable' => false, 'other_readable' => true, 'other_editable' => false
		);

		// * 会員管理が使用可＝上記以外、自分・他人とも自分／他人の読み・書き可
		$data[2]['userAttrSetting'] = array(
			'user_attribute_key' => 'test2',
			'data_type_key' => DataType::DATA_TYPE_TEXT,
		);
		$data[2]['enableUserManager'] = true;
		$data[2]['expected'] = array(
			'self_readable' => true, 'self_editable' => true, 'other_readable' => true, 'other_editable' => true
		);

		// * 会員管理が使用不可
		// ** 管理者以外、読み取り不可項目とする＝自分／他人の読み・書き不可。※読めないのに書けるはあり得ないため。
		$data[3]['userAttrSetting'] = array(
			'user_attribute_key' => 'test2',
			'data_type_key' => DataType::DATA_TYPE_TEXT,
			'only_administrator_readable' => true,
			'only_administrator_editable' => false,
		);
		$data[3]['enableUserManager'] = false;
		$data[3]['expected'] = array(
			'self_readable' => false, 'self_editable' => false, 'other_readable' => false, 'other_editable' => false
		);
		$data[4] = Hash::merge($data[3], array(
			'userAttrSetting' => array(
				'only_administrator_readable' => true,
				'only_administrator_editable' => true,
			),
		));
		// ** 管理者以外、書き込み不可項目とする＝自分／他人の書き込み不可。
		$data[5] = Hash::merge($data[3], array(
			'userAttrSetting' => array(
				'only_administrator_readable' => false,
				'only_administrator_editable' => true,
			),
			'expected' => array(
				'self_readable' => true, 'self_editable' => false, 'other_readable' => false, 'other_editable' => false
			),
		));
		// ** 上記以外
		// *** ハンドル・アバター＝自分は、読み・書き可。他人は、読み取り可／書き込み不可。
		// **** ハンドル
		$data[6]['userAttrSetting'] = array(
			'user_attribute_key' => UserAttribute::HANDLENAME_FIELD,
			'data_type_key' => DataType::DATA_TYPE_TEXT,
			'only_administrator_readable' => false,
			'only_administrator_editable' => false,
		);
		$data[6]['enableUserManager'] = false;
		$data[6]['expected'] = array(
			'self_readable' => true, 'self_editable' => true, 'other_readable' => true, 'other_editable' => false
		);
		// **** アバター
		$data[7]['userAttrSetting'] = array(
			'user_attribute_key' => UserAttribute::AVATAR_FIELD,
			'data_type_key' => DataType::DATA_TYPE_IMG,
			'only_administrator_readable' => false,
			'only_administrator_editable' => false,
		);
		$data[7]['enableUserManager'] = false;
		$data[7]['expected'] = array(
			'self_readable' => true, 'self_editable' => true, 'other_readable' => true, 'other_editable' => false
		);
		// *** それ以外＝自分は、読み・書き可。他人は、読み・書き不可。
		$data[8] = Hash::merge($data[3], array(
			'userAttrSetting' => array(
				'only_administrator_readable' => false,
				'only_administrator_editable' => false,
			),
			'expected' => array(
				'self_readable' => true, 'self_editable' => true, 'other_readable' => false, 'other_editable' => false
			),
		));

		return $data;
	}

/**
 * defaultUserAttributeRole()のテスト(enableUserManager=true)
 *
 * * パスワード＝自分／他人とも読み取り不可
 * * ラベル項目＝自分／他人とも書き込み不可
 * * 会員管理が使用可＝上記以外、自分・他人とも自分／他人の読み・書き可
 * * 会員管理が使用不可
 * ** 管理者以外、読み取り不可項目とする＝自分／他人の読み・書き不可。※読めないのに書けるはあり得ないため。
 * ** 管理者以外、書き込み不可項目とする＝自分／他人の書き込み不可。
 * ** 上記以外
 * *** ハンドル・アバター＝自分は、読み・書き可。他人は、読み取り可／書き込み不可。
 * *** それ以外＝自分は、読み・書き可。他人は、読み・書き不可。
 *
 * @param array|string $userAttrSetting 配列：ユーザ属性設定データ、文字列：ユーザ属性キー
 * @param bool $enableUserManager 有効かどうか
 * @param array $expected 期待値
 * @dataProvider dataProvider
 * @return void
 */
	public function testDefaultUserAttributeRole($userAttrSetting, $enableUserManager, $expected) {
		//テスト実施
		$result = $this->TestModel->defaultUserAttributeRole($userAttrSetting, $enableUserManager);

		//チェック
		$this->assertEquals($expected, $result['UserAttributesRole']);
	}

}
