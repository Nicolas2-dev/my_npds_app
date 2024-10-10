<?php

echo '
        <script type="text/javascript" src="' . site_url('assets/shared/bootstrap/dist/js/bootstrap.bundle.min.js') . '"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/bootstrap-table/dist/bootstrap-table.min.js') . '"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/bootstrap-table/dist/locale/bootstrap-table-' . language_iso(1, "-", 1) . '.min.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/table-export-jquery-plugin-master/tableExport.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/js/js.cookie.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/jquery-ui/jquery-ui.min.js') . '" ></script>
        <script type="text/javascript" src="' . site_url('assets/shared/bootboxjs/bootbox.min.js') . '" async="async"></script>
        <script type="text/javascript" src="' . site_url('assets/shared/prismjs/prism.js') . '"></script>
        <script type="text/javascript">
            //<![CDATA[
                (tarteaucitron.job = tarteaucitron.job || []).push("vimeo");
                (tarteaucitron.job = tarteaucitron.job || []).push("youtube");
                (tarteaucitron.job = tarteaucitron.job || []).push("dailymotion");
                //tarteaucitron.user.gtagUa = ""; /*uncomment the line and add your gtag*/
                //tarteaucitron.user.gtagMore = function () { /* uncomment the line add here your optionnal gtag() */ };
                //(tarteaucitron.job = tarteaucitron.job || []).push("gtag");
            //]]
        </script>
    </footer>
</div>
<script type="text/javascript" src="' . site_url('assets/js/npds_adapt.js') . '"></script>';
