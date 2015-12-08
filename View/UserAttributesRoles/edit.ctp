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
<?php echo $this->element('UserRoles.tabs'); ?>

<div class="form-group form-inline">
	<?php
		echo $this->NetCommonsForm->create('UserRoleSetting', array(
			'url' => $this->NetCommonsHtml->url(array('action' => 'user_manager'))
		));

		echo $this->NetCommonsForm->hidden('UserRoleSetting.role_key');
		echo $this->NetCommonsForm->hidden('UserRoleSetting.origin_role_key');

		echo $this->NetCommonsForm->label('UserRoleSetting.is_usable_user_manager',
			h($userManagerPluginName),
			array('class' => 'user-roles-label')
		);
		echo $this->UserRoleForm->radioUserRole('UserRoleSetting.is_usable_user_manager', null, array(
			'verifySystem' => true,
			'onclick' => 'submit()',
			'ng-click' => 'sending=true',
			'ng-disabled' => ($this->data['UserRole']['is_system'] ? 'true' : 'sending')
		));

		echo $this->NetCommonsForm->end();
	?>
</div>

<hr>

<?php echo $this->NetCommonsForm->create('UserAttributesRoles', array(
		'url' => $this->NetCommonsHtml->url(array('action' => 'edit', 'key' => $this->data['UserRoleSetting']['role_key']))
	)); ?>

	<?php echo $this->UserAttributeLayout->renderRow('UserAttributesRoles/render_edit_row'); ?>

	<div class="text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'OK'),
				$this->NetCommonsHtml->url(array('controller' => 'user_roles', 'action' => 'index')),
				array(),
				array(
					'ng-disabled' => '(sending || ' . ($this->data['UserRole']['is_system'] ? 'true' : 'false') . ')'
				)
			); ?>
	</div>

<?php echo $this->NetCommonsForm->end();
