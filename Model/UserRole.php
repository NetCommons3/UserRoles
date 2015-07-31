<?php
/**
 * UserRole Model
 *
 * @property Language $Language
 * @property Plugin $Plugin
 * @property Role $Role
 * @property UserRole $UserRole
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('Role', 'Roles.Model');

/**
 * UserRole Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model
 */
class UserRole extends Role {

/**
 * Table name
 *
 * @var string
 */
	public $useTable = 'roles';

/**
 * UserRole keys
 *
 * @var const
 */
	const
		USER_ROLE_KEY_SYSTEM_ADMINISTRATOR = 'system_administrator',
		USER_ROLE_KEY_USER_ADMINISTRATOR = 'user_administrator',
		USER_ROLE_KEY_CHIEF_USER = 'chief_user',
		USER_ROLE_KEY_COMMON_USER = 'common_user',
		USER_ROLE_KEY_GUEST_USER = 'guest_user';

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
	);

/**
 * Validation rules
 *
 * @var array
 */
	//public $validate = array(
	//	'language_id' => array(
	//		'numeric' => array(
	//			'rule' => array('numeric'),
	//			//'message' => 'Your custom message here',
	//			//'allowEmpty' => false,
	//			//'required' => false,
	//			//'last' => false, // Stop validation after this rule
	//			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	//		),
	//	),
	//	'type' => array(
	//		'numeric' => array(
	//			'rule' => array('numeric'),
	//			//'message' => 'Your custom message here',
	//			//'allowEmpty' => false,
	//			//'required' => false,
	//			//'last' => false, // Stop validation after this rule
	//			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	//		),
	//	),
	//	'name' => array(
	//		'notEmpty' => array(
	//			'rule' => array('notEmpty'),
	//			//'message' => 'Your custom message here',
	//			//'allowEmpty' => false,
	//			//'required' => false,
	//			//'last' => false, // Stop validation after this rule
	//			//'on' => 'create', // Limit validation to 'create' or 'update' operations
	//		),
	//	),
	//);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Language' => array(
			'className' => 'M17n.Language',
			'foreignKey' => 'language_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'UserRoleSetting' => array(
			'className' => 'UserRoles.UserRoleSetting',
			'foreignKey' => false,
			'conditions' => 'UserRoleSetting.role_key = UserRole.key',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		//'Plugin' => array(
		//	'className' => 'Plugin',
		//	'joinTable' => 'plugins_roles',
		//	'foreignKey' => 'role_id',
		//	'associationForeignKey' => 'plugin_id',
		//	'unique' => 'keepExisting',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => '',
		//	'limit' => '',
		//	'offset' => '',
		//	'finderQuery' => '',
		//),
		//'Room' => array(
		//	'className' => 'Rooms.Room',
		//	'joinTable' => 'roles_rooms',
		//	'foreignKey' => 'role_id',
		//	'associationForeignKey' => 'room_id',
		//	'unique' => 'keepExisting',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => '',
		//	'limit' => '',
		//	'offset' => '',
		//	'finderQuery' => '',
		//),
		//'UserRole' => array(
		//	'className' => 'UserRole',
		//	'joinTable' => 'roles_user_attributes',
		//	'foreignKey' => 'role_id',
		//	'associationForeignKey' => 'user_attribute_id',
		//	'unique' => 'keepExisting',
		//	'conditions' => '',
		//	'fields' => '',
		//	'order' => '',
		//	'limit' => '',
		//	'offset' => '',
		//	'finderQuery' => '',
		//)
	);

/**
 * Get UserRoles data
 *
 * @param string $roleKey roles.key
 * @return array UserRole data
 */
	public function getUserRoles($type = 'all', $options = array()) {
		$conditions = array(
			$this->alias . '.type' => self::ROLE_TYPE_USER
		);

		$options = Hash::merge(array(
			'recursive' => -1,
			'conditions' => $conditions,
			'order' => $this->alias . '.id',
		), $options);

		if (! $roles = $this->find($type, $options)) {
			return $roles;
		}

		return $roles;
	}

/**
 * Save UserRoles
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRole($data) {
		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		//バリデーション
		foreach ($data as $userRole) {
			if (! $this->validateUserRole($userRole)) {
				return false;
			}
		}

		try {
			//登録処理
			$userRoles = array();
			foreach ($data as $i => $userRole) {
				if (! $userRoles[$i] = $this->save($userRole, false, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}

		return $userRoles;
	}

/**
 * validate of UserRole
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateUserRole($data) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}
		return true;
	}

/**
 * Delete UserRole
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteUserRole($data) {
		$this->loadModels([
			'UserRoles' => 'UserRoles.UserRole',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//削除処理
			if (! $this->deleteAll(array($this->alias . '.key' => $data['UserRole']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$dataSource->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			//エラー出力
			CakeLog::error($ex);
			throw $ex;
		}

		return true;
	}

}
