<?php
/**
 * Plugin Name: ROT13 HTML Obfuscator
 * Description: An easy-to-use substitution cipher which encrypts your HTML tags (e.g. mail or phone numbers) to avoid them getting harvested by spam-bots.
 * Version: 1.0.0
 * Author: Oliver Pelz
 * Author URI: http://www.oliverpelz.de
 * License: GPLv3
 */


/**
 * Obfuscate HTML code using a ROT13 cipher
 * 
 * @param  array  $atts  HTML code, subject, CSS class, inner HTML text, and whether to provide a fallback.
 * @return string        Obfuscated link.
 */
function rotate_by_13_places( $atts ) {
    $a = shortcode_atts( array(
            'html' => '',
            'class' => '',
            'fallback' => 'false',
        ), $atts );

    // Subject
    if ( !empty( $a['html'] ) ) {
        $html_rot13 = str_rot13($a['html']);
    } else { $html_rot13 = ''; }

    // CSS Class Name
    if ( !empty( $a['class'] ) ) {
        $class = str_rot13('class') . '=' . str_rot13( esc_attr( $a['class'] ) ) . ' ';
    } else { $class = ''; }

    // Provide a CSS fallback?
    if ( $a['fallback'] != 'false' ) {
        $fallback = '<noscript><span style="unicode-bidi:bidi-override;direction:rtl;">' 
                    . strrev( esc_attr( $a['email'] ) )
                    . '</span></noscript>';
    } else { $fallback = ''; }
    
    $obf_id = 'obf_' . rand(0, 9999999);

    return  '<span id="' . $obf_id . '">'
                . '<script>document.getElementById("' . $obf_id . '").innerHTML='.$html_rot13.replace(/[a-zA-Z]/g,function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});document.body.appendChild(eo);</script>'
                . $fallback 
            . '</span>';
}
add_shortcode( 'rot13', 'rotate_by_13_places' );
