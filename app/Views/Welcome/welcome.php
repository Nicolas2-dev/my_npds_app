<?php
/**
 * Sample layout
 */

?>

ggggg

<?php if (isset($message) or isset($logout)): ?>
    <?= $message; ?>
    <?= $logout; ?>
<?php endif; ?>

fffff

<div class="page-header">
	<h1><?php echo $title ?></h1>
</div>

<p><?php echo $welcome_message ?></p>

<?php echo $rs ?>

<a class="btn btn-md btn-success" href="<?= site_url('subpage'); ?>">
	<?php echo __('Open subpage'); ?>
</a>
