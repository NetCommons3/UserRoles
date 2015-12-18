<?php
/**
 * 会員権限の編集Element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php
	foreach ($this->data['UserRole'] as $index => $userRole) {
		$languageId = $userRole['language_id'];
		if (! isset($languages[$languageId])) {
			continue;
		}

		if ($activeLangId === (string)$languageId) {
			$activeCss = ' active';
		} else {
			$activeCss = '';
		}
		echo '<div id="user-roles-' . $languageId . '" class="tab-pane' . $activeCss . '">';
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.id');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.key');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.language_id');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.type');

		echo $this->NetCommonsForm->input('UserRole.' . $index . '.name', array(
			'type' => 'text',
			'label' => __d('user_roles', 'User role name') . $this->element('NetCommons.required'),
		));

		echo '</div>';
	}

	echo $this->UserRoleForm->selectOriginUserRoles('UserRoleSetting.origin_role_key', array(
			'label' => __d('user_roles', 'Origin roles'),
			'value' => $this->data['UserRoleSetting']['origin_role_key'],
			'disabled' => ($this->params['action'] === 'edit'),
			'description' => true,
		));
