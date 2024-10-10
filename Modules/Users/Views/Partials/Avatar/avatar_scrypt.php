<script type="text/javascript">
    //<![CDATA[
    $(".ava-meca, #avatar, #tonewavatar").hide();

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#ava_perso").attr("src", e.target.result);
                $(".ava-meca").show();
            }
        }
        reader.readAsDataURL(input.files[0]);
    }

    $("#B1").change(function() {
        readURL(this);
        $("#user_avatar option[value='<?= $user_avatar; ?>']").prop("selected", true);
        $("#user_avatar").prop("disabled", "disabled");
        $("#avatar,#tonewavatar").hide();
        $("#avava .fv-plugins-message-container").removeClass("d-none").addClass("d-block");
    });

    window.reset2 = function(e, f) {
        e.wrap("<form>").closest("form").get(0).reset();
        e.unwrap();
        event.preventDefault();
        $("#B1").removeClass("is-valid is-invalid");
        $("#user_avatar option[value='<?= $user_avatar; ?>']").prop("selected", true);
        $("#user_avatar").prop("disabled", false);
        $("#avava").removeClass("fv-plugins-icon-container has-success");
        $(".ava-meca").hide();
        $("#avava .fv-plugins-message-container").addClass("d-none").removeClass("d-block");
    };
    //]]>
</script>