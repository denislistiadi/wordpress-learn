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
define( 'AUTH_KEY',         '.rObHG]i8SY;<Kv25Gl!EP#Nm{;E7ZF4n|nqWx.G|v0r-[bvJ[)&plOJu.Z]!k>G' );
define( 'SECURE_AUTH_KEY',  '%!^gpPSc/E07[+8p{E@F5Fu{s^nSN~[6DyR}Gf8=E:->oavpp+<f~!MmvH?5-$61' );
define( 'LOGGED_IN_KEY',    'z3S:iN# `|+%J#Hxw4zHA-;sjgZlNB,&K1h]JaoHTu.I=O$[j%Br&*n.lD,U13%x' );
define( 'NONCE_KEY',        '3E,S|3[:Lt`$XG>N_ThAdT|!]~:UBwF{4aC2X7KO!k8]9;p{bCRatM6!n$k&R:h/' );
define( 'AUTH_SALT',        '4]NMy|9|^,vS~r2m^un2UAPCxQTf):9hMMI}UC5bj1/nV;7N^!*/[>$ux`Ln!J!3' );
define( 'SECURE_AUTH_SALT', '|EyhXTvbH[vE=CJ>r2H%U_jn%`+a+O{mtE6b[@1L5k^#j5^?WW@7wLhv7D{pe7&v' );
define( 'LOGGED_IN_SALT',   'NujL*7IR._K2T9@Uo6N`kJ~0w+CLVWu9QXaUea4e2d9kU1 T* b];QO,S_JmS;/V' );
define( 'NONCE_SALT',       '?K9m^0)-+K_BA>mqi;-0<X]]UB/EB?s8(EMJ*EbhO$l@igmxj_TksGX%.#U|c=Da' );

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
$table_prefix = 'wp_learn_';

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
