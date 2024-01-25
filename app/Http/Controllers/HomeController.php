<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    /** 
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        info($request->token);
        auth()->user()->update(['device_token' => $request->token]);
        return response()->json(['status' => true,  'message' => 'token saved successfully.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = config('app.firebase_server_key');

        // dd($firebaseToken, $SERVER_API_KEY);

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);

        return response()->json(['status' => true,  'message' => 'token saved successfully.', 'response' => $response]);
    }
}
