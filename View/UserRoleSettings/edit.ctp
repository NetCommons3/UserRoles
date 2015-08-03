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

echo $this->Html->css(
	array(
		'/user_roles/css/style.css'
	),
	array('plugin' => false)
);
?>

<?php echo $this->element('UserRoles.tabs'); ?>

<div class="panel panel-default">
	<?php echo $this->Form->create(null, array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->Form->hidden('UserRoleSetting.id'); ?>

		<?php echo $this->Form->hidden('UserRoleSetting.role_key'); ?>

		<?php echo $this->Form->hidden('UserRoleSetting.default_role_key'); ?>

		<div class="form-group form-inline">
			<?php echo $this->UserRoleForm->selectDefaultRoomRoles('UserRoleSetting.default_room_role_key', array(
					'label' => array(
						'label' => __d('user_roles', 'Default room role'),
						'class' => 'user-roles-label'
					),
				)); ?>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo sprintf(__d('user_roles', '%s plugin setting'), h($roomsPluginName)); ?>
			</div>
			<div class="panel-body">
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<?php echo $this->Form->label('UserRoleSetting.is_usable_room_manager',
								h($roomsPluginName)
							); ?>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-8">
						<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.is_usable_room_manager',
								$this->UserRoleForm->isUsableOptions
							); ?>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<?php echo $this->Form->label('UserRoleSetting.public_room_creatable',
								__d('user_roles', 'Allow to create room in Public Space.')
							); ?>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-8">
						<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.public_room_creatable',
								$this->UserRoleForm->isPermittedOptions
							); ?>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<?php echo $this->Form->label('UserRoleSetting.group_room_creatable',
								__d('user_roles', 'Allow to create room in Grouproom Space.')
							); ?>
					</div>

					<div class="col-xs-12 col-sm-6 col-md-8">
						<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.group_room_creatable',
								$this->UserRoleForm->isPermittedOptions
							); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo __d('user_roles', 'Private room setting'); ?>
			</div>
			<div class="panel-body">
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
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->element('UserRoles.edit_btn'); ?>
	</div>

	<?php echo $this->Form->end(); ?>

</div>
