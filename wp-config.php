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
define( 'DB_NAME', 'wordpress_learn' );

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
define( 'AUTH_KEY',         'eQa]!AuXQi7v<R(5(NcN**>gu/KO{W9qZx->5e.Kol!byDt&!9p<J%Vr(]C>Ax4.' );
define( 'SECURE_AUTH_KEY',  'Apn+}$%&ge*]_lh-sA+*#V;L,p=~=isphk1{eL*1`UZv~5Lh5/?bX^O5PS&xAFbf' );
define( 'LOGGED_IN_KEY',    'yXoTE;qi~tGd6AKT?mz/FX!mdFC k+6--DF{>yP#3*CA^<bfPMEYc+ 7!O_)< /W' );
define( 'NONCE_KEY',        'k|wf0kEbnEL%|,;$Gd$Zvbm20tl/5#Gf:H49~W,N5Y2l(/O SU`Yb/|{&}Q!#F(s' );
define( 'AUTH_SALT',        '&&4y~r3pb~38otEM__6mBQzN(ALa{2]v)m`Xmb&9Q$b&UnJWk <kZ>o9J;Cts>,n' );
define( 'SECURE_AUTH_SALT', '6!3N)P?Tepfm^gx~Z+|V1w4J[<5AB$Hd}S(G-Esh8*!-{ph&`X0M(boqXMaY[3v^' );
define( 'LOGGED_IN_SALT',   'Qys<{k)2lUFAj]R/am1jO-h}q>1{VY_D+no$V&+nA>~4K8ls)}LO>[`iutmq6 ,>' );
define( 'NONCE_SALT',       'q_]o!C8=FC:_$SA^c 2t8TDW6X=(JKH<GRb`6<m7F<_b>OLrQmExE4IP9Sgjy#8R' );

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
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
