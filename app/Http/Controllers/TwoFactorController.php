<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;


class TwoFactorController extends Controller
{
    public function index(Request $request)
    {

        $google2fa = new Google2FA();

        $user = auth()->user();


        if($user->google2fa_verified == true) {
            $data = ['verified' => true];
        } else {
            // Generate a secret key
            $secretKey = $google2fa->generateSecretKey();

            // Save it to the user
            $user->update([
              'google2fa_secret' => $secretKey,
            ]);

            // Generate QR Code URL
            $QRImage = $google2fa->getQRCodeInline(
              config('app.name'),   // Application name
              auth()->user()->email, // User's email
              $secretKey            // Secret key
            );

            $data = ['secret' => $secretKey, 'qr' => $QRImage, 'verified' => false];

        }
        
        return view("twofactor.setup", $data);
    }

    public function verify(Request $request)
    {
        $google2fa = new Google2FA();

        $user = auth()->user();
        $data = $request->all();

        if ($google2fa->verifyKey($user->google2fa_secret, $data['code'])) {
            $user->google2fa_verified = true;
            $user->save();
            $return = ['success' => true, 'message' => '2FA Verification completed!'];
        } else {
            $return= ['success' => false, 'message' => 'Invalid Verificaton Code!'];
        }

        return response()->json($return);
    }
}
