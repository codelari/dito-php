<?php 

class Dito {
  public function __construct($options = array()){
    $this->apiKey = $options['apiKey'];
    $this->secret = $options['secret'];

    if(isset($options['environment'])){
      $this->environment = $options['environment'];
    }
    else{
      $this->environment = 'production';
    }
  }

  public function domains($moduleName){
    $domains = Array(
      "js" => 'js',
      "analytics" => 'analytics',
      "login" => 'login',
      "events" => 'events',
      "share" => 'share',
      "comments" => 'comments',
      "ranking" => 'ranking',
      "badge" => 'badge',
      "notification" => 'notification'
    );
  
    if($domains[$moduleName]){
      $name = $domains[$moduleName];
    
      switch ($this->environment) {
        case 'production':
          $url = "https://". $name .".plataformasocial.com.br";
          break;
        case 'development':
          $url = "https://". $name .".dev.plataformasocial.com.br";
          break;
        case 'staging':
          $url = "https://". $name .".dev.plataformasocial.com.br";
          break;
        case 'test':
          $url = "https://". $name .".dev.plataformasocial.com.br";
          break;
        default:
          $url = "https://". $name .".plataformasocial.com.br";
          break;
      }

      return $url;
    }
  }

  public function request($method, $moduleName, $path = '', $params = array(), $headers = array()){
    $url = $this->domains($moduleName) . $path;

    $params['platform_api_key'] = $this->apiKey;
    $params['sha1_signature'] = sha1($this->secret);

    $headers['Content-type'] = 'application/x-www-form-urlencoded';
    
    $http = array();

    $http['header'] = "";
    foreach($headers as $key=>$header){
      $http['header'] .= $key . ": " . $header . "\r\n";
    }
    
    $http['method'] = strtoupper($method);

    if($method == 'get') {
      $url . '?' . http_build_query($params);
    }
    else {
      $http['content'] = http_build_query($params);
    }

    $options = array(
      'http' => $http
    );

    $context  = stream_context_create($options);
    
    return file_get_contents($url, false, $context);
  }

  public function identify($user = array()){
    $params = array();

    if(isset($user['facebook_id'])){
      $networkName = 'facebook';
      $id = $user['facebook_id'];
      $params['network_name'] = 'fb';
    }
    else if(isset($user['google_plus_id'])){
      $networkName = 'plus';
      $id = $user['google_plus_id'];
      $params['network_name'] = 'pl';
    }
    else if(isset($user['twitter_id'])){
      $networkName = 'twitter';
      $id = $user['twitter_id'];
      $params['network_name'] = 'tw';
    }
    else if(isset($user['id'])){
      $networkName = 'portal';
      $id = $user['id'];
      $params['network_name'] = 'pt';
    }
    else{
      return array(
        'error' => array('message' => 'Missing the user id param. See the available options here: http://developers.dito.com.br/docs/sdks/ruby')
      );
    }

    $params['user_data'] = array();

    if($networkName == 'portal'){
      $params['user_data'] = array_merge($params['user_data'], $user);
      unset($params['user_data']['id']);
    }
    else{
      $params['user_data']['data'] = $user['data'];
    }

    if(isset($params['user_data']['data']) && is_array($params['user_data']['data'])){
      $params['user_data']['data'] = json_encode($params['user_data']['data']);
    }

    if(isset($user['access_token'])) $params['access_token'] = $user['access_token'];
    if(isset($user['signed_request'])) $params['signed_request'] = $user['signed_request'];
    if(isset($user['id_token'])) $params['id_token'] = $user['id_token'];

    return $this->request('post', 'login', '/users/'. $networkName .'/'. $id .'/signup', $params);
  }

  public function track($options = array()){
    $credentials = $this->generateIDAndIDType($options);
    $id = $credentials['id'];
    $idType = $credentials['idType'];

    if(!isset($id)){
      return array(
        'error' => array('message' => 'Missing the user id param. See the available options here: http://developers.dito.com.br/docs/sdks/ruby')
      );
    }

    $params = array('event' => json_encode($options['event']));
    if(isset($idType)) $params['id_type'] = $idType;

    return $this->request('post', 'events', '/users/'. $id, $params);
  }

  private

  function generateIDAndIDType($options = array()){
    if(isset($options['reference'])){
      $id = $options['reference'];
      $idType = nil;
    }
    else if(isset($options['facebook_id'])){
      $id = $options['facebook_id'];
      $idType = 'facebook_id';
    }
    else if(isset($options['google_plus_id'])){
      $id = $options['google_plus_id'];
      $idType = 'google_plus_id';
    }
    else if(isset($options['twitter_id'])){
      $id = $options['twitter_id'];
      $idType = 'twitter_id';
    }
    else if(isset($options['id'])){
      $id = $options['id'];
      $idType = 'id';
    }

    return array('id' => $id, 'idType' => $idType);
  }
}