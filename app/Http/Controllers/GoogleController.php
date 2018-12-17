<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class GoogleController extends Controller
{

    

    function getOAuthCredentialsFile()
    {
      // oauth2 creds

      // $oauth_creds = __DIR__ . '/../oauth-credentials.json';
      return Storage::exists('oauth-credentials.json') ?
        Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()
          . 'oauth-credentials.json' : false;
      // return Storage::exists('oauth-credentials.json') ?
      //   Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . Storage::url('oauth-credentials.json')
      //   : false;
      // if(!Storage::exists('oauth-credentials.json'))
      //   return false;
      // return Storage::get('oauth-credentials.json');
      // dd($oauth_creds);
      // if (file_exists($oauth_creds)) {
      //   return $oauth_creds;
      // }
      // return false;
    }

    function getTokenFile()
    {
      return Storage::exists('token.json') ?
        Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()
          . 'token.json' : false;
    }

    public function getClient()
    {
      $client = new \Google_Client();
      $client->setApplicationName('WeVector');
      $client->setAccessType("offline");
      $credPath = $this->getOAuthCredentialsFile();
      $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
      // $redirect_uri = 'http://www.google.com';
      // dd($redirect_uri);
      $client->setRedirectUri($redirect_uri);
      if(!$credPath) return false;
      $client->setAuthConfig($credPath);
      // $client->addScope(\Google_Service_Drive::DRIVE);
      $client->addScope("https://www.googleapis.com/auth/drive");
      $credPath = $this->getOAuthCredentialsFile();
      $service = new \Google_Service_Drive($client);

      if (isset($_REQUEST['logout'])) {
        unset($_SESSION['upload_token']);
      }

      if (isset($_GET['code'])) {
        echo 'code: ' . $_GET['code'] . '<br>';
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        // store in the session also
        $_SESSION['upload_token'] = $token;
        // redirect back to the example
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
      }

      if (!empty($_SESSION['upload_token'])) {
        $client->setAccessToken($_SESSION['upload_token']);
        if ($client->isAccessTokenExpired()) {
          unset($_SESSION['upload_token']);
        }
      } else {
        $authUrl = $client->createAuthUrl();
        dd($authUrl);
      }

      return $client;
    }

    public function index()
    {
      $client = $this->getClient();
      $service = new \Google_Service_Drive($client);
      dd($service);
      dd($client->getAccessToken());
      var_dump($client->getAccessToken());

      $TESTFILE = 'testfile-small.txt';
      // dd($client);
      if ($_SERVER['REQUEST_METHOD'] == 'POST' && $client->getAccessToken()) {
        // We'll setup an empty 1MB file to upload.
        // DEFINE("TESTFILE", 'testfile-small.txt');
        if (!file_exists($TESTFILE)) {
          $fh = fopen($TESTFILE, 'w');
          fseek($fh, 1024 * 1024);
          fwrite($fh, "!", 1);
          fclose($fh);
        }
      }

      // Now lets try and send the metadata as well using multipart!
      $file = new \Google_Service_Drive_DriveFile();
      $file->setName("Hello World!");
      $result2 = $service->files->create(
          $file,
          array(
            // 'data' => file_get_contents($TESTFILE),
            'data' => Storage::get('google_drive.txt'),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
          )
      );
    }
}

