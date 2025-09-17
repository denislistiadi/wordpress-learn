<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_learn' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'z{5i!/Nq!#v*~wpdGt7FEjibO_i/[C+sBy2h$,~vM4{D[CcBb@lO+e;X7iu6+2Kx' );
define( 'SECURE_AUTH_KEY',  ')RBPqx3H!7u(s-ngM[#pVs}ToL~Y}p-CwU%DC^8]k{JE72M:T>bv>TVp W7hVg@:' );
define( 'LOGGED_IN_KEY',    'E!@([$@4$anG`q],#AJA.f^,esV/u45zffH:`#crZ;|8Z:%S-rGI8(;t?W$@C}k7' );
define( 'NONCE_KEY',        'z#-:G/,cJ_9~i>*OMNvMLuUM|NV gW/2cp6{LkO[(h2,gPRI*ladkd?xI>|D@.JZ' );
define( 'AUTH_SALT',        ']vs$~[<9:VzOf2ETCgYD1.S>a3@ZJz#>1n?>WqBbGrZqoWV`4$ Asr6TUA N@xWg' );
define( 'SECURE_AUTH_SALT', ':qzLi}Q>hGdxxu9=FiOf~tkPFei6/y(r !}ovQ5mUzp}%-*EWb=uxf U%TrtOF#r' );
define( 'LOGGED_IN_SALT',   '~nfshiLf-I##ID6+V9K}XG8T]P?xLE!$B=_7j Ju{~%;m+h@kq@5:EzHsf/=p^`i' );
define( 'NONCE_SALT',       '5:/[rVacM{qB,8.2Nd{wp>TI0[%(#/( w1Y+Z,!lmL.MclO2L>JmZZ1MQCN<ER{a' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_local';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
@ini_set( 'display_errors', 0 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
