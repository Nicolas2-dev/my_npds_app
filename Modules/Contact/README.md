----------------------------------------------------------------------
Installation : contact (Formulaire de contact (ou autre)    
----------------------------------------------------------------------

Ce module démontre les possibilités de SFORM :
 - fabrication simple d'un formulaire
 - utilisation des fonctions de App (en l'occurence send_email)
   => c'est l'email qui est configurée dans les préférences / Section :
      "Envoyer par E-mail les nouveaux articles à l'administrateur'

----------------------------------------------------------------------
Lancement :
----------------------------------------------------------------------
 - via une url de type : modules.php?ModPath=contact&ModStart=contact

----------------------------------------------------------------------
Personnalisation :
----------------------------------------------------------------------
1 - Le contenu du formulaire est modifiable : sform/contact/formulaire.php
2 - vous pouvez créer d'autres formulaires en copiant le premier et en changeant de nom
    du formulaire (par ex : contact2.php) et en modifiant le lancement (ModStart=contact2)
3 - vous pouvez gérer certains paramètres de ce modules via pages.php
  $PAGES['modules.php?ModPath=ModPath=contact&ModStart=contact*'][title]="[french]Contactez-nous[/french][english]Contact us[/english][chinese]Contact us[/chinese]+|$title+";
  $PAGES['modules.php?ModPath=contact&ModStart=contact*'][run]="yes";
  $PAGES['modules.php?ModPath=contact&ModStart=contact*'][blocs]="0";