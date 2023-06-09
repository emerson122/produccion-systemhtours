<?php

namespace App\Http\Controllers\cuentas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CuentasController extends Controller
{

    protected $url = 'http://localhost:3000';
    /**
     * Función mostrar la pantalla de cuentas
     */
    public function ver()
    {
        /**
         * Seguridad de roles y perimisos metodo GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CUENTAS"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $consultar = $key['PER_CONSULTAR'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Periodo 21';
        }

        if ($consultar == '1') {
            try {
                $cuentas = http::withToken(Cache::get('token'))->get($this->url . '/cuentas');
                $personArr = $cuentas->json();
                //clasificacion
                $clasificacion = http::withToken(Cache::get('token'))->get($this->url . '/clasificacion');
                $clasificacionArr = $clasificacion->json();
                //grupos
                $grupos = http::withToken(Cache::get('token'))->get($this->url . '/grupos');
                $gruposArr = $grupos->json();
            } catch (\Throwable $th) {
                //throw $th;
                return 'error periodo 22';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA METODO GET',
                    "DES" => Cache::get('user') . ' INGRESO A LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 32';
            }
        } else {
            $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                "USR" => Cache::get('user'),
                "ACCION" => 'INTENTO FALLIDO METODO GET',
                "DES" => Cache::get('user') . 'INTENTO INGRESAR A LA PANTALLA DE CUENTAS Y LEER DATOS',
                "OBJETO" => 'CUENTAS'
            ]);
            return view('Auth.no-auth');
        }

        return view('cuentas.cuentas', compact('personArr', 'clasificacionArr', 'gruposArr'));
    }

    /**
     * Método para insertar una cuenta
     */
    public function insertar(Request $request)
    {
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CUENTAS"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $insercion = $key['PER_INSERCION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Periodo 21';
        }
        if ($insercion == '1') {
            try {
                $insertar =  Http::withToken(Cache::get('token'))->post(
                    $this->url . '/cuentas/insertar',
                    [
                        "CLASIFICACION" => $request->naturaleza,
                        "NOMBRE" => $request->nombrecuenta,
                        "CORRELATIVO" => $request->numerocuenta,
                        "GRUPO" => $request->grupo
                    ]
                );
                Session::flash('insertado', "1");
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 31';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'PANTALLA METODO POST',
                    "DES" => Cache::get('user') . ' INSERTO EL DATO DE ' . $request->nombrecuenta . ' EN LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error CUENTAS 32';
            }
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO POST',
                    "DES" => Cache::get('user') . ' INTENTO INSERTAR EL DATO ' . $request->nombrecuenta . ' EN LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 32';
            }
        }
        return back();
    }

    /**
     * Método para actualizar una cuenta
     */
    public function actualizar(Request $request)
    {
        /**
         * Seguridad de roles y permisos método GET
         */

        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CUENTAS"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $update = $key['PER_ACTUALIZACION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error Periodo 21';
        }
        if ($update == '1') {
            try {
                //code...
                // return $request;
                $actualizar = Http::withToken(Cache::get('token'))->put(
                    $this->url . '/cuentas/actualizar/' . $request->f,
                    [
                        "CLASIFICACION" => $request->naturaleza,
                        "NOMBRE" => $request->numero,
                        "NUM" => $request->cuenta

                    ]
                );
                Session::flash('actualizado', "1");
            } catch (\Throwable $th) {
                //throw $th;
                return 'error periodo 50';
            }

            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'ACTUALIZO UN DATO EN PANTALLA ',
                    "DES" => Cache::get('user') . ' ACTUALIZO EL DATO DE ' . $request->cuenta . ' EN LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 32';
            }
        } else {
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO PUT',
                    "DES" => Cache::get('user') . ' INTENTO ACTUALIZAR EL DATO ' . $request->cuenta . ' EN LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error periodo 32';
            }
        }



        return back();
    }


    /**
     * Metodo para eliminar una cuenta
     */
    public function eliminar(Request $request)
    {
        /**
         * Seguridad de roles y perimisos metodo delete
         */
        // return $request;
        try {
            //code...
            $search = Http::withToken(Cache::get('token'))->post($this->url . '/permisos/sel_per_obj', [
                "PV_ROL" => Cache::get('rol'),
                "PV_OBJ" => "CUENTAS"
            ]);

            $permisos = $search->json();
            foreach ($permisos as $key) {
                $eliminacion = $key['PER_ELIMINACION'];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return 'Error CUENTAS 21';
        }

        if ($eliminacion == '1') {
            $respuesta = 0;
            try {
                $eliminar = Http::withToken(Cache::get('token'))->delete(
                    $this->url . '/cuentas/eliminar/' . $request->f
                );

                $respuesta = strrpos($eliminar, 'ESTA UTILIZADA');
                if ($respuesta > 0) {
                    Session::flash('nopuedes', "1");
                    $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                        "USR" => Cache::get('user'),
                        "ACCION" => 'SE INTENTO ELIMINAR CUENTA EN USO',
                        "DES" => Cache::get('user') . ' INTENTO ELIMINAR EL DATO  CON CÓDIGO ' . $request->f . ' EN LA PANTALLA DE CUENTAS',
                        "OBJETO" => 'CUENTAS'

                    ]);
                    return back();
                }
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error CUENTAS 250';
            }

            Session::flash('eliminado', "1");
            $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                "USR" => Cache::get('user'),
                "ACCION" => 'ELIMINACION DE CUENTA',
                "DES" => Cache::get('user') . '  ELIMINO EL DATO  CON CODIGO' . $request->f . ' EN LA PANTALLA DE CUENTAS',
                "OBJETO" => 'CUENTAS'

            ]);
        } else {
            # code...
            try {
                $bitacora = Http::withToken(Cache::get('token'))->post($this->url . '/seguridad/bitacora/insertar', [
                    "USR" => Cache::get('user'),
                    "ACCION" => 'SIN PERMISO METODO DELETE',
                    "DES" => Cache::get('user') . ' INTENTO ELIMINAR EL DATO  CON CÓDIGO ' . $request->f . ' EN LA PANTALLA DE CUENTAS',
                    "OBJETO" => 'CUENTAS'

                ]);

                Session::flash('sinpermiso', '1');
            } catch (\Throwable $th) {
                //throw $th;
                return 'Error cuenta 301';
            }
        }
        return back();
    }

    /**
     * Función para mostrar el pdf 
     */

    public function mostrarPDF()
    {
        $cuentas = http::withToken(Cache::get('token'))->get($this->url . '/catalago');
        $cuentas = $cuentas->json();
        // return $cuentas;

        return view('cuentas.cuentasPDF', compact('cuentas'));
    }
}
