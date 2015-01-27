<?php
$server 			= "http://www.clubjoanis.com/";
$global_vars 	=	array(

# ------------------------------------------------------
# LANGUE
# ------------------------------------------------------

"LANGUAGE"	=>	"fr",

# ------------------------------------------------------
# WEBROOT (Racine du serveur Web)
# -----------------------------------------------------

"WEBROOT"		=>  "/www/docs/",
"URL"				=>  $server,
"PATH_EXE"	=>  "/home/httpd/vhosts/clubjoanis.com/cgi-bin/",

# ------------------------------------------------------
# MYSQL
# -----------------------------------------------------

"DB_HOST"		=>	"localhost",
"DB_NAME"		=>	"club",
//"DB_USER"		=>	"root",
//"DB_PWD"		=>	"",
"DB_USER"		=>	"club",
"DB_PWD"		=>	"V@lj0@n1s2014",

# ------------------------------------------------------
# SESSION
#
# 	- SESSION_ON : utilise les sessions PHP (1|0)
# 	- SESSION_EXPIRE : nbre de minutes pendant lesquelles la session est active
# 	- SESSION_MODE : (cookie|get) specifie si la session utilise ou non les Cookies pour se propager
#                  si le mode cookie echoue, le mode get est automatiquement utilisé
# -----------------------------------------------------

"SESSION_EXPIRE"	=>	"0",
"SESSION_MODE"		=>	"cookie",

# ------------------------------------------------------
# RESULTATS DE RECHERCHES
#
# 	- SEARCH_ROWS : nombre de lignes à afficher par page
# 	- SEARCH_COLOR_1 et SEARCH_COLOR_2 : couleurs des lignes de resultat (paires et impaires)
# -----------------------------------------------------

"SEARCH_ROWS"			=>	"20",
"SEARCH_COLOR_1"	=>	"#CCCCCC",
"SEARCH_COLOR_2"	=>	"#ffffff",

# ------------------------------------------------------
# FICHES VINS
# -----------------------------------------------------

"PROD_VINI"  			=> "Vinification",
"PROD_SIT"  			=> "Situation",
"PROD_GEO"  			=> "Géologie",
"PROD_ELAB"  			=> "Elaboration",
"PROD_VINI"  			=> "Vinification",
"PROD_DEGUS"  		=> "Dégustation",
"PROD_CEP"  			=> "Cépages",
"PROD_SERV"  			=> "Service",
"PROD_PRIX"  			=> "<b>Prix TTC : %s € TTC/btle</b> (par %s bouteilles)",
"PROD_PRIX1"  		=> "Prix TTC : <b>%s € TTC/btle</b><br>(par %s bouteilles)",

# ------------------------------------------------------
# ENTETES DU PANIER
# -----------------------------------------------------

"CART"       			=> "Votre panier",
"CART_NAME"       => "Nom",
"CART_PRICE"      => "Prix/btle",
"CART_QUANTITY"   => "Quantité",
"CART_SUBTOTAL"   => "Total",
"CART_TOTAL"   		=> "Sous-total",
"CART_SUPPR"   		=> "Supprimer",
"CART_REM"   			=> "Remise quantitative",
"CART_REM_TXT"   	=> "Remise quantitative",
"CART_REM1"   		=> "Remise personnelle",
"CART_REM1_TXT"   => "Remise personnelle",
"CART_TAXES"   		=> "Dont TVA",
"CART_TOTAL_TTC"  => "Total TTC",
"MAX_QUANTITY"    =>  100000,

# ------------------------------------------------------
# ENTETES COMMANDES
# -----------------------------------------------------

"CDE"  						=> "Commande N° %s du %s",
"CDE_NAME"  			=> "Adresse de facturation",
"CDE_LIV"  				=> "Adresse de livraison",
"CDE_INSTR"  			=> "Instructions de livraison",

# ------------------------------------------------------
# FORMULAIRES CLIENTS
# -----------------------------------------------------

"CLIENT_NOM"  		=> "Nom",
"CLIENT_PRENOM"  	=> "Prénom",
"CLIENT_SOC"  		=> "Société",
"CLIENT_ADR"  		=> "Adresse",
"CLIENT_ZIP"  		=> "Code Postal",
"CLIENT_VILLE"  	=> "Ville",
"CLIENT_MAIL"  		=> "Email",
"CLIENT_TEL"  		=> "Téléphone",
"CLIENT_MOB"  		=> "Mobile",
"CLIENT_PASS"  		=> "Mot de passe",
"CLIENT_INSTR"  	=> "Instructions complémentaires concernant la commande (code, étage, etc) : ",
"CLIENT_LIV1"  		=> "Créer une nouvelle adresse de livraison",
"CLIENT_LIV2"  		=> "Identique à l'adresse de facturation",
"CLIENT_REGL"  		=> "Mode de règlement",
"CLIENT_REGL1"  	=> "CB Sécurisée",
"CLIENT_REGL2"  	=> "Chèque",
"CLIENT_VALID"  	=> "Merci de v&eacute;rifier et de compl&eacute;ter vos coordonn&eacute;es, puis de valider votre commande :",

# ------------------------------------------------------
# MAILS
# 	- FROM : expediteur
# 	- WELCOME_TITLE et WELCOME_MSG : email envoyé lors de l'inscription
# 	- PASSWD_TITLE et PASSWD_MSG : email envoyé lors de l'oubli d'un mot de passe
# -----------------------------------------------------

"FROM"            => "info@clubjoanis.com",
"WELCOME_TITLE" 	=> "Inscription au club Joanis.",
"WELCOME_MSG" 		=>
"Bienvenue %s %s,

Vous êtes désormais inscrit au club Joanis et nous vous en remercions.

Veuillez trouver ci-dessous vos codes d'accès personnels :

 - Votre adresse email : %s
 - Votre mot de passe : %s
 
Veuillez copier ce mot de passe et l'indiquer sur la page d'accès au Club Joanis.
Vous pouvez accéder à cette page en cliquant ici :
".$server."login.php

Vous pourrez personnaliser votre mot de passe sur la page de votre profil après vous être identifié.

Nous vous remercions de votre confiance.

L'équipe du Club Joanis

-------------------------------------
Le Club Joanis - Château Val Joanis - 84120 Pertuis
Tél : 04 90 79 20 77 - Fax : 04 90 09 69 52
",
"PASSWD_TITLE" 		=> "Vos codes d'accès au Club Joanis.",
"PASSWD_MSG" 			=>
"Bonjour %s %s,

Vous avez demandé à recevoir votre mot de passe sur le club Joanis.

Le voici:

 - Votre adresse email : %s
 - Votre mot de passe: %s

Veuillez copier ce mot de passe et l'indiquer sur la page d'accès au Club Joanis.
Vous pouvez accéder à cette page en cliquant ici :
".$server."login.php

Nous vous remercions.

L'équipe du Club Joanis

-------------------------------------
Le Club Joanis - Château Val Joanis - 84120 Pertuis
Tél : 04 90 79 20 77 - Fax : 04 90 09 69 52
",

# ------------------------------------------------------
# MESSAGES D'ERREUR
# 	- LOGIN_EXISTS : l'utilisateur existe deja
# 	- LOGIN_UNKNOWN : l'utilisateur est inconnu ou son mot de passe invalide
# 	- LOGIN_CONFIRM : l'utilisateur vient d'être crée et doit recevoir son mot de passe par mail
# -----------------------------------------------------

"LOGIN_EXISTS" 		=> "Cette adresse Email est déjà enregistrée.<br>Si vous avez oublié votre mot de passe,
			<a href=\"Javascript:document.location.href='login.php$urlSession&recupPassword=1&user_email=$user_email'\">cliquez ici</a>
			pour le recevoir à l'adresse %s",
"LOGIN_UNKNOWN" 	=> "Cette adresse email n'est pas enregistée.<br>Si vous souhaitez vous inscrire,
			<br>merci de remplir le formulaire en <a href='login.php'>cliquant ici</a>",
"LOGIN_CONFIRM" 	=> "Un email contenant vos codes d'accès a été envoyé à l'adresse suivante : %s",
"LOGIN_TXT" 			=> "Afin de compléter votre commande, veuillez vous identifier<br>
			<br>Si vous n'êtes pas encore inscrit au club Joanis, vous pouvez devenir membre en
			<a href='login.php?inscr=1' style='color:black'>cliquant ici</a>",
"LOGIN_TXT1" 			=> "En devenant membre du club Joanis, vous aurez accès à nos productions du sud de la vallée du Rhône<br>
			ainsi qu'à des offres exceptionnelles réservées aux membres du club.",
"LOGIN_BTN" 			=> "Entrez",
"LOGIN_BTN1" 			=> "Créer mon compte",
"LOGIN_PASS" 			=> "Vous avez oublié votre mot de passe ?",

# ------------------------------------------------------
# AFFICHAGES DES PAGES
# -----------------------------------------------------

"PAGETITLE"       => "Le Club Joanis", # titre
"METAKEYS"        => "",  			# meta keywords
"METADESC"        => "",  			# meta description
"TABLEWIDTH"  		=> "90%",  	# largeur de la page
"SPACER_WIDTH"  	=> "10",    	# espacement entre les colonnes
"LEFT_WIDTH"  		=> "200",   	# taille du menu de gauche
"RIGHT_WIDTH"  		=> "160",   	# taille du menu de droite
"TITLE_WIDTH"     => "15",    	# taille des titres
"MAIN_COLOR"      => "#003366", # couleur du fond
"GENERATE_NAME"		=> "index.html",

# ------------------------------------------------------
# CHECK_STOCK
# 	détermine si la boutique doit ou non vérifier les disponibilités des produits
# -----------------------------------------------------

"CHECK_STOCK"			=>	"0",

# ------------------------------------------------------
# NE RIEN SUPPRIMER SOUS CETTE LIGNE !
# -----------------------------------------------------
"ENDPOINT"				=> 	""
);

while (list($key, $value) = each($global_vars)) {
  define($key, $value);
}
?>
