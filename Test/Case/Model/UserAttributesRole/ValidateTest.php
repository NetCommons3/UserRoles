<?php
/**
 * UserAttributesRole::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('UserAttributesRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserAttributesRole::validate()のテスト
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Test\Case\Model\UserAttributesRole
 */
class UserAttributesRoleValidateTest extends NetCommonsValidateTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.user_roles.user_attributes_role',
		'plugin.user_roles.user_role_setting',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'user_roles';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'UserAttributesRole';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'validate';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 */
	public function dataProviderValidationError() {
		$data['UserAttributesRole'] = (new UserAttributesRoleFixture())->records[0];

		return array(
			array('data' => $data, 'field' => 'role_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'user_attribute_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'self_readable', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'self_editable', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'other_readable', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'other_editable', 'value' => '2',
				'message' => __d('net_commons', 'Invalid request.')),
		);
	}

}
