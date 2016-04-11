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

echo $this->NetCommonsHtml->css('/user_roles/css/style.css');
?>

<?php echo $this->element('UserRoles.subtitle'); ?>
<?php echo $this->Wizard->outputWizard(UserRolesAppController::WIZARD_USER_ROLE_SETTINGS); ?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('UserRoleSetting'); ?>

	<div class="panel-body">
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.id'); ?>
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.role_key'); ?>
		<?php echo $this->NetCommonsForm->hidden('UserRoleSetting.origin_role_key'); ?>

		<?php foreach ($this->data['PluginsRole'] as $i => $pluginsRole) : ?>
			<div class="row form-group">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<?php echo $this->NetCommonsForm->label('PluginsRole.' . $i . '.PluginsRole.is_usable_plugin', $pluginsRole['Plugin']['name']); ?>
				</div>

				<div class="col-xs-12 col-sm-6 col-md-8">
					<?php echo $this->UserRoleForm->radioUserRole('PluginsRole.' . $i . '.PluginsRole.is_usable_plugin', true, array(
						'value' => (bool)$pluginsRole['PluginsRole']['id']
					)); ?>

					<?php echo $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.id'); ?>
					<?php echo $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.role_key', array('value' => $roleKey)); ?>
					<?php echo $this->NetCommonsForm->hidden('PluginsRole.' . $i . '.PluginsRole.plugin_key', array('value' => $pluginsRole['Plugin']['key'])); ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="row form-group">
			<div class="col-xs-12 col-sm-6 col-md-4">
				<?php echo $this->NetCommonsForm->label('UserRoleSetting.use_private_room',
						__d('user_roles', 'Use private room?')
					); ?>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-8">
				<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.use_private_room', false); ?>
			</div>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('controller' => 'user_roles', 'action' => 'index'))
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>

</div>
