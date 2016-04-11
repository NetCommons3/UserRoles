<?php
/**
 * 権限管理の一覧表示テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->MessageFlash->description(__d('user_roles', 'You can add, edit and delete authority in your NetCommons.')); ?>


<div class="text-right">
	<?php echo $this->LinkButton->add(__d('user_roles', 'Add user role'), array('action' => 'add')); ?>
</div>

<table class="table table-condensed">
	<thead>
		<tr>
			<th>
				<?php echo __d('user_roles', 'User role name'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($userRoles as $index => $userRole) : ?>
			<tr>
				<td>
					<?php echo h($userRole['UserRole']['name']); ?>

					<?php if ($userRole['UserRole']['key'] !== UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR) : ?>
						<?php echo $this->LinkButton->edit('', array('action' => 'edit', h($userRole['UserRole']['key'])), array('iconSize' => ' btn-xs')); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
