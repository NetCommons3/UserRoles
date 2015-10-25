<?php
/**
 * UserRoleSettings edit template
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

<?php echo $this->element('UserRoles.tabs'); ?>

<div class="panel panel-default">
	<?php echo $this->Form->create('UserRoleSetting', array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->Form->hidden('UserRoleSetting.id'); ?>

		<?php echo $this->Form->hidden('UserRoleSetting.role_key'); ?>

		<?php echo $this->Form->hidden('UserRoleSetting.origin_role_key'); ?>

		<div class="row form-group">
			<div class="col-xs-12 col-sm-6 col-md-4">
				<?php echo $this->Form->label('UserRoleSetting.is_usable_room_manager',
						h($roomsPluginName)
					); ?>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-8">
				<?php if ($this->data['UserRole']['is_systemized']) : ?>
					<?php echo $this->Form->hidden('UserRoleSetting.is_usable_room_manager', array(
						'value' => (int)$this->data['UserRoleSetting']['is_usable_room_manager']
					)); ?>

					<?php echo $this->UserRoleForm->radioUserRole(null,
							$this->UserRoleForm->isUsableOptions,
							array('disabled' => true, 'hiddenField' => false)
						); ?>
				<?php else : ?>
					<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.is_usable_room_manager',
							$this->UserRoleForm->isUsableOptions,
							array(
								//'onclick' => 'submit()',
								'ng-disabled' => '(sending || ' . (int)$this->data['UserRole']['is_systemized'] . ')',
								//'ng-click' => 'sending = true'
							)
						); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="row form-group">
			<div class="col-xs-12 col-sm-6 col-md-4">
				<?php echo $this->Form->label('UserRoleSetting.use_private_room',
						__d('user_roles', 'Use private room?')
					); ?>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-8">
				<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.use_private_room',
						$this->UserRoleForm->isUsableOptions
					); ?>
			</div>
		</div>

		<div class="row form-group">
			<div class="col-xs-12 col-sm-6 col-md-4">
				<?php echo $this->Form->label('UserRoleSetting.private_room_upload_max_size',
						__d('user_roles', 'The total size of Private Room')
					); ?>
			</div>

			<div class="col-xs-12 col-sm-4 col-md-3">
				<?php echo $this->UserRoleForm->selectMaxSize('UserRoleSetting.private_room_upload_max_size'); ?>
			</div>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>
	</div>

	<?php echo $this->Form->end(); ?>

</div>
