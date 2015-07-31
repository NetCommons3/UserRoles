<?php
/**
 * UserAttribute edit form template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Form->hidden($index . '.' . 'UserRole.id'); ?>

<?php echo $this->Form->hidden($index . '.' . 'UserRole.key'); ?>

<?php echo $this->Form->hidden($index . '.' . 'UserRole.language_id'); ?>

<?php echo $this->Form->hidden($index . '.' . 'UserRole.type'); ?>

<div class="form-group">
	<?php echo $this->Form->input($index . '.' . 'UserRole.name', array(
			'type' => 'text',
			'label' => __d('user_roles', 'User role name') . $this->element('NetCommons.required'),
			'class' => 'form-control',
		)); ?>

	<?php echo $this->element(
		'NetCommons.errors', [
			'errors' => $this->validationErrors,
			'model' => 'UserRole',
			'field' => 'name',
		]); ?>
</div>

<?php echo $this->UserRoleForm->selectBaseRoles($index . '.' . 'UserRoleSetting.default_role_key', array(
		'label' => __d('user_roles', 'Base roles'),
		//'value' => UserRole::USER_ROLE_KEY_COMMON_USER
	)); ?>
