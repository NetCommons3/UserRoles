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
			'default_role_key' => array(
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
 * Save UserRoles
 *
 * @param array $data received post data
 * @param bool True is created, false is updated
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRoleSetting($data) {
		$this->loadModels([
			'UserRoleSetting' => 'UserRoles.UserRoleSetting',
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		//UserRoleSettingのバリデーション
		if (! $this->validateUserRoleSetting($data)) {
			return false;
		}

		try {
			//UserRoleの登録処理
			if (! $this->save($data, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			//PluginsRoleのデータ登録処理
			if ($data['UserRoleSetting']['is_usable_room_manager']) {
				$this->savePluginsRole($data['UserRoleSetting']['role_key'], 'rooms');
			} else {
				$this->deletePluginsRole($data['UserRoleSetting']['role_key'], 'rooms');
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}

		return true;
	}

/**
 * Validate of UserRoleSetting
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateUserRoleSetting($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}
		return true;
	}

}
