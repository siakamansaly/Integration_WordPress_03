<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache



/** Enable W3 Total Cache */
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */
// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'chaletscaviar' );
/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );
/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );
/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );
/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );
/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'y* D!9gNgrMzKB|): c>>olL5s}v%Bw&grjf+&9{}DuHb8l#UC8T-.t_!x.o#[]b' );
define( 'SECURE_AUTH_KEY',  '60{>]=/@&O)2?Z6KK,h-4W2-T3Xt[w2x;.<D[ek<6u;=_}v=a1SZ^%&pl`)?$XVm' );
define( 'LOGGED_IN_KEY',    '&6P=ya3emS58%sd~v<`sV$C;&92X?%k!A_=qqjG7I3/upmL%b8n?=>)&5hAZ-|St' );
define( 'NONCE_KEY',        '<P=bAG/z9f,rkT@6f68nwX/5|ubc;qv ,KNE3M,=)vk=*O;NveU~2-2Eb6%NkKLL' );
define( 'AUTH_SALT',        'm0]f*bB,d]nCD5iVAy8(?A#f#^dZ6 l9#?t?+b)6X1TJ~$N/VXF9A@/4?,&J8[xP' );
define( 'SECURE_AUTH_SALT', 'p((.%cj/9pXb,USIJ]g`G,m&Y(2v`jj}bw`g*L ::bj$G*R:hkN[,zfdggH&d& v' );
define( 'LOGGED_IN_SALT',   'V2[skY:2h7LErhgh.B.^[Pe[>3Vl,@hkS:bv.,b9os^tJAs=+Z#6LZsF+~4w9Mms' );
define( 'NONCE_SALT',       'ex.iyPy!hFpCoys9YA^WAJ;gEcvBHn9u+-v7dZ7TFCKOYk02km,U?j0Z;n-]c5nU' );
/**#@-*/
/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'ocr_';
/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'SCRIPT_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */
/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );
  define( 'WP_SAMESITE_COOKIE', 'None' );
/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );