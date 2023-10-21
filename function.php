<?php
/*
Plugin Name: RN WP All Icons
Description: Ein WordPress-Plugin, das Icons zu Ihrer Website hinzufügt.
Version: 0.01
Author: Ruben Norgall
*/

## Ziele
/*
Einhaltung Regeln: Prakmatischer Programmierer
- DRY Dont Repeat yourself
- KIS Keep it Simple
- 
*/
##V.0.01
/* TO DOS:
- Menu
- Curl Font-Awesome Bibliotheke
- Einbindung WP-Dashicons
- Menuübersicht
- Menuerklärung
*/
##V.1.0.
/*
- Lokale Einbundung von Font-Awesome, wird heruntergeladen, per Menubutton
- Aktivierung der Wordpress-Icons im Frontend
*/

/*ADMIN BACKEND*/

// Funktion zum Hinzufügen des Backend-Menüs
  function rn_wp_all_icons_menu_page() {
    add_menu_page(
        'RN WP All Icons', // Seitentitel
        'RN WP All Icons', // Menütitel
        'manage_options', // Benutzerberechtigung
        'rn-wp-all-icons-settings', // Menü-Slug
        'rn_wp_all_icons_settings_page', // Funktion zum Rendern der Einstellungsseite
        'dashicons-star-half', // Icon
        10 // Position
    );
}

function rn_wp_all_icons_settings_page() {
    $html = ''
    $html .= '<div class="wrap">';
    $html .= '<h2>RN WP All Icons Einstellungen</h2>';
    $html .= '<p>Hier können Sie die Einstellungen für Ihr Plugin vornehmen.</p>';
    $html .= '<form method="post" action="options.php">';
    $html .= settings_fields('rn_wp_all_icons_settings');
    $html .= do_settings_sections('rn-wp-all-icons-settings');
    $html .= submit_button();
    $html .= '</form>';
    $html .= '</div>';

    echo $html;
}

// Hook-Funktion zum Hinzufügen der Menüseite
function rn_wp_all_icons_admin_menu() {
    add_action('admin_menu', 'rn_wp_all_icons_menu_page');
}

// Fügen Sie eine Hook-Funktion hinzu, um die Menüseite hinzuzufügen
add_action('admin_init', 'rn_wp_all_icons_admin_menu');

/**/
