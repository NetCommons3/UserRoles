<?php
/**
 * 権限管理の詳細設定テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css(array(
	'/user_roles/css/style.css',
	'/plugin_manager/css/style.css',
));
?>

<?php echo $this->element('UserRoles.subtitle'); ?>
<?php echo $this->Wizard->navibar(UserRolesAppController::WIZARD_USER_ROLE_SETTINGS); ?>
<?php echo $this->MessageFlash->description(__d('user_roles', 'Make sure your change, and press &#039;NEXT&#039;.')); ?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('UserRoleSetting'); ?>

	<div class="panel-body">
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.id'); ?>
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.role_key'); ?>
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.origin_role_key'); ?>

		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo $this->NetCommonsForm->input('UserRoleSetting.use_private_room', array(
					'type' => 'radio',
					'label' => __d('user_roles', 'Use private room?'),
					'options' => array(
						'1' => __d('user_roles', 'Use'),
						'0' => __d('user_roles', 'Not use'),
					),
					'class' => false,
					'div' => array('class' => 'form-inline'),
				)); ?>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo __d('user_roles', 'Select site-manager plugin to use'); ?>
			</div>
			<div class="panel-body plugin-checkbox-outer clearfix">
				<?php
					$hidden = '';
					foreach ($this->data['PluginsRole'] as $i => $pluginsRole) {
						echo $this->UserRoleForm->checkboxUserRole('PluginsRole.' . $i . '.PluginsRole.is_usable_plugin',
							array(
								'label' => $pluginsRole['Plugin']['name'],
								'div' => false,
								'checked' => (bool)$pluginsRole['PluginsRole']['id'],
							)
						);
						$hidden .= $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.id');
						$hidden .= $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.role_key', array(
							'value' => $roleKey)
						);
						$hidden .= $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.plugin_key', array(
							'value' => $pluginsRole['Plugin']['key'])
						);
					}

					echo $hidden;
				?>
			</div>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Wizard->buttons(UserRolesAppController::WIZARD_USER_ROLE_SETTINGS); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>

</div>
