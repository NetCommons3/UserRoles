<?php
/**
 * UserAttributesRole Model
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('UserRolesAppModel', 'UserRoles.Model');

/**
 * UserAttributesRole Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserAttributesRole extends UserRolesAppModel {

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
			'user_attribute_key' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Get UserAttributesRole data
 *
 * @param string $roleKey roles.key
 * @return array UserAttributesRole data
 */
	public function getUserAttributesRole($roleKey) {
		$conditions = array(
			$this->alias . '.role_key' => $roleKey
		);

		$options = array(
			'recursive' => -1,
			'conditions' => $conditions,
			'order' => $this->alias . '.id',
		);

		if (! $ret = $this->find('all', $options)) {
			return $ret;
		}

		$userAttributesRole = array();
		foreach ($ret as $i => $data) {
			$userAttributesRole[$data[$this->alias]['id']] = $data;
		}

		return $userAttributesRole;
	}

/**
 * Validate of UserAttributesRoles
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateUserAttributesRoles($data) {
		foreach ($data as $userAttributesRole) {
			$this->set($userAttributesRole);
			$this->validates();
			if ($this->validationErrors) {
				return false;
			}
		}
		return true;
	}

/**
 * Validate of UserAttributesRoles
 *
 * @param array $params received data
 *		array('role_key' => '', 'default_role_key' => '', 'user_attribute_key' => '', 'only_administrator' => false, 'is_systemized' => false)
 * @return bool True on success, false on validation errors
 */
	public function defaultUserAttributeRolePermissions($params) {
		$this->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
			'UserRole' => 'UserRoles.UserRole'
		]);

		if (! $default = $this->find('first', array(
			'recursive' => -1,
			'fields' => array('self_readable', 'self_editable', 'other_readable', 'other_editable'),
			'conditions' => array(
				'role_key' => $params['default_role_key'],
				'user_attribute_key' => $params['user_attribute_key'],
			),
		))) {
			switch ($params['default_role_key']) {
				case UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR:
					$default = $this->create(array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => true, 'other_editable' => true,
					));
					break;
				case UserRole::USER_ROLE_KEY_USER_ADMINISTRATOR:
					$default = $this->create(array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => true, 'other_editable' => true,
					));
					break;
				case UserRole::USER_ROLE_KEY_CHIEF_USER:
					$default = $this->create(array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => true, 'other_editable' => false,
					));
					break;
				case UserRole::USER_ROLE_KEY_COMMON_USER:
					$default = $this->create(array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => false, 'other_editable' => false,
					));
					break;
				default:
					$default = $this->create(array(
						'self_readable' => false, 'self_editable' => false,
						'other_readable' => false, 'other_editable' => false,
					));
			}
		}

		if (! $userAttributeRole = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $params['role_key'],
				'user_attribute_key' => $params['user_attribute_key'],
			),
		))) {
			$userAttributeRole = $this->create(array(
				'role_key' => $params['role_key'],
				'user_attribute_key' => $params['user_attribute_key']
			));
		}

		$setting = array();
		if ($params['is_usable_user_manager']) {
			if ($params['is_systemized']) {
				$setting = array(
					'self_readable' => true, 'self_editable' => false,
					'other_readable' => true, 'other_editable' => false,
				);
			} else {
				$setting = array(
					'self_readable' => true, 'self_editable' => true,
					'other_readable' => true, 'other_editable' => true,
				);
			}
		} elseif ($params['only_administrator']) {
			$setting = array(
				'self_readable' => false, 'self_editable' => false,
				'other_readable' => false, 'other_editable' => false,
			);
		} else {
			$setting = array();
		}

		$userAttributeRole['UserAttributesRole'] =
				array_merge($userAttributeRole['UserAttributesRole'], $default['UserAttributesRole'], $setting);

		return $userAttributeRole;
	}

/**
 * Save UserAttributesRoles
 *
 * @param array $data received post data
 * @param bool True is created, false is updated
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserAttributesRoles($data) {
		$this->loadModels([
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		//UserAttributesRoleのバリデーション
		if (! $this->validateUserAttributesRoles($data['UserAttributesRole'])) {
			return false;
		}

		try {
			//UserRoleの登録処理
			if (! $this->saveMany($data['UserAttributesRole'], array('validate' => false))) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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

}
