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
 * システム管理者権限の定数
 *
 * @var const
 */
	const USER_ROLE_KEY_SYSTEM_ADMINISTRATOR = 'system_administrator';

/**
 * 会員管理者権限の定数
 *
 * @var const
 */
	const USER_ROLE_KEY_ADMINISTRATOR = 'administrator';

/**
 * 会員一般権限の定数
 *
 * @var const
 */
	const USER_ROLE_KEY_COMMON_USER = 'common_user';

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'NetCommons.OriginalKey',
		'UserRoles.UserRole',
	);

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
	public $hasAndBelongsToMany = array();

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
			'language_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'type' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
				'inList' => array(
					'rule' => array('inList', array(parent::ROLE_TYPE_USER)),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('user_roles', 'User role name')),
					'required' => true
				),
			),
			'is_system' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),

			//key to set in OriginalKeyBehavior.
		));

		return parent::beforeValidate($options);
	}

/**
 * Called before each find operation. Return false if you want to halt the find
 * call, otherwise return the (modified) query data.
 *
 * @param array $query Data used to execute this query, i.e. conditions, order, etc.
 * @return mixed true if the operation should continue, false if it should abort; or, modified
 *  $query to continue with new $query
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforefind
 */
	public function beforeFind($query) {
		$query['conditions']['UserRole.type'] = UserRole::ROLE_TYPE_USER;
		return $query;
	}

/**
 * 会員権限の登録
 *
 * @param array $data received post data
 * @param bool $created True is created, false is updated
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRole($data, $created) {
		$this->loadModels([
			'UserRoleSetting' => 'UserRoles.UserRoleSetting',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		//トランザクションBegin
		$this->begin();

		//UserRoleのバリデーション
		$roleKey = $data['UserRole'][0]['key'];
		if (! $this->validateMany($data['UserRole'])) {
			return false;
		}

		try {
			//UserRoleの登録処理
			$userRoles = array();
			foreach ($data['UserRole'] as $i => $userRole) {
				$userRole['UserRole']['key'] = $roleKey;
				if (! $userRoles[$i] = $this->save($userRole, false, false)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
				$roleKey = $userRoles[$i]['UserRole']['key'];
			}
			if ($created) {
				$data['UserRoleSetting']['role_key'] = $roleKey;

				//UserRoleSettingのデフォルトデータ登録処理
				$this->saveDefaultUserRoleSetting($data);

				//UserAttributesRoleのデフォルトデータ登録処理
				$this->saveDefaultUserAttributesRole($data);

				//PluginsRoleのデフォルトデータ登録処理
				$this->saveDefaultPluginsRole($data);
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return $userRoles;
	}

/**
 * 会員権限の削除
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteUserRole($data) {
		$this->loadModels([
			'UserRoleSetting' => 'UserRoles.UserRoleSetting',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		//トランザクションBegin
		$this->begin();
		if (! $this->verifyDeletable($data['key'])) {
			return false;
		}

		try {
			//削除処理
			if (! $this->deleteAll(array($this->alias . '.key' => $data['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			if (! $this->UserRoleSetting->deleteAll(array($this->UserRoleSetting->alias . '.role_key' => $data['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			if (! $this->UserAttributesRole->deleteAll(array($this->UserAttributesRole->alias . '.role_key' => $data['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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
 * 削除可能か検証する
 *
 * @param array $roleKey received post data
 * @return bool True：削除可、False：削除不可
 */
	public function verifyDeletable($roleKey) {
		$this->loadModels([
			'User' => 'Users.User',
		]);

		$userRole = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array('key' => $roleKey),
		));

		//システムフラグがONになっているものは、削除不可
		if (Hash::get($userRole, $this->alias . '.is_system')) {
			return false;
		}

		$count = $this->User->find('count', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey),
		));
		if ($count === false) {
			return false;
		}
		if ($count > 0) {
			$this->validationErrors['key'][] = __d('user_roles', 'Can not be deleted because it has this authority is used.');
			return false;
		}

		return true;
	}

}
