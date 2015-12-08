<?php
/**
 * UserRoleSetting Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppModel', 'UserRoles.Model');

/**
 * UserRoleSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserRoleSetting extends UserRolesAppModel {

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'UserRoles.UserRole',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'role_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'origin_role_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * UserRoleSettingデータ取得
 *
 * @param string $roleKey 権限キー
 * @return array UserRoleSettingデータ配列
 */
	public function getUserRoleSetting($roleKey) {
		$this->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		$userRoleSetting = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $roleKey,
			)
		));
		$userRoleSetting['UserRoleSetting']['is_usable_room_manager'] = (bool)$this->PluginsRole->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $roleKey,
				'plugin_key' => 'rooms',
			)
		));
		$userRoleSetting['UserRoleSetting']['is_usable_user_manager'] = (bool)$this->PluginsRole->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $roleKey,
				'plugin_key' => 'user_manager',
			)
		));

		return $userRoleSetting;
	}

/**
 * UserRoleSettingの登録処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRoleSetting($data) {
		$this->loadModels([
			'UserRoleSetting' => 'UserRoles.UserRoleSetting',
		]);

		//トランザクションBegin
		$this->begin();

		//UserRoleSettingのバリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			//UserRoleの登録処理
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//PluginsRoleのデータ登録処理
			if ($data['UserRoleSetting']['is_usable_room_manager']) {
				$this->savePluginsRole($data['UserRoleSetting']['role_key'], 'rooms');
			} else {
				$this->deletePluginsRole($data['UserRoleSetting']['role_key'], 'rooms');
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

/**
 * 会員管理の使用有無の登録処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUsableUserManager($data) {
		$this->loadModels([
			'UserRole' => 'UserRoles.UserRole',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		//トランザクションBegin
		$this->begin();

		try {
			//PluginsRoleのデータ登録処理
			if ($data['UserRoleSetting']['is_usable_user_manager']) {
				$this->savePluginsRole($data['UserRoleSetting']['role_key'], 'user_manager');
			} else {
				$this->deletePluginsRole($data['UserRoleSetting']['role_key'], 'user_manager');

				$result = $this->UserAttributesRole->updateAll(
					array($this->UserAttributesRole->alias . '.other_editable' => 0),
					array(
						$this->UserAttributesRole->alias . '.other_editable ' => true,
						$this->UserAttributesRole->alias . '.role_key' => $data['UserRoleSetting']['role_key'],
					)
				);
				if (! $result) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
