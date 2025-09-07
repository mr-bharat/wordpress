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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'ZK0Ah+aI$_IC%rKI[`j8oXP3*Ozs?Pr624~,JMN3rs+Bms=7!oA:Gwy=|`}H{e,C' );
define( 'SECURE_AUTH_KEY',  'Xqi4/#2>$|=Dd%[D4QX/U5k@G[k~L`HZ%^of4sxS9!EfTk3/.RXwZ_65^wv[yDFr' );
define( 'LOGGED_IN_KEY',    'rw,>6V~P2G#G^)z~b{+O=a3k(B}rFTp8c ALPQeM;NgNvk.c,=&YXbOV<?i6pvX!' );
define( 'NONCE_KEY',        'biiGZGo$YY+^?,VDd]d|MOGSi6y}It /L+9tW/mb{v$AgT*_Jo`],}Un9=mZ?Qcr' );
define( 'AUTH_SALT',        '?u%<b0dYzXMs%aya#t_T0o,o?g`o#_KF`/-+Rn,MFKZBe~?rlpSV0&a:^4|SJzWr' );
define( 'SECURE_AUTH_SALT', '#%? wA*kqn()5hu.Ai~lY0t4k4*NU2b8uXA{vinFJYyYa!gC,(Ou87) 7btH)lF)' );
define( 'LOGGED_IN_SALT',   'd%_~`?U-uUY,*YIsh1B4ED7!gWwg0%1Y>l3 nZ;S|!@(D|JM|)/lmON}e<:7O.B-' );
define( 'NONCE_SALT',       '.`vyg]6B83D-*kmS#YNbSOM<-Ja%Vvp+W;ZA|`Pac3Pxy&N{zo>&kK<3wNOcV??s' );

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
