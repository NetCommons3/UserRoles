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

		echo '<div class="form-group" ng-show="activeLangId === \'' . (string)$languageId . '\'" ng-cloak>';
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.id');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.key');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.language_id');
		echo $this->NetCommonsForm->hidden('UserRole.' . $index . '.type');

		echo $this->NetCommonsForm->input('UserRole.' . $index . '.name', array(
			'type' => 'text',
			'label' => $this->SwitchLanguage->inputLabel(__d('user_roles', 'User role name'), $languageId),
			'required' => true,
			'error' => array(
				'ng-show' => 'activeLangId === \'' . (string)$languageId . '\'',
			),
		));

		echo '</div>';

		echo $this->NetCommonsForm->input('UserRole.' . $index . '.description', array(
			'type' => 'textarea',
			'label' => $this->SwitchLanguage->inputLabel(__d('user_roles', 'User role description'), $languageId),
			'required' => true,
			'rows' => '3',
			'div' => array(
				'class' => 'form-group',
				'ng-show' => 'activeLangId === \'' . (string)$languageId . '\'',
				'ng-cloak' => ' '
			),
		));
	}
?>

<div class="form-group row">
	<div class="col-xs-11 col-xs-offset-1"
		 ng-init="<?php echo $this->UserRoleForm->domId('UserRoleSetting.origin_role_key') . '=\'' .
				 $this->data['UserRoleSetting']['origin_role_key'] . '\';'; ?>">
		<?php
			echo $this->UserRoleForm->selectOriginUserRoles('UserRoleSetting.origin_role_key', array(
					'label' => __d('user_roles', 'Origin roles'),
					//'value' => $this->data['UserRoleSetting']['origin_role_key'],
					'disabled' => ($this->params['action'] === 'edit'),
					'help' => '<div class="alert alert-warning">' . __d('user_roles', 'Role description') . '</div>',
				));
		?>

		<?php echo $this->UserRoleForm->displayUserRoleDescriptions('UserRoleSetting.origin_role_key'); ?>
	</div>
</div>
