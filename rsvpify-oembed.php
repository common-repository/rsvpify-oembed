<?php
/*
    Plugin Name: RSVPify Event RSVP & Registration Embed
    Plugin URI: https://rsvpify.com/features/
    Description: RSVPify oEmbed plugin.
    Version: 1.2.0
    Author: RSVPify
    Author URI: http://www.rsvpify.com
    License: GNU General Public License v3
*/

class RsvpifyOEmbed{
    public function __construct()
    {
        wp_oembed_add_provider("https://*.rsvpify.com", "https://app3.rsvpify.com/api/rsvp/oembed");

        add_filter('oembed_dataparse', array($this, 'set_rsvpify_sandbox_attribute' ), 99, 4);
    }

    public function set_rsvpify_sandbox_attribute($result, $data, $url) {
        if ($data->provider_url !== 'https://www.rsvpify.com') {
            return $result;
        }

        $sandbox_pos = strpos($data->html, 'sandbox=') + 9;
        $sandbox = substr($data->html, $sandbox_pos, strpos($data->html, '"', $sandbox_pos) - $sandbox_pos);

        $result = str_replace( ' sandbox="allow-scripts"', " sandbox=\"{$sandbox}\"", $result );
        $result = str_replace( 'security="restricted"', "", $result );

        return $result;
    }
}

$rsvpifyOEmbed = new RsvpifyOEmbed();
?>