<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {

        return view('welcome', ['settings' => $this->data()]);
    }

    public function landing()
    {
        return view('landing', ['settings' => $this->data()]);
    }

    private function data()
    {
        $settings = Setting::all();
        $arr = [];
        foreach ($settings as $s) {
            $arr[$s->name] = $s->value;
        }
        return collect($arr);
    }

    public function test()
    {
        return view('test');
    }
}
