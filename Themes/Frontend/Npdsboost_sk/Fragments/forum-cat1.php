<h2 class="mb-3">Forums</h2>
forum_subscribeON()
<div class="mb-3 row">
    <div class="col-sm-12">
        <input type="text" class="mb-3 form-control form-control-sm n_filtrbox" placeholder="Filtrer les résultats" />
    </div>
</div>
<div class="n-filtrable list-group">
    forum_categorie(1)
</div>
<div class="row">
    <div class="col-sm-12">
        <span class="help-block">forum_message()</span>
        forum_bouton_subscribe()
    </div>
</div>
forum_subscribeOFF()
<div id="forum_recherche">
    <p>forum_recherche()</p>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $('.n-filtrable div p').each(function() {
            $(this).attr('data-search-term', $(this).text().toLowerCase());
            $('.n_filtrbox').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                $('.n-filtrable div p').each(function() {
                    if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        $('#ckball_f').change(function() {
            check_a_f = $('#ckball_f').is(':checked');
            if (check_a_f) {
                $('#ckb_status_f').text('Tout décocher');
            } else {
                $('#ckb_status_f').text('Tout cocher');
            }
            $('.n-ckbf').prop('checked', $(this).prop('checked'));
        });
    });
    //]]
</script>