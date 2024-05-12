<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class OrdenController extends Controller
{

    public function getOrdenDetalle($orderId)
    {
        $token = $this->getBearerToken();
        if (!$token) {
            return response()->json(['error' => 'No se pudo obtener Bearer Token'], 500);
        }
        $ordenDetalle = $this->getOrdenDetallePorId($orderId, $token); 
        if ($ordenDetalle['order']['status'] != "processing" || Orden::find($ordenDetalle['order']['id'])) { 
            return response()->json(['mensaje'=>'La orden ya existe o esta pendiente.']);  
        }
        $this->createOrden($ordenDetalle); 
        $response = [
            'orden' => $ordenDetalle,
            'mensaje' => 'Detalle de la orden obtenido y creada correctamente.'
        ];
        return response()->json($response); 
    }

    private function getBearerToken()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'https://rocky-beyond-58885-df0762919b44.herokuapp.com/login', [
                'json' => [
                    'email' => 'jhon@win.investments',
                    'password' => 'password'
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['access_token'] ?? null;
        } catch (RequestException $e) {
            return null; 
        }
    }

    private function getOrdenDetallePorId($orderId, $token)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', "https://rocky-beyond-58885-df0762919b44.herokuapp.com/orders/{$orderId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
    
            $ordenDetalle = json_decode($response->getBody(), true);
            return $ordenDetalle;
        } catch (RequestException $e) {
            return null; 
        }
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
         $ordenesFiltradas = Orden::query()->byStatus($status)->byGroupId($group_id)
         ->byAmount($amount)->get();
         
         if ($ordenesFiltradas->isEmpty()) {
            return response()->json(['error' => 'No se encontraron órdenes con los filtros proporcionados'], 404);
        }
        return response()->json($ordenesFiltradas);         
    }
    //Requerimiento D
    public function totalOrdenes(){

        $cantidadOrdenes = Orden::count(); 
        $sumaMontos = Orden::sum('amount'); 
        $response = [
            'cantidad_total_ordenes' => $cantidadOrdenes,
            'suma_montos_ordenes' => $sumaMontos,
        ];
        return response()->json($response);
    }
     //Requerimiento E
    public function guardarTodasOrdenes($orderId)
    {
        $token = $this->getBearerToken();

        if (!$token) { 
            return response()->json(['error' => 'No se pudo obtener Bearer Token'], 500);
        }
        $ordenDetalle = $this->getOrdenDetallePorId($orderId, $token);
        if (Orden::find($ordenDetalle['order']['id'])) return response()->json(['mensaje'=>'La orden ya existe.']); 
        
        $this->createOrden($ordenDetalle); 
        $response = [
            'orden' => $ordenDetalle,
            'message' =>'Detalle de la orden obtenido y creada correctamente.'
        ];
        return response()->json($response);
    }
    //Requerimiento F
    public function eliminarOrden($id){
          $orden=Orden::find($id);
          if (!$orden) {
            return response()->json(['error' => 'No existe la orden '.$id], 500);
          }
          $orden->delete();
          $response = [
              'message' => 'Orden '.$id.' eliminada'
          ];
          return response()->json($response); 
          
    }
}
