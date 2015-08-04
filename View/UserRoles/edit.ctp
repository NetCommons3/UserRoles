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
		<?php echo $this->SwitchLanguage->tablist('user-roles-'); ?>

		<div class="tab-content">
			<?php foreach ($this->data as $index => $userRole) : ?>
				<?php $languageId = $userRole['UserRole']['language_id']; ?>

				<?php if (isset($languages[$languageId])) : ?>
					<div id="user-roles-<?php echo $languageId; ?>"
							class="tab-pane<?php echo ($activeLangId === (string)$languageId ? ' active' : ''); ?>">

						<?php echo $this->element('UserRoles/edit_form', array('index' => $index)); ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->element('UserRoles.edit_btn'); ?>
	</div>

	<?php echo $this->Form->end(); ?>
</div>

<?php if ($this->request->params['action'] === 'edit' && ! $isSystemized) : ?>
	<?php echo $this->element('UserRoles/delete_form'); ?>
<?php endif;
