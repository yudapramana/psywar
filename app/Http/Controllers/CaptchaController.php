<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generate()
    {
        // 🔒 JANGAN generate ulang jika masih ada
        if (! session()->has('captcha_code')) {
            $code = strtoupper(substr(
                str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
                0,
                5
            ));

            session()->put('captcha_code', $code);
        } else {
            $code = session('captcha_code');
        }

        $img = imagecreate(120, 40);

        $bg   = imagecolorallocate($img, 245, 247, 250);
        $txt  = imagecolorallocate($img, 30, 41, 59);
        $line = imagecolorallocate($img, 180, 180, 180);

        for ($i = 0; $i < 6; $i++) {
            imageline(
                $img,
                rand(0, 120),
                rand(0, 40),
                rand(0, 120),
                rand(0, 40),
                $line
            );
        }

        imagestring($img, 5, 22, 12, $code, $txt);

        ob_start();
        imagepng($img);
        $imageData = ob_get_clean();

        imagedestroy($img);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }


}
