<?php
/**
 * UserAttributesRoles edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_roles/css/style.css');
echo $this->NetCommonsHtml->css('/user_attributes/css/style.css');
?>


<?php echo $this->element('UserRoles.subtitle'); ?>

<?php echo $this->element('UserRoles.tabs'); ?>

<?php echo $this->Form->create('UserRoleSetting', array(
		'novalidate' => true,
		'url' => '/user_roles/user_attributes_roles/user_manager/'
	)); ?>

<div class="form-group form-inline">
	<?php echo $this->Form->hidden('UserRoleSetting.role_key'); ?>
	<?php echo $this->Form->hidden('UserRoleSetting.origin_role_key'); ?>

	<?php echo $this->Form->label('UserRoleSetting.is_usable_user_manager',
			h($userManagerPluginName),
			array('class' => 'user-roles-label')
		); ?>

	<?php echo $this->UserRoleForm->radioUserRole('UserRoleSetting.is_usable_user_manager',
			$this->UserRoleForm->isUsableOptions,
			array(
				'onclick' => 'submit()',
				'ng-disabled' => '(sending || ' . (int)$this->data['UserRole']['is_systemized'] . ')',
				'ng-click' => 'sending = true'
			)
		); ?>
</div>

<?php echo $this->Form->end(); ?>

<hr>

<?php echo $this->Form->create('UserAttributesRoles', array(
		'novalidate' => true,
		'url' => '/user_roles/user_attributes_roles/edit/' . h($this->data['UserRoleSetting']['role_key'])
	)); ?>

<?php echo $this->UserAttributeLayout->renderRow('UserAttributesRoles/render_edit_row'); ?>

<div class="text-center">
	<?php echo $this->Button->cancelAndSave(
			__d('net_commons', 'Cancel'),
			__d('net_commons', 'OK'),
			$this->NetCommonsHtml->url(array('action' => 'index')),
			array(),
			array('disabled' => $this->data['UserRoleSetting']['is_usable_user_manager'])
		); ?>
</div>

<?php echo $this->Form->end();
