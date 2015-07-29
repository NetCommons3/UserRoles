<?php
/**
 * UserRole Model
 *
 * @property Language $Language
 * @property Plugin $Plugin
 * @property Role $Role
 * @property UserAttribute $UserAttribute
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
 * Table name
 *
 * @var string
 */
	public $alias = 'Role';

/**
 * UserRole keys
 *
 * @var const
 */
	const
		USER_ROLE_KEY_SYSTEM_ADMINISTRATOR = 'system_administrator',
		USER_ROLE_KEY_USER_ADMINISTRATOR = 'user_administrator',
		USER_ROLE_KEY_CHIEF_USER = 'chief_user',
		USER_ROLE_KEY_GENERAL_USER = 'general_user',
		USER_ROLE_KEY_GUEST_USER = 'guest_user';

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
		//'UserAttribute' => array(
		//	'className' => 'UserAttribute',
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
 * @return array Role data
 */
	public function getUserRoles($type = 'all', $roleKey = null, $options = array()) {
		$conditions = array(
			$this->alias . '.type' => self::ROLE_TYPE_USER
		);
		if (isset($roleKey)) {
			$conditions[$this->alias . '.key'] = $roleKey;
		}

		$options = Hash::merge(array(
			'recursive' => -1,
			'conditions' => $conditions,
			'order' => $this->alias . '.id',
		), $options);


		if (! $roles = $this->find($type, $options)) {
			return $roles;
		}

		if (isset($roleKey)) {
			return $roles[0];
		} else {
			return $roles;
		}
	}

}
