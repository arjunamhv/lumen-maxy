<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

$router->group(['prefix' => 'penjualan'], function () use ($router) {
    $router->get('/', function () {
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil menampilkan data penjualan',
            'data' => [
                [
                    'id' => 1,
                    'nama' => 'Buku Tulis',
                    'harga' => 5000,
                    'qty' => 10,
                    'total' => 50000
                ],
                [
                    'id' => 2,
                    'nama' => 'Buku Gambar',
                    'harga' => 7000,
                    'qty' => 5,
                    'total' => 35000
                ],
                [
                    'id' => 3,
                    'nama' => 'Pensil',
                    'harga' => 2000,
                    'qty' => 20,
                    'total' => 40000
                ]
            ]
        ]);
    });
    $router->get('/{id}', function ($id) {
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil menampilkan data penjualan dengan id ' . $id,
            'data' => [
                'id' => $id,
                'nama' => 'Buku Tulis',
                'harga' => 5000,
                'qty' => 10,
                'total' => 50000
            ]
        ]);
    });
    $router->post('/', function (Request $request) {
        $id = 4;
        $nama = $request->input('nama');
        $harga = $request->input('harga');
        $qty = $request->input('qty');
        $total = $harga * $qty;
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil menambahkan data penjualan',
            'data' => [
                'id' => $id,
                'nama' => $nama,
                'harga' => $harga,
                'qty' => $qty,
                'total' => $total
            ]
        ]);
    });
    $router->put('/{id}', function (Request $request, $id) {
        $nama = $request->input('nama');
        $harga = $request->input('harga');
        $qty = $request->input('qty');
        $total = $harga * $qty;
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil mengubah data penjualan dengan id ' . $id,
            'data' => [
                'id' => $id,
                'nama' => $nama,
                'harga' => $harga,
                'qty' => $qty,
                'total' => $total
            ]
        ]);
    });
    $router->delete('/{id}', function ($id) {
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil menghapus data penjualan dengan id ' . $id
        ]);
    });

    //auth
    $router->get('/{id}/confirm', function (Request $request, $id) {
        $apiKey = $request->header('api_key');
        $user = app('db')->table('users')->where('api_key', $apiKey)->first();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil konfirmasi penjualan dengan id ' . $id . ' oleh ' . $user->username
        ]);
    });
    $router->get('/{id}/send-email', function (Request $request, $id) {
        $apiKey = $request->header('api_key');
        $user = app('db')->table('users')->where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized'
            ]);
        }

        $email = $user->email;
        $messageContent = "Penjualan dengan ID: {$id} telah berhasil dikonfirmasi.";

        Mail::raw($messageContent, function ($message) use ($email) {
            $message->to($email);
            $message->subject('Konfirmasi Penjualan');
        });

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil mengirim email konfirmasi penjualan dengan id ' . $id . ' ke ' . $email
        ]);
    });
});
