<div class="article_index">
    <h2>!N_titre!</h2>
    <div class="article_sujet">!N_sujet!</div>
    <div class="article_index_contenu">
        <?php
        if ($aid == $informant) {
            echo "!N_texte!";
        } else {
            echo "<span class='emetteur'></span> !N_texte!";
        }
        ?>
    </div>
    <div class="article_index_infos">
        <!-- Par !N_emetteur!,--> Le <!-- !N_publicateur! --> !N_date! [Lu !N_nb_lecture! fois]<br />
        !N_suite!
    </div>
</div>