<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrdenController extends Controller
{

    public function getOrdenDetalle($orderId)
    {
        // Realiza la solicitud de login para obtener el Bearer Token
        $token = $this->getBearerToken();

        // Si se obtiene el token correctamente, procede a obtener los detalles de la orden por su ID
        if ($token) {
            $ordenDetalle = $this->getOrdenDetallePorId($orderId, $token);
            return response()->json($ordenDetalle);
        }
        // Manejar caso de error
        return response()->json(['error' => 'No se pudo obtener Bearer Token'], 500);
    }

    private function getBearerToken()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://rocky-beyond-58885-df0762919b44.herokuapp.com/login', [
            'json' => [
                'email' => 'jhon@win.investments',
                'password' => 'password'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'] ?? null;
    }

    private function getOrdenDetallePorId($orderId, $token)
    {
        $client = new Client();
        $response = $client->request('GET', "https://rocky-beyond-58885-df0762919b44.herokuapp.com/orders/{$orderId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $ordenDetalle = json_decode($response->getBody(), true);
        return $ordenDetalle;
    }
}
