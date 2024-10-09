<?php
/**
 * Sample layout
 */

?>

<?php if (isset($message) or isset($logout)): ?>
    <?= $message; ?>
    <?= $logout; ?>
<?php endif; ?>

<div class="page-header">
	<h1><?php echo $title ?></h1>
</div>

<p><?php echo $welcome_message ?></p>

<a class="btn btn-md btn-success" href="<?= site_url(''); ?>">
	<?php echo __('Home'); ?>
</a>
