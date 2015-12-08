<?php
/**
 * サブタイトル(権限名表示)Element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->start('subtitle'); ?>
	<?php if (isset($subtitle)) : ?>
		<span class="text-muted">(<?php echo h($subtitle); ?>)</span>
	<?php endif; ?>
<?php $this->end();
