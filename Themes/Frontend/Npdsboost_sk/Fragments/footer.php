<?php

use Npds\Support\Profiler;
use Npds\Console\Profiler as QuickProfiler;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Language;

?>
<footer id="footer" class="footer text-center mt-4">
    <div class="container">
        <p>!sc_infos!
            <br />!msg_foot!
        </p>
        <script type="text/javascript" src="<?= site_url('assets/shared/jquery-translate/jquery.translate.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/js/npds-dicotransl.js'); ?>"></script>
        <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function() {
                var translator = $('body').translate({
                    lang: "fr",
                    t: dict
                });

                translator.lang("<?php echo Language::language_iso(1, '', 0); ?>");

                $('.plusdecontenu').click(function() {
                    var $this = $(this);
                    $this.toggleClass('plusdecontenu');
                    
                    if ($this.hasClass('plusdecontenu')) {
                        $this.text(translator.get('Plus de contenu'));
                    } else {
                        $this.text(translator.get('Moins de contenu'));
                    }
                });

                if (matchMedia) {
                    const mq = window.matchMedia("(max-width: 991px)");
                    mq.addListener(WidthChange);
                    WidthChange(mq);
                }

                function WidthChange(mq) {
                    if (mq.matches) {
                        $("#col_LB, #col_RB").removeClass("show")
                    } else {
                        $("#col_LB, #col_RB").addClass("show")
                    }
                }
            });
            //]]
        </script>
    </div>
    <div class="container">
        <div class="row" style="margin: 15px 0 0;">
            <div class="col-lg-4">
                <p class="text-muted">Copyright &copy; <?php echo date('Y'); ?> <a href="http://www.simplemvcframework.com/" target="_blank"><b>Npds Framework</b></a></p>
            </div>
            <div class="col-lg-8">
                <p class="text-muted pull-right">
                    <?php if (ENVIRONMENT == 'development') { ?>
                        <small><?= Profiler::report(); ?></small>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php
if ((ENVIRONMENT == 'development') && (Auth::guard('admin'))) {
    // Show the QuickProfiler Widget.
    QuickProfiler::process();
}
?>
