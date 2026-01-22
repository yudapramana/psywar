<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CaptchaController
{
    public static function generate()
    {
        // Captcha berupa 5 karakter (angka + huruf besar)
        $captcha = Str::upper(Str::random(5));

        // Simpan ke session
        Session::put('login_captcha', $captcha);

        return $captcha;
    }

    public static function validate($input)
    {
        return Session::get('login_captcha') === strtoupper($input);
    }
}
