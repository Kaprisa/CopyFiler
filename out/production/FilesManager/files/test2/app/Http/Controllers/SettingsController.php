<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getAll()
    {
        $settings = Setting::all();
        return response()->json($settings, 200);
    }

    public function save(Request $request)
    {
        $check = $request->has('id') ? [ 'id' => $request->id ] : [ 'name' => $request->name ];
        $setting = Setting::updateOrCreate(
           $check,
           [
               'name' => $request->name,
               'value' => $request->value,
               'field_type' => $request->field_type
           ]
       );
       return response()->json($setting, 200);
    }

    public function delete($id)
    {
        Setting::destroy($id);
        return null;
    }
}
