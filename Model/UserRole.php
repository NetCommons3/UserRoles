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
 * システム権限
 *
 * @var const
 */
	public static $systemRoles = array(
		self::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR,
		self::USER_ROLE_KEY_ADMINISTRATOR
	);

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
					'required' => true
				),
			),
			'type' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true
				),
				'inList' => array(
					'rule' => array('inList', array(parent::ROLE_TYPE_USER)),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('user_roles', 'User role name')
					),
					'required' => true
				),
			),
			'description' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(
						__d('net_commons', 'Please input %s.'), __d('user_roles', 'User role description')
					),
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
 * 会員権限のバリデーション
 *
 * @param array $data リクエストデータ
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function validateUserRole($data) {
		//UserRoleのバリデーション
		//　※$data['UserRole'][0]['key']という形からvalidateMany()を通すことで
		//　　$data['UserRole'][0]['UserRole']['key']となる
		return $this->validateMany($data['UserRole']);
	}

/**
 * 会員権限の登録
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRole($data) {
		//トランザクションBegin
		$this->begin();

		//UserRoleのバリデーション
		//　※$data['UserRole'][0]['key']という形からvalidateMany()を通すことで
		//　　$data['UserRole'][0]['UserRole']['key']となる
		$roleKey = $data['UserRole'][0]['key'];
		if (! $this->validateMany($data['UserRole'])) {
			return false;
		}
		$created = !(bool)$roleKey;

		try {
			//UserRoleの登録処理
			$userRoles = array();
			foreach ($data['UserRole'] as $i => $userRole) {
				$userRole['UserRole']['key'] = $roleKey;
				$userRoles[$i] = $this->save($userRole, false, false);
				if (! $userRoles[$i]) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
				$roleKey = $userRoles[$i]['UserRole']['key'];
			}

			if ($created) {
				$this->__saveCreatedUserRole($roleKey, $data);
			} else {
				if (! $this->UserRoleSetting->saveUserRoleSetting($data)) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error 1'));
				}
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
 * 会員権限の登録
 *
 * @param string $roleKey ロールキー
 * @param array $data リクエストデータ
 * @return bool
 * @throws InternalErrorException
 */
	private function __saveCreatedUserRole($roleKey, $data) {
		$this->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		$original = $this->UserRoleSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $data['UserRoleSetting']['origin_role_key']
			)
		));

		//UserRoleSettingの登録処理
		$data['UserRoleSetting']['role_key'] = $roleKey;
		$data['UserRoleSetting']['is_site_plugins'] = $original['UserRoleSetting']['is_site_plugins'];
		if (isset($data['DefaultRolePermission'])) {
			$data['DefaultRolePermission'] = Hash::insert(
				$data['DefaultRolePermission'], '{s}.role_key', $roleKey
			);
		}

		if (! $this->UserRoleSetting->saveUserRoleSetting($data)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//PluginsRoleのデータ登録処理
		if (isset($data['PluginsRole'])) {
			$data['PluginsRole'] = Hash::remove(
				$data['PluginsRole'], '{n}.PluginsRole[is_usable_plugin=0]'
			);
			$data['PluginsRole'] = Hash::insert(
				$data['PluginsRole'], '{n}.PluginsRole.id', null
			);
			$data['PluginsRole'] = Hash::insert(
				$data['PluginsRole'], '{n}.PluginsRole.role_key', $roleKey
			);
			if (! $this->PluginsRole->saveMany($data['PluginsRole'])) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		//UserAttributesRoleのデータ登録処理
		$data['UserAttributesRole'] = Hash::insert(
			$data['UserAttributesRole'], '{n}.UserAttributesRole.id', null
		);
		$data['UserAttributesRole'] = Hash::insert(
			$data['UserAttributesRole'], '{n}.UserAttributesRole.role_key', $roleKey
		);
		$result = $this->UserAttributesRole->saveUserAttributesRoles($data);
		if (! $result) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * UserRoleSettingの登録処理
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserRolePlugins($data) {
		//トランザクションBegin
		$this->begin();

		try {
			//PluginsRoleのデータ登録処理
			foreach ($data['PluginsRole'] as $pluginRole) {
				if (Hash::get($pluginRole, 'PluginsRole.is_usable_plugin', false)) {
					$this->savePluginsRole(
						$pluginRole['PluginsRole']['role_key'], $pluginRole['PluginsRole']['plugin_key']
					);
				} else {
					$this->deletePluginsRole(
						$pluginRole['PluginsRole']['role_key'], $pluginRole['PluginsRole']['plugin_key']
					);
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

		try {
			if (! $this->verifyDeletable($data['key'])) {
				return false;
			}
			//削除処理
			if (! $this->deleteAll(array($this->alias . '.key' => $data['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array($this->UserRoleSetting->alias . '.role_key' => $data['key']);
			if (! $this->UserRoleSetting->deleteAll($conditions, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$conditions = array($this->UserAttributesRole->alias . '.role_key' => $data['key']);
			if (! $this->UserAttributesRole->deleteAll($conditions, false)) {
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
 * @throws BadRequestException
 * @throws InternalErrorException
 */
	public function verifyDeletable($roleKey) {
		$this->loadModels([
			'User' => 'Users.User',
		]);

		$userRole = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array('key' => $roleKey),
		));
		if (! $userRole) {
			//リクエストのエラーなのでBadRequestにする
			throw new BadRequestException(__d('net_commons', 'Bad Request'));
		}

		//システムフラグがONになっているものは、削除不可
		if (Hash::get($userRole, $this->alias . '.is_system')) {
			return false;
		}

		$count = $this->User->find('count', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $roleKey),
		));
		if ($count === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		if ($count > 0) {
			$this->validationErrors['key'][] =
					__d('user_roles', 'Can not be deleted because it has this authority is used.');
			return false;
		}

		return true;
	}

}
