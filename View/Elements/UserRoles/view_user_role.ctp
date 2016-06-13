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

<div class="panel panel-default">
	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-roles-'); ?>

		<div class="tab-content">
			<?php
				foreach ($this->data['UserRole'] as $index => $userRole) {
					$languageId = $userRole['language_id'];
					if (! isset($languages[$languageId])) {
						continue;
					}

					echo $this->NetCommonsForm->input('UserRole.' . $index . '.name', array(
						'type' => 'label',
						'label' => $this->SwitchLanguage->inputLabel(__d('user_roles', 'User role name'), $languageId),
						'div' => array(
							'class' => 'form-group',
							'ng-show' => 'activeLangId === \'' . (string)$languageId . '\'',
							'ng-cloak' => ' '
						)
					));

					echo $this->NetCommonsForm->input('UserRole.' . $index . '.description', array(
						'type' => 'label',
						'label' => $this->SwitchLanguage->inputLabel(__d('user_roles', 'User role description'), $languageId),
						'div' => array(
							'class' => 'form-group',
							'ng-show' => 'activeLangId === \'' . (string)$languageId . '\'',
							'ng-cloak' => ' '
						),
					));
				}
			?>

			<div class="form-group">
				<?php
					echo $this->NetCommonsForm->checkbox('UserRoleSetting.use_private_room', array(
						'label' => __d('user_roles', 'Use private room?'),
						'disabled' => true
					));
				?>
			</div>

			<div class="form-group">
				<?php
					echo $this->NetCommonsForm->checkbox('DefaultRolePermission.group_creatable.value', array(
						'label' => __d('user_roles', 'Have authority of create group?'),
						'disabled' => true
					));
				?>
			</div>

			<?php
				echo $this->UserRoleForm->selectOriginUserRoles('UserRoleSetting.origin_role_key', array(
					'label' => __d('user_roles', 'Origin role'),
					'disabled' => true,
					'userRoleKey' => $roleKey
				));
			?>
		</div>
	</div>
</div>