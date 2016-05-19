<?php
/*
 * Kirby Ionic Push Plugin
 * A plugin for Kirby - https://getkirby.com/
 * Version: 0.0.2
 *
 * Author: Butch Ewing @butchewing
*/

// Feel free to set this to whatever you would like.
$prefix    = "push/";

// GCM/APN credentials the notification be sent with.
// http://docs.ionic.io/v2.0.0-beta/docs/security-profiles
$profile   = "prod";

// Ionic Push Notifications Endpoint.
// http://docs.ionic.io/docs/api-push
$endpoint  = "https://api.ionic.io/push/notifications";

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
      $push       = array();
      $items      = array();

      $push       = page($collection)->children()->sortBy('date', 'asc')->filter(function($child) {
        return date('Y-m-d', $child->date()) === date('Y-m-d', time()) && ($child->time() > date('H:i:s', time() - 15 * 60) && $child->time() <= date('H:i:s', time()));
      })->toArray();

      foreach($push as $row) {
        $items[] = array(
          'tokens'       => ["all"],
          'profile'      => $profile,
          'notification' => array(
            'title'   => $row['content']['title'],
            'message' => $row['content']['text'],
            'android' => array(
              'title'   => $row['content']['title'],
              'message' => $row['content']['text']
            ),
            'ios' => array(
              'title'   => $row['content']['title'],
              'message' => $row['content']['text']
            )
          )
        );
      }

      $header = array();
      $header[] = 'Content-type: application/json';
      $header[] = 'Authorization: Bearer '. $api_token;

      foreach ($items as $item) {
        // CURL it up.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($item));
        $result = curl_exec($ch);
        curl_close($ch);
      }
    }
  )
));
