<?php
/**
 * Frontend Default Layout
 */

use Npds\Support\Assets;
use App\Modules\Theme\Support\Facades\Theme;

?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_CODE; ?>">
<head>

	<!-- Site meta -->
	<meta charset="utf-8">
	<?php
	// Add Controller specific data.
    if (is_array($headerMetaData)) {
        foreach($headerMetaData as $str) {
            echo $str;
        }
    }
	?>
	<title><?= $title.' - '.SITE_TITLE; ?></title>

    <?= Theme::head(); ?>

	<!-- CSS -->
	<?php
	Assets::css(array(
		// '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css',
		// '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css',
		// '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
		// site_url('themes/default/assets/css/style.css')
	));

	//Add Controller specific CSS files.
    Assets::css($headerCSSheets);

    Assets::js(array(
        // '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',
    ));

    //Add Controller specific JS files.
    Assets::js($headerJScripts);

	?>
</head>
<body>

<?php Theme::theme_header($pdst)?>

<?php if (isset($GraphicAdmin)): ?>
    <?= $GraphicAdmin; ?>
<?php endif; ?>

<?php if (isset($adminhead)): ?>
    <?= $adminhead; ?>
<?php endif; ?>

<!-- Content Area -->
<?= $content; ?>

<?php if (isset($adminfoot)): ?>
    <?= $adminfoot; ?>
<?php endif; ?>

<?php Theme::theme_footer($pdst)?>

<!-- JS -->
<?php
Assets::js(array(
	// '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'
));

//Add Controller specific JS files.
Assets::js($footerJScripts);
?>

</body>
</html>
