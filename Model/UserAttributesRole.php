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
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'UserRoles.UserAttributesRole',
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
			'user_attribute_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => false,
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * UserAttributesRoleデータ取得
 *
 * @param string $roleKey 権限キー
 * @return array UserAttributesRoleデータ配列
 */
	public function getUserAttributesRole($roleKey) {
		$result = $this->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				$this->alias . '.role_key' => $roleKey
			),
			'order' => $this->alias . '.id',
		));

		if (! $result) {
			return $result;
		}

		return Hash::combine($result, '{n}.' . $this->alias . '.id', '{n}');
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
 * Save UserAttributesRoles
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveUserAttributesRoles($data) {
		$this->loadModels([
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		//トランザクションBegin
		$this->begin();

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
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
