<?php

namespace App\Http\Controllers\bitacoras;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class BitacoraController extends Controller
{

    protected $url = 'http://localhost:3000';

    /**
     * Función mostrar retorna la vista principal de la pantalla de bitacora
     */

    public function mostrar()
    {
     
        
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "BITACORAS"
            ]);
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de permisos y roles: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }
        
            $permisos = $search->json();
            $consultar = 0;
            foreach ($permisos as $key) {
                $consultar = $key['PER_CONSULTAR'];
            }
        if ( $consultar == '1') {
            try {

                $bitacora = http::withToken(Cache::get('token'))->get($this->url . '/seguridad/sel_bitacora');
                //return $bitacora;
                $bitacoraArr = $bitacora->json();

            } catch (\Exception $e) {
                Log::debug('Error procedimiento de seleccionar bitacora: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }




            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA ROLES METODO GET',
                    "DES" => Cache::get('user') . ' INGRESO A LA PANTALLA DE BITACORAS',
                    "OBJETO" => 'BITACORAS'
                ]);

            } catch (\Exception $e) {
                Log::debug('Error procedimiento de insertar bitacora: '.$e->getMessage());
                Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
                return view('Bitacoras.error');
            }
        } else {
            $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                "USR" => Cache::get('user'),
                "ACCION" => 'ACCESO NO AUTORIZADO ROLES ',
                "DES" => Cache::get('user') . ' INTENTO INGRESAR A LA PANTALLA DE BITACORAS',
                "OBJETO" => 'BITACORAS'
            ]);
            return view('Auth.no-auth');
        }
        return view('Bitacoras.bitacoras', compact('bitacoraArr'));
    }

    /**
     * Función para generar el pdf de bitacora 
     */
    public function pdf()
    {
        try {
            //code...
            $bitacora = http::withToken(Cache::get('token'))->get($this->url . '/seguridad/sel_bitacora');
            //return $bitacora;
            $bitacoraArr = $bitacora->json();
        } catch (\Exception $e) {
            Log::debug('Error procedimiento de seleccionar bitacora: '.$e->getMessage());
            Session::flash('error', 'ERR-001 Ocurrió un error de conexión');
            return view('Bitacoras.error');
        }

        return view('Bitacoras.bitacoraspdf', compact('bitacoraArr'));
    }
}
