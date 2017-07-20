<?php
/**
 * 権限管理の個人情報設定タブのテンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_roles/css/style.css');
?>

<?php
	echo $this->UserRoleForm->settingTabs(UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES);
	echo $this->element('UserRoles.subtitle');
	echo $this->MessageFlash->description(
		__d('user_roles', 'You can set whether or not to view the user information of others. ' .
				'However, if you can use the membership management menu, you can view and edit all the items except the password of all members, edit the password.')
	);
?>

<div class="alert alert-warning">
	<?php echo __d('user_roles', 'There is a possibility that if does not set properly outflow of personal information occurs.'); ?>
</div>

<?php echo $this->NetCommonsForm->create('UserAttributesRoles', array(
		'type' => 'put',
		'url' => NetCommonsUrl::actionUrlAsArray(array('action' => 'edit', 'key' => $roleKey))
	)); ?>

	<?php echo $this->UserAttributeLayout->renderRow('UserAttributesRoles/render_edit_row'); ?>

	<div class="text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				NetCommonsUrl::actionUrlAsArray(array('controller' => 'user_roles', 'action' => 'index')),
				array(),
				array('ng-disabled' => '(sending || ' . ($this->data['UserRoleSetting']['is_usable_user_manager'] ? 'true' : 'false') . ')')
			); ?>
	</div>

<?php echo $this->NetCommonsForm->end();
