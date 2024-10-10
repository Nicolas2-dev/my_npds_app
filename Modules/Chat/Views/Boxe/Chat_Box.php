<?php if ($dimauto <= 1): ?>
    <?php foreach ($chat_box as $chat_line): ?>
        <?php if ($chat_line['db_name'] == 1): ?>
            <?php if($chat_line['guard_and_list']): ?>
                <a href="<?= site_url('user.php?op=userinfo&amp;uname='. $chat_line['db_username']); ?>"><?= $chat_line['username']; ?>.</a>
            <?php else: ?>
                <span class=""><?= $chat_line['username']; ?>.</span>
            <?php endif; ?>
        <?php else: ?>
            <span class=""><?= $chat_line['username']; ?>.</span>
        <?php endif; ?>
        &gt;&nbsp;<span><?= $chat_line['message']; ?></span><br />
    <?php endforeach; ?>

    <?php if ($une_ligne): ?>
        <hr />
    <?php endif; ?>

    <?php if($count_chatters > 0): ?> 
        <div class="d-flex">
            <a id="<?= $pour; ?>_encours" class="fs-4" href="javascript:void(0);" onclick="window.open(<?= $PopUp; ?>);" title="<?= __d('chat', 'Cliquez ici pour entrer'); ?> <?= $pour; ?>" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fa fa-comments fa-2x nav-link faa-pulse animated faa-slow"></i>
            </a>
            <span class="badge rounded-pill bg-primary ms-auto align-self-center" title="<?= __d('chat', 'personne connectée.'); ?>" data-bs-toggle="tooltip">
                <?= $count_chatters; ?>
            </span>
        </div>'
    <?php else: ?>
        <div>
            <a id="<?= $pour; ?>" href="javascript:void(0);" onclick="window.open(<?= $PopUp; ?>);" title="<?= __d('chat', 'Cliquez ici pour entrer'); ?>" data-bs-toggle="tooltip" data-bs-placement="right">
                <i class="fa fa-comments fa-2x "></i>
            </a>
        </div>
    <?php endif; ?>
<?php else: ?>
    <?php if (count($auto) > 1): ?>
        <ul>
            <?php foreach ($chat_box as $chat_line): ?>
            <li>
                <a href="javascript:void(0);" onclick="window.open($chat_line['PopUp']);">
                    <?= $chat_line['autovalueX']['groupe_name']; ?>
                </a>
                <?php if ($chat_line['count_chatters']): ?>
                    &nbsp;(<span class="text-danger"><b><?= $chat_line['count_chatters']; ?></b></span>)
                <?php endif; ?>

            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>    
<?php endif; ?>