<?php
/**
 * DefaultUserRole Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ModelBehavior', 'Model');

/**
 * DefaultUserRole Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model\Behavior
 */
class UserRoleBehavior extends ModelBehavior {

/**
 * Save default UserRoleSetting
 *
 * @param Model $model Model using this behavior
 * @param array $data User role data
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function saveDefaultUserRoleSetting(Model $model, $data) {
		//UserRoleSettingデフォルトのデータ取得
		$userRoleSetting = $model->UserRoleSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['default_role_key'])
		));
		if (! $userRoleSetting) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//UserRoleSettingの登録処理
		foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
			$userRoleSetting = Hash::remove($userRoleSetting, 'UserRoleSetting.' . $field);
		}
		$userRoleSetting['UserRoleSetting']['role_key'] = $data['UserRoleSetting']['role_key'];
		if (! $model->UserRoleSetting->save($userRoleSetting, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * Save default UserAttributesRole
 *
 * @param Model $model Model using this behavior
 * @param array $data User role data
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function saveDefaultUserAttributesRole(Model $model, $data) {
		//UserAttributesRoleデフォルトのデータ取得
		$userAttributesRole = $model->UserAttributesRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['default_role_key'])
		));
		if (! $userAttributesRole) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		//UserAttributesRoleの登録処理
		foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
			$userAttributesRole = Hash::remove($userAttributesRole, '{n}.UserAttributesRole.' . $field);
		}
		$userAttributesRole = Hash::insert($userAttributesRole, '{n}.UserAttributesRole.role_key', $data['UserRoleSetting']['role_key']);
		if (! $model->UserAttributesRole->saveMany($userAttributesRole, array('validate' => false))) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * Save default PluginsRole
 *
 * @param Model $model Model using this behavior
 * @param array $data User role data
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function saveDefaultPluginsRole(Model $model, $data) {
		//PluginsRoleデフォルトのデータ取得
		$pluginsRole = $model->PluginsRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['default_role_key'])
		));
		if (! $pluginsRole) {
			return true;
		}

		//PluginsRoleの登録処理
		foreach (['id', 'created', 'created_user', 'modified', 'modified_user'] as $field) {
			$pluginsRole = Hash::remove($pluginsRole, '{n}.PluginsRole.' . $field);
		}
		$pluginsRole = Hash::insert($pluginsRole, '{n}.PluginsRole.role_key', $data['UserRoleSetting']['role_key']);
		if (! $model->PluginsRole->saveMany($pluginsRole, array('validate' => false))) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * Save PluginsRole
 *
 * @param Model $model Model using this behavior
 * @param string $roleKey roles.key
 * @param string $pluginKey plugins.key
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function savePluginsRole(Model $model, $roleKey, $pluginKey) {
		$model->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		$conditions = array(
			'role_key' => $roleKey,
			'plugin_key' => $pluginKey
		);

		//PluginsRoleのデータ取得
		$pluginsRole = $model->PluginsRole->find('first', array(
			'recursive' => -1,
			'conditions' => $conditions
		));
		if (! $pluginsRole) {
			$pluginsRole = $model->PluginsRole->create($conditions);
		}

		//PluginsRoleの登録処理
		if (! $model->PluginsRole->save($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * Delete PluginsRole
 *
 * @param Model $model Model using this behavior
 * @param string $roleKey roles.key
 * @param string $pluginKey plugins.key
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function deletePluginsRole(Model $model, $roleKey, $pluginKey) {
		$model->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		$conditions = array(
			'role_key' => $roleKey,
			'plugin_key' => $pluginKey
		);

		//PluginsRoleの削除処理
		if (! $model->PluginsRole->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

/**
 * Save default UserAttributesRole
 *
 * @param Model $model Model using this behavior
 * @param array $data User role data
 * @return bool True on success
 * @throws InternalErrorException
 */
	public function saveUserAttributesRole(Model $model, $data) {
		$model->loadModels([
			'UserRole' => 'UserRoles.UserRole',
			'UserAttributes' => 'UserAttributes.UserAttribute',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole'
		]);

		$userAttributes = $model->UserAttributes->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'language_id' => Configure::read('Config.languageId')
			),
		));

		foreach ($userAttributes as $userAttribute) {
			$params = array(
				'role_key' => $data['UserRoleSetting']['role_key'],
				'default_role_key' => $data['UserRoleSetting']['default_role_key'],
				'user_attribute_key' => $userAttribute['UserAttribute']['key'],
				'only_administrator' => (bool)$userAttribute['UserAttribute']['only_administrator'],
				'is_systemized' => (bool)$userAttribute['UserAttribute']['is_systemized'],
				'is_usable_user_manager' => (bool)$data['UserRoleSetting']['is_usable_user_manager']
			);

			$userAttributeRole = $model->UserAttributesRole->defaultUserAttributeRolePermissions($params);
			if (! $model->UserAttributesRole->save($userAttributeRole, array('validate' => false))) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		return true;
	}

}
