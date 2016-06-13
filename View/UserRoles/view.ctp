<?php
/**
 * 権限管理の会員権限の追加・編集テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('title_for_modal'); ?>
<?php echo Current::read('Plugin.name'); ?>
 -
<?php echo h($subtitle); ?>
<?php $this->end(); ?>

<ul class="nav nav-tabs" role="tablist">
	<li class="active">
		<?php
			$key = UserRolesAppController::WIZARD_USER_ROLES;
			$label = __d('user_roles', 'General setting');
			echo $this->NetCommonsHtml->link(
				$label, '#' . $key, ['aria-controls' => $key, 'role' => 'tab', 'data-toggle' => 'tab']
			);
		?>
	</li>

	<?php if (Hash::get($this->request->data, 'UserRoleSetting.is_site_plugins')) : ?>
		<li>
			<?php
				$key = UserRolesAppController::WIZARD_USER_ROLES_PLUGINS;
				$label = __d('user_roles', 'Select site-manager plugin to use');
				echo $this->NetCommonsHtml->link(
					$label, '#' . $key, ['aria-controls' => $key, 'role' => 'tab', 'data-toggle' => 'tab']
				);
			?>
		</li>
	<?php endif; ?>

	<li>
		<?php
			$key = UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES;
			$label = __d('user_roles', 'Information Policy');
			echo $this->NetCommonsHtml->link(
				$label, '#' . $key, ['aria-controls' => $key, 'role' => 'tab', 'data-toggle' => 'tab']
			);
		?>
	</li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="<?php echo UserRolesAppController::WIZARD_USER_ROLES; ?>">
		<div class="text-right nc-edit-link">
			<?php echo $this->Button->editLink(__d('net_commons', 'Edit'),
					array('controller' => 'user_roles', 'action' => 'edit', 'key' => $roleKey),
					array('iconSize' => ' btn-xs')
				); ?>
		</div>
		<?php echo $this->element('UserRoles/view_user_role'); ?>
	</div>

	<?php if (Hash::get($this->request->data, 'UserRoleSetting.is_site_plugins')) : ?>
		<div class="tab-pane" id="<?php echo UserRolesAppController::WIZARD_USER_ROLES_PLUGINS; ?>">
			<?php if (! in_array($roleKey, UserRole::$systemRoles, true)) : ?>
				<div class="text-right nc-edit-link">
					<?php echo $this->Button->editLink(__d('net_commons', 'Edit'),
							array('controller' => 'user_roles_plugins', 'action' => 'edit', 'key' => $roleKey),
							array('iconSize' => ' btn-xs')
						); ?>
				</div>
			<?php endif; ?>

			<?php echo $this->element('UserRoles/view_user_roles_plugins'); ?>
		</div>
	<?php endif; ?>

	<div class="tab-pane" id="<?php echo UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES; ?>">
		<?php if (! in_array($roleKey, UserRole::$systemRoles, true) &&
				! Hash::get($this->request->data, 'UserRoleSetting.is_usable_user_manager')) : ?>
			<div class="text-right nc-edit-link">
				<?php echo $this->Button->editLink(__d('net_commons', 'Edit'),
						array('controller' => 'user_attributes_roles', 'action' => 'edit', 'key' => $roleKey),
						array('iconSize' => ' btn-xs')
					); ?>
			</div>
		<?php endif; ?>

		<?php echo $this->UserAttributeLayout->renderRow('UserRoles/render_view_row'); ?>
	</div>
</div>
