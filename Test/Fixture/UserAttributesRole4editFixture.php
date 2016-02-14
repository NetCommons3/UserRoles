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

App::uses('UserAttributesRoleFixture', 'UserRoles.Test/Fixture');

/**
 * UserAttributesRoleFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserAttributes\Model
 */
class UserAttributesRole4editFixture extends UserAttributesRoleFixture {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'UserAttributesRole';

/**
 * Full Table Name
 *
 * @var string
 */
	public $table = 'user_attributes_roles';

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'role_key' => 'system_administrator', 'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'system_administrator', 'user_attribute_key' => 'radio_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'system_administrator', 'user_attribute_key' => 'system_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'administrator', 'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'administrator', 'user_attribute_key' => 'radio_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'administrator', 'user_attribute_key' => 'system_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1',
		),
		array(
			'role_key' => 'common_user', 'user_attribute_key' => 'user_attribute_key',
			'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0',
		),
		array(
			'role_key' => 'common_user', 'user_attribute_key' => 'radio_attribute_key',
			'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0',
		),
		array(
			'role_key' => 'common_user', 'user_attribute_key' => 'system_attribute_key',
			'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0',
		),
	);
}
