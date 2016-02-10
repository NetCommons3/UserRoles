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
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'role_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_attribute_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'self_readable' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'self_editable' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'other_readable' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'other_editable' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

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
	);

}
