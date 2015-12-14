<?php
/**
 * Migration file
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Migration file
 *
 * * プライベートルームの容量(private_room_upload_max_size)フィールドの削除
 *
 * @package NetCommons\UserRoles\Config\Migration
 * @link https://github.com/NetCommons3/UserRoles/issues/13
 */
class DropPrivateRoomUploadMaxSize extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'drop_private_room_upload_max_size';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'user_role_settings' => array('private_room_upload_max_size'),
			),
		),
		'down' => array(
			'create_field' => array(
				'user_role_settings' => array(
					'private_room_upload_max_size' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プライベートの総容量'),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
