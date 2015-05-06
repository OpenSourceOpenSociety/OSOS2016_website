<?php

if (!class_exists('KS_Http')) {

class KS_Http
{
    public static function get($url, &$errors = null)
    {
        // try WP first
        $response = wp_remote_get($url);

        if ($response === null) {
            if ($errors !== null) {
                $errors[] = 'wp_remote_get() returned null';
            }
        } else if ($response === false) {
            if ($errors !== null) {
                $errors[] = 'wp_remote_get() returned false';
            }
        } else if (is_wp_error($response)) {
            if ($errors !== null) {
              $errors[] = sprintf('wp_remote_get() returned: %s', $response->get_error_message());
            }
        } else if (!isset($response['body'])) {
            if ($errors !== null) {
                $errors[] = 'wp_remote_get() has no response body';
            }
        } else if ($response['response']['code'] != 200) {
            if ($errors !== null) {
                $errors[] = sprintf('wp_remote_get() returned HTTP code %d', $response['response']['code']);
            }
        } else {
            return $response['body'];
        }

        // try file_get_contents
        $old_error_level = error_reporting();
        error_reporting(0);
        $response = file_get_contents($url);
        error_reporting($old_error_level);
        if ($response === false) {
            if ($errors !== null) {
                $errors[] = 'file_get_contents() returned false';
            }
        } else {
            return $response;
        }


        // try cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'KS_Http/1.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        if (($errno = curl_errno($ch)) !== 0) {
            $response = false;
            if ($errors !== null) {
                $errors[] = sprintf('cURL returned errno %d (%s)', $errno, curl_error($ch));
            }
        } else if (($code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) != 200) {
            $response = false;
            if ($errors !== null) {
                $errors[] = sprintf('cURL returned HTTP code %d', $code);
            }
        }

        curl_close($ch);

        return $response;
    }
}

}