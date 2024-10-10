<?php if ($skinOn != ''): ?>
    <div class="form-floating">
        <select class="form-select" id="blocskinchoice"><option><?= $skinOn; ?></option></select>
        <label for="blocskinchoice">Choisir un skin</label>
    </div>
    <script type="text/javascript">
        //<![CDATA[
            fetch("<?= site_url('assets/skins/skins.json'); ?>")
                .then(response => response.json())
                .then(data => load(data));

            function load(data) {
                const skins = data.skins;
                const select = document.querySelector("#blocskinchoice");

                skins.forEach((value, index) => {
                    const option = document.createElement("option");
                    option.value = index;
                    option.textContent = value.name;
                    select.append(option);
                });

                select.addEventListener("change", (e) => {
                    const skin = skins[e.target.value];
                    if (skin) {
                        document.querySelector("#bsth").setAttribute("href", skin.css);
                        document.querySelector("#bsthxtra").setAttribute("href", skin.cssxtra);
                    }
                });

                const changeEvent = new Event("change");
                
                select.dispatchEvent(changeEvent);
            }
        //]]>
    </script>
<?php else: ?>
    <div class="alert alert-danger">Thème non skinable</div>
<?php endif; ?>