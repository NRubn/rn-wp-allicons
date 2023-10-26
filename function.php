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

// Die Funktion zur Anzeige der Einstellungsseite
// Fügen Sie diese Funktion zu Ihrer "rn-wp-all-icons.php"-Datei hinzu, um das Menüelement in der Seitenleiste anzuzeigen.

function rn_wp_all_icons_admin_menu() {
    add_menu_page(
        'RN WP All Icons',
        'RN WP All Icons',
        'manage_options',
        'rn-wp-all-icons-settings',
        'rn_wp_all_icons_settings_page',
        'dashicons-star-half' // Hier wird das Dashicon festgelegt
    );
}

// Die Funktion zur Anzeige der Einstellungsseite

function rn_wp_all_icons_settings_page() {
    // Array mit Icons und Titeln
$rn_icon_sets = array(
    'rn_enable_dashicons' => 'WordPress-Dashicons',
    'rn_enable_fontawesome' => 'Font-Awesome'
);

// Aktivierte Sets
$selected_sets = array();

// Überprüfen, ob das Formular abgesendet wurde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($rn_icon_sets as $icon_set_name => $icon_set_title) {
        $post_key = $icon_set_name;
    
        if (isset($_POST['rn_enable_icon']) && isset($_POST[$post_key]) && $_POST[$post_key] === 'on') {
            $selected_sets[] = $icon_set_name;
            update_option($icon_set_name, 'on');
        } else {
            update_option($icon_set_name, 'off');
        }
    }
}

    echo '<div class="wrap">';
    echo '<h2>RN WP All Icons Einstellungen</h2>';
    echo '<p>Einstellungen</p>';
    // Formular erstellen
    echo '<form method="post">';
    echo '<h3>Wählen Sie die Icon-Sets aus:</h3>';
    foreach ($rn_icon_sets as $icon_set_name => $icon_set_title) {
        echo '<br>ICON SET NAME: '.$icon_set_name.'<br>';
        $checked = in_array($icon_set_name, $selected_sets) ? 'checked' : '';
        // Überprüfen, ob die entsprechende Option in der Datenbank aktiv ist
        $option_value = get_option($icon_set_name);
        if ($option_value === 'on') {
            $checked = 'checked';
        }
        echo '<label><input type="checkbox" name="'. $icon_set_name . '" ' . $checked . '> ' . esc_html($icon_set_title) . '</label><br>';
    }
    echo '<label style="display: none;"><input type="checkbox" name="rn_enable_icon" value="true" checked></label>';
    echo '<input type="submit" value="Aktivieren">';
    echo '</form>';
    $fontawesome='false';
    $dashicons='false';

    foreach ($rn_icon_sets as $icon_set_name => $icon_set_title) {
        // Überprüfen, ob die Option in der Datenbank aktiv ist
        $option_value = get_option($icon_set_name);
        $link = '';
        if($icon_set_name === 'rn_enable_dashicons'){
            $link = 'https://developer.wordpress.org/resource/dashicons/';
            $dashicons='true';
        }
        if($icon_set_name === 'rn_enable_fontawesome'){
            $link = 'https://fontawesome.com/';
            $fontawesome='true';
        }
        if ($option_value === 'on') {
            echo '<p>Eine Liste mit allen ' . esc_html($icon_set_title) . ' Icons findest du hier <a href="'.$link.'" target=”_blank">Link zur Dokumentation</a></p>';
        } else {
            echo '<p>' . esc_html($icon_set_title) . ' deaktiviert</p>';
        }
    }

    if($fontawesome==="true"){
        #first fix this
        #downloadfontawesome();
    }

    if($dashicons==="true"){
        echo '<i class="dashicons dashicons-admin-home"></i>';
    }
}

add_action('admin_menu', 'rn_wp_all_icons_admin_menu');

function enqueue_wp_dashicons_if_enabled() {
    $enable_dashicons_option = get_option('rn_enable_dashicons');

    if ($enable_dashicons_option === 'on') {
        wp_enqueue_style('dashicons');
    }
}

add_action('wp_enqueue_scripts', 'enqueue_wp_dashicons_if_enabled');


function load_fontawesome_css() {
    $rn_enable_fontawesome = get_option('rn_enable_fontawesome');

    if ($rn_enable_fontawesome === 'on') {
        $plugin_dir = plugin_dir_url(__FILE__);
        $css_file_path = $plugin_dir . 'assets/fontawesome-free-web/css/all.min.css';
        $fontawesome_version = '6.4.2'; //Kann später aus einer Option gelesen werden
        // Stil registrieren und laden
        wp_register_style('fontawesome', $css_file_path, array(), $fontawesome_version);
        wp_enqueue_style('fontawesome');
    }

}

add_action('wp_enqueue_scripts', 'load_fontawesome_css');

/*TO DO: Hier klappt der Download noch nicht*/
function downloadfontawesome(){
    $pluginPath = plugins_url('', __FILE__);
    $fontAwesomeUrl = 'https://fontawesome.com/v5.15.1/assets/font-awesome-5.15.1-web.zip'; // URL zur Font-Awesome-ZIP-Datei
    $downloadPath =  $pluginPath . '/Font-Awesome-zip/font-awesome.zip'; // Pfad zum Herunterladen der ZIP-Datei
    $extractPath =  $pluginPath . '/assets/fontawesome'; // Pfad zum Extrahieren der Font Awesome-Dateien

    // Den Inhalt der Font-Awesome-ZIP-Datei herunterladen
    $fileContents = file_get_contents($fontAwesomeUrl);

    if ($fileContents !== false) {
        // Datei im angegebenen Verzeichnis speichern
        if (file_put_contents($downloadPath, $fileContents) !== false) {
            // ZIP-Datei extrahieren
            $zip = new ZipArchive();
            if ($zip->open($downloadPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                echo 'Font Awesome wurde erfolgreich heruntergeladen und in ' . $extractPath . ' extrahiert.';
            } else {
                echo 'Fehler beim Extrahieren der ZIP-Datei.';
            }
        } else {
            echo 'Fehler beim Speichern der Font Awesome-Datei.';
        }
    } else {
        echo 'Fehler beim Herunterladen der Font Awesome-Datei.';
    }
}