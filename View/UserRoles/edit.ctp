<?php
/**
 * UserRoles edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Html->script(
	array(
		'/user_roles/js/user_roles.js'
	),
	array('plugin' => false)
);
?>

<?php echo $this->element('UserRoles.tabs'); ?>

<div class="panel panel-default">
	<?php echo $this->Form->create(null, array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-roles-'); ?>

		<div class="tab-content">
			<?php foreach ($this->data as $index => $userRole) : ?>
				<?php if (isset($languages[$userRole['UserRole']['language_id']])) : ?>
					<div id="user-roles-<?php echo $userRole['UserRole']['language_id']; ?>"
							class="tab-pane<?php echo ($activeLangId === (string)$userRole['UserRole']['language_id'] ? ' active' : ''); ?>">

						<?php echo $this->element('UserRoles/edit_form', array(
								'index' => $index,
							)); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<?php echo $this->element('UserRoles.edit_btn'); ?>

	<?php echo $this->Form->end(); ?>
</div>

<?php if ($this->request->params['action'] === 'edit' && ! $isSystemized) : ?>
	<?php echo $this->element('UserRoles/delete_form'); ?>
<?php endif;
