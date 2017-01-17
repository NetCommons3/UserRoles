<?php
/**
 * UserAttributesRoleFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * UserAttributesRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserAttributesRoleFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'role_key' => 'system_administrator',
			'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1',
			'self_editable' => '1',
			'other_readable' => '1',
			'other_editable' => '1',
		),
		array(
			'id' => '2',
			'role_key' => 'administrator',
			'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1',
			'self_editable' => '1',
			'other_readable' => '1',
			'other_editable' => '1',
		),
		array(
			'id' => '3',
			'role_key' => 'common_user',
			'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1',
			'self_editable' => '1',
			'other_readable' => '0',
			'other_editable' => '0',
		),
		array(
			'id' => '4',
			'role_key' => 'test_user',
			'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1',
			'self_editable' => '1',
			'other_readable' => '0',
			'other_editable' => '0',
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('UserRoles') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new UserRolesSchema())->tables['user_attributes_roles'];
		parent::init();
	}

}
