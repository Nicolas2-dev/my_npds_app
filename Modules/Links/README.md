----------------------------------------------------------------------
Module Links
----------------------------------------------------------------------
Ce module remplace la fonction standard de App

Il permet une gestion simplifiée d'un annuaire et incorpore de nouvelles fonctions :
 - Vous pouvez avoir plusieurs instances de ce module :
   - dans des sous-répertoires différents (de /modules)
   - links.conf.php permet de spécifier le préfixe des tables
   - le formulaire SFORM peut-être spécifique pour chaque instance

 - La gestion des liens HTTP n'est pas obligatoire (links.conf.php) ce qui vous permettra
   de faire un annuaire de communes, de restaurants ....
 - Le moteur de recherche est celui de App
 - Le module intègre complètement l'administration de l'annuaire



----------------------------------------------------------------------
Installation : Module Links
----------------------------------------------------------------------
 - Créer un sous répertoire dans le répertoire modules [links_01 par exemple]
 - Créer un sous répertoire dans le répertoire modules/sform [links_01 par exemple]
   - modifier le formulaire standard (formulaire.php).
 - Parametrer links.conf.php (préfixe des tables et gestion des liens HTTP)
   => si vous laissez $links_DB="", ce module reprendra directement les tables de
      votre site sans autres manipulations !
 - personnaliser les 3 fichiers de "publicité" (links.ban_01, links.ban_02 et links.ban_03). Ces fichiers sont optionnels
   et peuvent contenir "presque" n'importe quoi.
 - connecter vous en Admin à App
----------------------------------------------------------------------

----------------------------------------------------------------------
Lancement :
----------------------------------------------------------------------
 - via une url de type :
   modules.php?ModPath=[links_01]&ModStart=[links_01]

----------------------------------------------------------------------