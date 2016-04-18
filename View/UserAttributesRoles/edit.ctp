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

<?php echo $this->element('UserRoles.subtitle'); ?>
<?php echo $this->Wizard->navibar(UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES); ?>
<?php echo $this->MessageFlash->description(__d('user_roles', 'You can set whether or not to view the user information of others. However, if the user manger plugin can be used, because you can view and edit all of the user information, here, it can not be set.')); ?>

<?php echo $this->NetCommonsForm->create('UserAttributesRoles', array(
		'type' => 'put',
		'url' => $this->NetCommonsHtml->url(array('action' => 'edit', 'key' => $roleKey))
	)); ?>

	<?php echo $this->UserAttributeLayout->renderRow('UserAttributesRoles/render_edit_row'); ?>

	<div class="text-center">
		<?php echo $this->Wizard->buttons(
				UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES,
				array(),
				array(),
				array('ng-disabled' => '(sending || ' . ($this->data['UserRoleSetting']['is_usable_user_manager'] ? 'true' : 'false') . ')')
			); ?>
	</div>

<?php echo $this->NetCommonsForm->end();
