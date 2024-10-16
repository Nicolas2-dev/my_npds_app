
    <h2><?= __d('stats', 'Statistiques'); ?></h2>
    <div class="card card-body lead">
        <div>
        <?= __d('stats', 'Nos visiteurs ont visualisé'); ?> <span class="badge bg-secondary"><?= Sanitize::wrh($total); ?></span> <?= __d('stats', 'pages depuis le'); ?> <?= config('npds.startdate'); ?>
        </div>
    </div>
    <h3 class="my-4"><?= __d('stats', 'Navigateurs web'); ?></h3>
    <table data-toggle="table" data-mobile-responsive="true">
        <thead>
            <tr>
                <th data-sortable="true" ><?= __d('stats', 'Navigateurs web'); ?></th>
                <th data-sortable="true" data-halign="center" data-align="right" >%</th>
                <th data-align="right" ></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="<?= Theme::theme_image_row('stats/explorer.gif', 'stats') ?>" alt="MSIE_ico" loading="lazy"/> MSIE </td>
                <td>
                <div class="text-center small"><?= $msie[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $msie[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $msie[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $msie[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/firefox.gif') ?: site_url('assets/images/stats/firefox.gif'); ?>" alt="Mozilla_ico" loading="lazy"/> Mozilla </td>
                <td>
                <div class="text-center small"><?= $netscape[1]; ?> %</div>
                    <div class="progress bg-light">
                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $netscape[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $netscape[1]; ?>%; height:1rem;"></div>
                    </div>
                </td>
                <td> <?= $netscape[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/opera.gif') ?: site_url('assets/images/stats/opera.gif'); ?>" alt="Opera_ico" loading="lazy"/> Opera </td>
                <td>
                <div class="text-center small"><?= $opera[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $opera[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $opera[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $opera[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/chrome.gif') ?: site_url('assets/images/stats/chrome.gif'); ?>" alt="Chrome_ico" loading="lazy"/> Chrome </td>
                <td>
                <div class="text-center small"><?= $chrome[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $chrome[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $chrome[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $chrome[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/safari.gif') ?: site_url('assets/images/stats/safari.gif'); ?>" alt="Safari_ico" loading="lazy"/> Safari </td>
                <td>
                <div class="text-center small"><?= $safari[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $safari[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $safari[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $safari[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/webtv.gif') ?: site_url('assets/images/stats/webtv.gif'); ?>"  alt="WebTV_ico" loading="lazy"/> WebTV </td>
                <td>
                <div class="text-center small"><?= $webtv[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $webtv[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $webtv[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $webtv[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/konqueror.gif') ?: site_url('assets/images/stats/konqueror.gif'); ?>" alt="Konqueror_ico" loading="lazy"/> Konqueror </td>
                <td>
                <div class="text-center small"><?= $konqueror[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $konqueror[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $konqueror[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $konqueror[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/lynx.gif') ?: site_url('assets/images/stats/lynx.gif'); ?>" alt="Lynx_ico" loading="lazy"/> Lynx </td>
                <td>
                <div class="text-center small"><?= $lynx[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $lynx[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $lynx[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $lynx[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/altavista.gif') ?: site_url('assets/images/stats/altavista.gif'); ?>" alt="<?= __d('stats', 'Moteurs de recherche'); ?>_ico" /> <?= __d('stats', 'Moteurs de recherche'); ?> </td>
                <td>
                <div class="text-center small"><?= $bot[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $bot[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $bot[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $bot[0]; ?></td>
            </tr>
            <tr>
                <td><i class="fa fa-question fa-3x align-middle"></i> <?= __d('stats', 'Inconnu'); ?> </td>
                <td>
                <div class="text-center small"><?= $b_other[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $b_other[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $b_other[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $b_other[0]; ?></td>
            </tr>
        </tbody>
    </table>
    <br />
    <h3 class="my-4"><?= __d('stats', 'Systèmes d\'exploitation'); ?></h3>
    <table data-toggle="table" data-mobile-responsive="true" >
        <thead>
            <tr>
                <th data-sortable="true" ><?= __d('stats', 'Systèmes d\'exploitation'); ?></th>
                <th data-sortable="true" data-halign="center" data-align="right">%</th>
                <th data-align="right"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td ><img src="<?= Theme::theme_image('stats/windows.gif') ?: site_url('assets/images/stats/windows.gif'); ?>"  alt="Windows" loading="lazy"/>&nbsp;Windows</td>
                <td>
                <div class="text-center small"><?= $windows[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $windows[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $windows[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $windows[0]; ?></td>
            </tr>
            <tr>
                <td ><img src="<?= Theme::theme_image('stats/linux.gif') ?: site_url('assets/images/stats/linux.gif'); ?>"  alt="Linux" loading="lazy"/>&nbsp;Linux</td>
                <td>
                <div class="text-center small"><?= $linux[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $linux[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $linux[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $linux[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/mac.gif') ?: site_url('assets/images/stats/mac.gif'); ?>"  alt="Mac/PPC" loading="lazy"/>&nbsp;Mac/PPC</td>
                <td>
                <div class="text-center small"><?= $mac[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $mac[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $mac[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $mac[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/bsd.gif') ?: site_url('assets/images/stats/bsd.gif'); ?>"  alt="FreeBSD" loading="lazy"/>&nbsp;FreeBSD</td>
                <td>
                <div class="text-center small"><?= $freebsd[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $freebsd[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $freebsd[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $freebsd[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/sun.gif') ?: site_url('assets/images/stats/sun.gif'); ?>"  alt="SunOS" loading="lazy"/>&nbsp;SunOS</td>
                <td>
                <div class="text-center small"><?= $sunos[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $sunos[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $sunos[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $sunos[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/irix.gif') ?: site_url('assets/images/stats/irix.gif'); ?>"  alt="IRIX" loading="lazy"/>&nbsp;IRIX</td>
                <td>
                <div class="text-center small"><?= $irix[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $irix[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $irix[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $irix[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/be.gif') ?: site_url('assets/images/stats/be.gif'); ?>" alt="BeOS" loading="lazy"/>&nbsp;BeOS</td>
                <td>
                <div class="text-center small"><?= $beos[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $beos[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $beos[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $beos[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/os2.gif') ?: site_url('assets/images/stats/os2.gif'); ?>" alt="OS/2" loading="lazy"/>&nbsp;OS/2</td>
                <td>
                <div class="text-center small"><?= $os2[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $os2[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $os2[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $os2[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/aix.gif') ?: site_url('assets/images/stats/aix.gif'); ?>" alt="AIX" loading="lazy"/>&nbsp;AIX</td>
                <td>
                <div class="text-center small"><?= $aix[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $aix[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $aix[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $aix[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/android.gif') ?: site_url('assets/images/stats/android.gif'); ?>" alt="Android" loading="lazy"/>&nbsp;Android</td>
                <td>
                <div class="text-center small"><?= $andro[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $andro[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $andro[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $andro[0]; ?></td>
            </tr>
            <tr>
                <td><img src="<?= Theme::theme_image('stats/ios.gif') ?: site_url('assets/images/stats/ios.gif'); ?>" alt="Ios" loading="lazy"/> Ios</td>
                <td>
                <div class="text-center small"><?= $ios[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $ios[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $ios[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $ios[0]; ?></td>
            </tr>
            <tr>
                <td><i class="fa fa-question fa-3x align-middle"></i>&nbsp;<?= __d('stats', 'Inconnu'); ?></td>
                <td>
                <div class="text-center small"><?= $os_other[1]; ?> %</div>
                <div class="progress bg-light">
                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?= $os_other[1]; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $os_other[1]; ?>%; height:1rem;"></div>
                </div>
                </td>
                <td><?= $os_other[0]; ?></td>
            </tr>
        </tbody>
    </table>
    <h3 class="my-4"><?= __d('stats', 'Thème(s)'); ?></h3>
    <table data-toggle="table" data-striped="true">
        <thead>
            <tr>
                <th data-sortable="true" data-halign="center"><?= __d('stats', 'Thème(s)'); ?></th>
                <th data-halign="center" data-align="right"><?= __d('stats', 'Nombre d\'utilisateurs par thème'); ?></th>
                <th data-halign="center"><?= __d('stats', 'Status'); ?></th>
            </tr>
        </thead>
        <tbody>




        </tbody>
    </table>

<h3 class="my-4"><?= __d('stats', 'Statistiques diverses'); ?></h3>
<ul class="list-group">
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-user fa-2x text-muted me-1"></i><?= __d('stats', 'Utilisateurs enregistrés'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($unum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-users fa-2x text-muted me-1"></i><?= __d('stats', 'Groupe'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($gnum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-user-edit fa-2x text-muted me-1"></i><?= __d('stats', 'Auteurs actifs'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($anum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="<?= Theme::theme_image('stats/postnew.png') ?: site_url('assets/images/admin/postnew.png'); ?>" alt="" loading="lazy"/><?= __d('stats', 'Articles publiés'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($snum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="<?= Theme::theme_image('stats/topicsman.png') ?: site_url('assets/images/admin/topicsman.png'); ?>" alt="" loading="lazy"/><?= __d('stats', 'Sujets actifs'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($tnum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-comments fa-2x text-muted me-1"></i><?= __d('stats', 'Commentaires'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($cnum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="<?= Theme::theme_image('stats/sections.png') ?: site_url('assets/images/admin/sections.png'); ?>" alt="" loading="lazy"/><?= __d('stats', 'Rubriques spéciales'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($secnum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="<?= Theme::theme_image('stats/sections.png') ?: site_url('assets/images/admin/sections.png'); ?>" alt="" loading="lazy"/><?= __d('stats', 'Articles présents dans les rubriques'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($secanum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-muted me-1"></i><?= __d('stats', 'Liens présents dans la rubrique des liens web'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($links); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-link fa-2x text-muted me-1"></i><?= __d('stats', 'Catégories dans la rubrique des liens web'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($cat); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><img class="me-1" src="<?= Theme::theme_image('stats/submissions.png') ?: site_url('assets/images/admin/submissions.png'); ?>"  alt="" /><?= __d('stats', 'Article en attente d\'édition'); ?> <span class="badge bg-secondary ms-auto"><?= Sanitize::wrh($subnum); ?> </span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted me-1"></i>Version Num <span class="badge bg-danger ms-auto"><?= config('npds.Version_Num'); ?></span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted me-1"></i>Version Id <span class="badge bg-danger ms-auto"><?= config('npds.Version_Id'); ?></span></li>
    <li class="list-group-item d-flex justify-content-start align-items-center"><i class="fa fa-cogs fa-2x text-muted me-1"></i>Version Sub <span class="badge bg-danger ms-auto"><?= config('npds.Version_Sub'); ?></span></li>
</ul>
<br />
<p class="text-center"><a href="http://www.npds.org" >http://www.Npds.org</a> - French Portal Generator Gnu/Gpl Licence</p><br />