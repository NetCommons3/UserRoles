<?php
/**
 * UserRoleSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * UserRoleSettingFixture
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserRoleSettingFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'role_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'default_role_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'use_room_manager' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'ルーム管理の使用有無'),
		'use_user_manager' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => '会員管理の使用有無'),
		'public_room_creatable' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'パブリックスペース内にルームの新規作成許可'),
		'group_room_creatable' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'グループスペース内にルームの新規作成許可'),
		'use_private_room' => array('type' => 'boolean', 'null' => true, 'default' => null, 'comment' => 'プライベートルームの使用有無'),
		'private_room_upload_total_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プライベートの総容量'),
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
			'id' => 1,
			'role_key' => 'Lorem ipsum dolor sit amet',
			'default_role_key' => 'Lorem ipsum dolor sit amet',
			'use_room_manager' => 1,
			'use_user_manager' => 1,
			'public_room_creatable' => 1,
			'group_room_creatable' => 1,
			'use_private_room' => 1,
			'private_room_upload_total_size' => 1,
			'created_user' => 1,
			'created' => '2015-07-28 07:35:12',
			'modified_user' => 1,
			'modified' => '2015-07-28 07:35:12'
		),
	);

}
