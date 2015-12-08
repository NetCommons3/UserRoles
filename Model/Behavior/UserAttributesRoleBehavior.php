<?php
/**
 * UserAttributesRole Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ModelBehavior', 'Model');

/**
 * UserAttributesRole Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model\Behavior
 */
class UserAttributesRoleBehavior extends ModelBehavior {

/**
 * UserAttributesRoleのデフォルト値
 *
 * @param Model $model Model using this behavior
 * @param array $params received data
 *		array('role_key' => '', 'default_role_key' => '', 'user_attribute_key' => '', 'only_administrator' => false, 'is_system' => false)
 * @return bool True on success, false on validation errors
 */
	public function defaultUserAttributeRolePermissions(Model $model, $params) {
		$model->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
			'UserRole' => 'UserRoles.UserRole'
		]);

		$default = $this->__getDefaultUserAttributeRolePermission($model, $params);

		$userAttributeRole = $model->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'role_key' => $params['role_key'],
				'user_attribute_key' => $params['user_attribute_key'],
			),
		));
		if (! $userAttributeRole) {
			$userAttributeRole = $model->create(array(
				'role_key' => $params['role_key'],
				'user_attribute_key' => $params['user_attribute_key']
			));
		}

		$setting = array();
		if ($params['is_usable_user_manager']) {
			if ($params['is_system']) {
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
		}

		$userAttributeRole['UserAttributesRole'] =
				Hash::merge($userAttributeRole['UserAttributesRole'], $default['UserAttributesRole'], $setting);

		return $userAttributeRole;
	}

/**
 * UserAttributesRoleのデフォルト値を取得
 *
 * @param Model $model Model using this behavior
 * @param array $params received data
 *		array('role_key' => '', 'default_role_key' => '', 'user_attribute_key' => '', 'only_administrator' => false, 'is_system' => false)
 * @return mixed default user attribute role permission
 */
	private function __getDefaultUserAttributeRolePermission(Model $model, $params) {
		$default = $model->find('first', array(
			'recursive' => -1,
			'fields' => array('self_readable', 'self_editable', 'other_readable', 'other_editable'),
			'conditions' => array(
				'role_key' => $params['origin_role_key'],
				'user_attribute_key' => $params['user_attribute_key'],
			),
		));
		if (! $default) {
			switch ($params['origin_role_key']) {
				case UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR:
					$default[$model->alias] = array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => true, 'other_editable' => true,
					);
					break;
				case UserRole::USER_ROLE_KEY_ADMINISTRATOR:
					$default[$model->alias] = array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => true, 'other_editable' => true,
					);
					break;
				case UserRole::USER_ROLE_KEY_COMMON_USER:
					$default[$model->alias] = array(
						'self_readable' => true, 'self_editable' => true,
						'other_readable' => false, 'other_editable' => false,
					);
					break;
				default:
					$default[$model->alias] = array(
						'self_readable' => false, 'self_editable' => false,
						'other_readable' => false, 'other_editable' => false,
					);
			}
		}

		return $default;
	}

}
