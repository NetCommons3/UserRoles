<?php
/**
 * Insert records migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Insert records migration
 *
 * @package NetCommons\UserRoles\Config\Migration
 */
class Records extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * Insert records
 *
 * @var array $migration
 */
	public $records = array(
		'UserRoleSetting' => array(
			array(
				'role_key' => 'system_administrator', 'origin_role_key' => 'system_administrator',
				'use_private_room' => '1', 'is_site_plugins' => '1',
			),
			array(
				'role_key' => 'administrator', 'origin_role_key' => 'administrator',
				'use_private_room' => '1', 'is_site_plugins' => '1',
			),
			array(
				'role_key' => 'common_user', 'origin_role_key' => 'common_user',
				'use_private_room' => '1', 'is_site_plugins' => '0',
			),
		),
		'UserAttributesRole' => array(
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'avatar',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'username',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'password',
				'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'handlename',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'name',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'email',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'moblie_mail',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'sex',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'timezone',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'role_key',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'status',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'created',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'created_user',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'modified',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'modified_user',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'password_modified',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'last_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'previous_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'profile',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'system_administrator', 'user_attribute_key' => 'search_keywords',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'avatar',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'username',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'password',
				'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'handlename',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'name',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'email',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'moblie_mail',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'sex',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'timezone',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'role_key',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'status',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'created',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'created_user',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'modified',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'modified_user',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'password_modified',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'last_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'previous_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'profile',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'administrator', 'user_attribute_key' => 'search_keywords',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '1', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'avatar',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'username',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'password',
				'self_readable' => '0', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'handlename',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'name',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'email',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'moblie_mail',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'sex',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'timezone',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'role_key',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'status',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'created',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'created_user',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'modified',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'modified_user',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'password_modified',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'last_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'previous_login',
				'self_readable' => '1', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'profile',
				'self_readable' => '1', 'self_editable' => '1', 'other_readable' => '1', 'other_editable' => '0', ),
			array(
				'role_key' => 'common_user', 'user_attribute_key' => 'search_keywords',
				'self_readable' => '0', 'self_editable' => '0', 'other_readable' => '0', 'other_editable' => '0', ),
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
		if ($direction === 'down') {
			return true;
		}
		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
