<?php
/**
 * ゲスト権限レコード追加 migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * ゲスト権限レコード追加 migration
 *
 * @package NetCommons\UserRoles\Config\Migration
 * @see https://github.com/NetCommons3/NetCommons3/issues/826
 */
class GuestRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'guest_records';

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
		'Role' => array(
			array(
				'language_id' => '2', 'key' => 'guest_user', 'type' => '1', 'name' => 'ゲスト', 'is_system' => '0',
				'description' => 'プライベートルームおよびグループが作成できない会員。他人に対して、必要最低限の情報のみ閲覧できます。 ',
			),
			array(
				'language_id' => '1', 'key' => 'guest_user', 'type' => '1', 'name' => 'Guest user', 'is_system' => '0',
				'description' => 'A guest user',
			),
		),
		'UserRoleSetting' => array(
			array(
				'role_key' => 'guest_user', 'origin_role_key' => 'common_user',
				'use_private_room' => '0', 'is_site_plugins' => '0',
			),
		),
		'UserAttributesRole' => array(
			//common_userのデータをコピーする
		),
		'DefaultRolePermission' => array(
			array(
				'role_key' => 'guest_user', 'type' => 'user_role', 'permission' => 'group_creatable', 'value' => '0', 'fixed' => '0',
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
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin'
		]);
		if (! $this->Plugin->runMigration('Roles', $this->connection)) {
			return false;
		}
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
			if ($model === 'UserAttributesRole') {
				$objModel = $this->generateModel($model);
				$result = $objModel->find('all', [
					'recursive' => -1,
					'conditions' => [
						'role_key' => 'common_user'
					],
				]);
				$records = Hash::combine($result, '{n}.UserAttributesRole.id', '{n}.UserAttributesRole');
				foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
					$records = Hash::remove($records, '{n}.' . $field);
				}
				$records = Hash::insert($records, '{n}.role_key', 'guest_user');
			}

			if (! $this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
