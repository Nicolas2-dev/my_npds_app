<div class="modal fade" id="bl_versusModal" tabindex="-1" aria-labelledby="bl_versusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bl_versusModalLabel"><img class="adm_img me-2" src="<?= site_url('assets/images/admin/message_npds.png'); ?>" alt="icon_" loading="lazy" /><?= translate("Version"); ?> Npds^</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Vous utilisez Npds^ <?= $Version_Sub; ?> <?= $Version_Num; ?></p>
                <p><?= translate("Une nouvelle version de Npds^ est disponible !"); ?></p>
                <p class="lead mt-3"><?= $versus_info[1]; ?> <?= $versus_info[2]; ?></p>
                <p class="my-3">
                    <a class="me-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'<?= $versus_info[2]; ?>.zip" target="_blank" title="" data-bs-toggle="tooltip" data-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.zip</a>
                    <a class="mx-3" href="https://github.com/npds/npds_dune/archive/refs/tags/'<?= $versus_info[2]; ?>.tar.gz" target="_blank" title="" data-bs-toggle="tooltip" data-original-title="Charger maintenant"><i class="fa fa-download fa-2x me-1"></i>.tar.gz</a>
                </p>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bl_messageModal" tabindex="-1" aria-labelledby="bl_messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=""><span id="bl_messageModalIcon" class="me-2"></span><span id="bl_messageModalLabel"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="bl_messageModalContent"></p>
                <form class="mt-3" id="bl_messageModalForm" action="" method="POST">
                    <input type="hidden" name="id" id="bl_messageModalId" value="0" />
                    <button type="submit" class="btn btn btn-primary btn-sm"><?= translate("Confirmer la lecture") ; ?></button>
                </form>
            </div>
            <div class="modal-footer">
                <span class="small text-muted">Information de Npds.org</span><img class="adm_img me-2" src="<?= site_url('assets/images/admin/message_npds.png'); ?>" alt="icon_" loading="lazy" />
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#bl_messageModal").on("show.bs.modal", function(event) {
            var button = $(event.relatedTarget);
            var id = button.data("id");

            $("#bl_messageModalId").val(id);
            $("#bl_messageModalForm").attr("action", "<?= site_url('admin.php?op=alerte_update'); ?>");

            $.ajax({
                url: "<?= site_url('api/alerte'); ?>",
                method: "POST",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    var fnom_affich = JSON.stringify(data["fnom_affich"]),
                        fretour_h = JSON.stringify(data["fretour_h"]),
                        ficone = JSON.stringify(data["ficone"]);

                    $("#bl_messageModalLabel").html(JSON.parse(fretour_h));
                    $("#bl_messageModalContent").html(JSON.parse(fnom_affich));
                    $("#bl_messageModalIcon").html('<img src="<?= site_url('assets/images/admin/'); ?>' + JSON.parse(ficone) + '.png" />');
                }
            });
        });
    });
</script>