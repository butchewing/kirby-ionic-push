<?php
/*
 * Kirby Ionic Push Plugin
 * A plugin for Kirby - https://getkirby.com/
 * Version: 0.0.1
 *
 * Author: Butch Ewing @butchewing
*/

// Feel free to set this to whatever you would like.
$prefix   = "push/";

// GCM/APN credentials the notification be sent with.
// http://docs.ionic.io/v2.0.0-beta/docs/security-profiles
$profile  = "dev";

// Ionic Push Notifications Endpoint.
// http://docs.ionic.io/docs/api-push
$endpoint = "https://api.ionic.io/push/notifications";

// Ionic Push Notification API Token.
// http://docs.ionic.io/docs/api-getting-started
$api_token = "";

kirby()->routes(array(
  array(
    'method'  => 'GET',
    'pattern' => $prefix . '(:all)',
    'action'  => function() use($prefix, $profile, $endpoint, $api_token) {
      $path       = kirby()->request()->path();
      $collection = str_replace($prefix, '', $path);
      $updates    = array();
      $items      = array();

      $updates    = page($collection)->children()->sortBy('date', 'asc')->filter(function($child) {
        return ($child->date() > time() - 15 * 60 && $child->date() <= time());
      })->toArray();

      foreach($updates as $update) {
        $items[]         = $update;
        $items[]['push'] = array(
          'send_to_all'  => true,
          'profile'      => $profile,
          'notification' => array(
            'title'   => $update['content']['title'],
            'message' => $update['content']['text']
          )
        );
      }

      foreach ($items as $item) {
        if ($item['push']) {
          // CURL it up.
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $endpoint);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_token

          ));
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item['push']));
          $result = curl_exec($ch);
          curl_close($ch);
        }
      }
    }
  )
));
