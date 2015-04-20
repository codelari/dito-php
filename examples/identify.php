<?php
require_once("../lib/Dito.php");

$dito = new Dito(array(
  'apiKey' => 'MjAxNC0wNS0yMCAxMTowMzoyMSAtMDMwMEdyYXBoIEFwaSBWMjQ0',
  'secret' => 'HNVksCIUywbCIBJOv3UjgqmA7p5chPPFrpBbqvFW'
));

// $dito->identify(array(
//   'id' => 1231232131,
//   'name' => 'Marcos',
//   'email' => 'marcos@dito.com.br',
//   'data' => array(
//     'teste' => 'Marcos'
//   )
// ));

// echo $dito->identify(array(
//   'facebook_id' => '10202840988483394',
//   'access_token' => 'CAALrJyxmEMoBAEyGMZB9ejtNZC4I0ZCRfey5Vba1JxUwaMK3ZBBb46mg1xEwaWJZCZCEKhEv1VXyNoJRNBDlSwR3UjvKuZBMqkgDtefeNbqi0YdQMHbxnh6w9ZCaG9zSnYJ7KslGpv2dWtE45QPbo8ZAHllFpskq8GVvMh9z3Wl33i3WP2ZAO8ZBRttiTSu9XwlirLtJ0kv7nyYXICmvj5ZAZCl1t',
//   'data' => array(
//     'teste' => 'Marcos'
//   )
// ));

// $dito->track(array(
//   'id' => '1231232131',
//   'event' => array(
//     'action' => 'acao-teste'
//   )
// ));

$dito->track(array(
  'facebook_id' => '10202840988483394',
  'event' => array(
    'action' => 'acao-teste'
  )
));