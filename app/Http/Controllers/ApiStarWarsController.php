<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiStarWarsController extends Controller
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env("BASE_URI_API_STAR_WARS"),
            'timeout' => 2.0,
            'verify' => false,
        ]);
    }

    public function getPeople(Request $request,$id = null)
    {
        $data = $this->getApiData('people',$id);

        return response()->json($data);
    }

    public function getPlanets(Request $request,$id = null)
    {
        $data = $this->getApiData('planets',$id);
        return response()->json($data);
    }

    public function getVehicles(Request $request,$id = null)
    {
        $data = $this->getApiData('vehicles',$id);
        return response()->json($data);
    }

    private function getApiData(string $url,int $id = null)
    {
        $urlParameters = [];

        if ($id) {
            $url = 'people' . $id ? '/'.$id : '';
        }else{
            $urlParameters = array('query' => ['limit' => 0,'offset' => 10]);
        }

        try {
            $response = $this->client->request('GET', $url,$urlParameters);

            return json_decode($response->getBody(), true);;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorMessage = json_decode($e->getResponse()->getBody(), true)['detail'];
                return ['error' => $errorMessage];
            } else {
                return ['error' => 'Failed to retrieve the info from the API.'];
            }
        }
    }
}
