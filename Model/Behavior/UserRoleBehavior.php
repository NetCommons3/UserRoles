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
App::uses('UserAttribute', 'UserAttributes.Model');
App::uses('DataType', 'DataTypes.Model');

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
		$model->loadModels([
			'UserRoleSetting' => 'UserRoles.UserRoleSetting',
		]);

		//UserRoleSettingデフォルトのデータ取得
		$userRoleSetting = $model->UserRoleSetting->find('first', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['origin_role_key'])
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
		$model->loadModels([
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);

		//UserAttributesRoleデフォルトのデータ取得
		$userAttributesRole = $model->UserAttributesRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['origin_role_key'])
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
		$model->loadModels([
			'PluginsRole' => 'PluginManager.PluginsRole',
		]);

		//PluginsRoleデフォルトのデータ取得
		$pluginsRole = $model->PluginsRole->find('all', array(
			'recursive' => -1,
			'conditions' => array('role_key' => $data['UserRoleSetting']['origin_role_key'])
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
		if (! $model->PluginsRole->save($pluginsRole, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		if ($pluginKey === 'user_manager') {
			$this->__updateOnlyAdministorator($model, $roleKey, true);
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
			$model->PluginsRole->alias . '.role_key' => $roleKey,
			$model->PluginsRole->alias . '.plugin_key' => $pluginKey
		);

		//PluginsRoleの削除処理
		if (! $model->PluginsRole->deleteAll($conditions, false)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		if ($pluginKey === 'user_manager') {
			$this->__updateOnlyAdministorator($model, $roleKey, false);
		}

		return true;
	}

/**
 * 管理者のみの項目に対する更新
 *
 * @param Model $model Model using this behavior
 * @param string $roleKey ロールキー
 * @param bool $enableUserManager 有効かどうか
 * @return bool True on success
 * @throws InternalErrorException
 */
	private function __updateOnlyAdministorator(Model $model, $roleKey, $enableUserManager) {
		$model->loadModels([
			'UserAttributeSetting' => 'UserAttributes.UserAttributeSetting',
			'UserAttributesRole' => 'UserRoles.UserAttributesRole',
		]);
		$userAttrSettings = $model->UserAttributeSetting->find('all', array(
			'recursive' => -1,
		));
		$UserAttributesRoles = $model->UserAttributesRole->find('all', array(
			'recursive' => -1,
			'fields' => array('id', 'role_key', 'user_attribute_key'),
			'conditions' => array('role_key' => $roleKey)
		));

		$data['UserAttributesRole'] = array();
		foreach ($userAttrSettings as $i => $userAttrSetting) {
			$userAttrRole = Hash::extract($UserAttributesRoles,
					'{n}.UserAttributesRole[user_attribute_key=' . $userAttrSetting['UserAttributeSetting']['user_attribute_key'] . ']');

			$data['UserAttributesRole'][$i] = Hash::merge(
				$model->UserAttributesRole->create(Hash::get($userAttrRole, '0')),
				$model->UserAttributesRole->defaultUserAttributeRole($userAttrSetting, $enableUserManager)
			);
		}

		if (! $model->UserAttributesRole->saveUserAttributesRoles($data)) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}

		return true;
	}

}
