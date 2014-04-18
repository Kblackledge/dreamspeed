<?php

/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/


function dreamspeed_check_required_plugin() {
    if ( class_exists( 'DreamObjects_Services' ) || !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        return;
    }

    require_once ABSPATH . '/wp-admin/includes/plugin.php';
    deactivate_plugins( __FILE__ );

    $msg = sprintf( __( 'Amazon S3 and CloudFront has been deactivated as it requires the <a href="%s">Amazon&nbsp;Web&nbsp;Services</a> plugin.', 'dreamspeed' ), 'https://github.com/deliciousbrains/wp-amazon-web-services' ) . '<br /><br />';
    
    if ( file_exists( WP_PLUGIN_DIR . '/amazon-web-services/amazon-web-services.php' ) ) {
        $activate_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=amazon-web-services/amazon-web-services.php', 'activate-plugin_amazon-web-services/amazon-web-services.php' );
        $msg .= sprintf( __( 'It appears to already be installed. <a href="%s">Click here to activate it.</a>', 'dreamspeed' ), $activate_url );
    }
    else {
        $download_url = 'https://github.com/deliciousbrains/wp-amazon-web-services/releases/download/v0.1/amazon-web-services-0.1.zip';
        $msg .= sprintf( __( '<a href="%s">Click here to download a zip of the latest version.</a> Then install and activate it. ', 'dreamspeed' ), $download_url );
    }

    $msg .= '<br /><br />' . __( 'Once it has been activated, you can activate Amazon&nbsp;S3&nbsp;and&nbsp;CloudFront.', 'dreamspeed' );

    wp_die( $msg );
}

add_action( 'plugins_loaded', 'dreamspeed_check_required_plugin' );

function dreamspeed_init( $aws ) {
    global $dreamspeed;
    require_once 'classes/dreamspeed.php';
    $dreamspeed = new DreamSpeed_Services( __FILE__, $aws );
}

add_action( 'dreamspeed_init', 'dreamspeed_init' );