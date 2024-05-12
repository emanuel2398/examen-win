<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrdenController extends Controller
{

    public function getOrdenDetalle($orderId)
    {
        // Realiza la solicitud de login para obtener el Bearer Token
        $token = $this->getBearerToken();

        if (!$token) { // Verificar si se recibió el token
            return response()->json(['error' => 'No se pudo obtener Bearer Token'], 500);
        }
        
        $ordenDetalle = $this->getOrdenDetallePorId($orderId, $token); // Obtiene el detalle de la orden por ID utilizando el token
        if ($ordenDetalle['order']['status'] == "processing" && !Orden::find($ordenDetalle['order']['id'])) { // Verifica el estado y la existencia de la orden
            $this->createOrden($ordenDetalle); // Crea la orden si está en estado "processing" y no existe
            $message = 'Detalle de la orden obtenido y creada correctamente.';
        }else {
            $message = 'La orden ya existe o está pendiente.';
        }
        $response = [
            'orden' => $ordenDetalle,
            'message' => $message
        ];

        return response()->json($response); // Devuelve la respuesta JSON
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
     //Requerimiento B
    private function createOrden($ordenDetalle){
        Orden::create([
            'id'=>$ordenDetalle['order']['id'],
            'status' => $ordenDetalle['order']['status'],
            'amount' => $ordenDetalle['order']['amount'],
            'group_id' => $ordenDetalle['order']['group_id']
        ]);
    }
    //Requerimiento C
    public function filtrarOrdenes($status=null, $group_id=null, $amount=null)
    {
         $ordenesFiltradas = Orden::query()->byStatus($status)->byGroupId($group_id)  //Se usa scopes para filtrar
         ->byAmount($amount)->get();
         
        return response()->json($ordenesFiltradas);         
    }
    //Requerimiento D
    public function totalOrdenes(){

        $cantidadOrdenes = Orden::count(); // Obtiene la cantidad total de órdenes
        $sumaMontos = Orden::sum('amount'); // Obtiene la suma del campo "amount" de todas las órdenes
        $response = [
            'cantidad_total_ordenes' => $cantidadOrdenes,
            'suma_montos_ordenes' => $sumaMontos,
        ];
        return response()->json($response);
    }

    public function guardarTodasOrdenes($orderId)
    {
        // Realiza la solicitud de login para obtener el Bearer Token
        $token = $this->getBearerToken();

        if (!$token) { // Verificar si se recibió el token
            return response()->json(['error' => 'No se pudo obtener Bearer Token'], 500);
        }
        
        $ordenDetalle = $this->getOrdenDetallePorId($orderId, $token); // Obtiene el detalle de la orden por ID utilizando el token
        if (!Orden::find($ordenDetalle['order']['id'])) { // Verifica la existencia de la orden
            $this->createOrden($ordenDetalle); // Crea la orden si está en estado "processing" y no existe
            $message = 'Detalle de la orden obtenido y creada correctamente.';
        }else {
            $message = 'La orden ya existe.';
        }
        $response = [
            'orden' => $ordenDetalle,
            'message' => $message
        ];
        return response()->json($response); // Devuelve la respuesta JSON
    }
}
