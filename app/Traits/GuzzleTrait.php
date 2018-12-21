<?php

namespace App\Traits;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

trait GuzzleTrait {

    public function getResBody($res)
    {
      return $res->getBody();
    }

    public function getResStatusCode($res)
    {
      return $res->getStatusCode();
    }

    public function getReqExMessage($e)
    {
      return $e->getMessage();
    }

    public function getReqExRequest($e)
    {
      return $e->getRequest();
    }

    public function getReqExRequestMethod($e)
    {
      return $e->getRequest()->getMethod();
    }

//    public function getRemoteData($method = 'GET', $url = 'http://httpbin.org/get', $headers = null)
    public function request($method = 'GET', $url = 'http://httpbin.org/get', $headers = [], $body = '')
    {
      $client = count($headers) == 0 ? new Client() : new Client($headers);
      $promise = $client->requestAsync($method, $url, $headers, $body);
//      dd($promise);
//        $promise = $client->requestAsync($method, $url);
      $promise->then(
        function (ResponseInterface $res) {
          return $res;
        },
        function (RequestException $e) {
          return $e;
        }
      );

      $response = $promise->wait();
//      dd($this->getResBody($response));
      return $response;

//      echo $this->getResStatusCode($response);
//      echo $this->getResBody($response);

      // echo $this->getResBody($response);
      // $body = $responsePOST->getBody();
      // dd($body->getContents());
      // $promise = $client->postAsync('post');
      //
      // // dd($promise);
      //
      // $promise->then(
      //   function(ResponseInterface $res) {
      //     echo "res->getStatusCode(): $res->getStatusCode()\n";
      //     die();
      //   },
      //   function(RequestException $e) {
      //     echo "e->getMessage(): $e->getMessage()\n\n";
      //     echo "e->getRequest(): $e->getRequest()\n\n";
      //     echo "e->getRequest()->getMethod(): $e->getRequest()->getMethod()\n\n";
      //     die();
      //   }
      // );

      // dd($responseGET);

      // $res = $responseGET->getStatusCode();
      // // $res .= "GET getStatusCode<BR>$responseGET->getStatusCode()<BR>";
      // if($responseGET->hasHeader('Content-Length'))
      //   $res .= "GET hasHeader<BR>$responseGET->getHeader('Content-Length')";
      //
      // foreach($responseGET->getHeaders() as $name => $values){
      //   echo $name . ': ' . implode(', ', $values) . '\r\n';
      // }
      //
      // $body = $responseGET->getBody();
      // $res .= "GET body: " . $body;

      // return "GET<BR>$responseGET<BR><BR>DELETE<BR>$responseDELETE<BR><BR>
      //         PATCH<BR>$responsePATCH<BR><BR>POST<BR>$responsePOST<BR><BR>
      //         PUT<BR>$responsePUT<BR>";
    //   $client = new Client(
    //     ['headers' =>
    //       ['content-type' => 'application/json',
    //       'Accept' => 'application/json']
    //   ]);
    //
    //   $response = $client->request('POST', 'https://jsonplaceholder.typicode.com/posts',
    //     [
    //       'json' => [
    //         'code' => 'token',
    //       ]
    //     ]);
    //
    //   $data = $response->getBody();
    //   $data = json_decode($data);
    //   var_dump($data);
    }
}
