<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    /*  public function getTables()
    {
        $platform = DB::getDoctrineSchemaManager()->getDatabasePlatform();
        try {
            $platform->registerDoctrineTypeMapping('enum', 'string');
        } catch (DBALException $e) {}
        $tables = DB::select('SHOW TABLES');
        $arr = [];
        foreach ($tables as $t) {
            $columns = [];
            foreach (DB::getSchemaBuilder()->getColumnListing($t->Tables_in_crm) as $c) {
                try {
                    $type = DB::getSchemaBuilder()->getColumnType($t->Tables_in_crm, $c);
                } catch (DBALException $e) {
                    $type = '';
                }
                $columns[] = [
                    'name' => $c,
                    'type' =>  $type
                ];
            }
            $arr[$t->Tables_in_crm] = $columns;
        }
        return response()->json((array) $arr, 200);
    }*/

    public function sql_query(Request $request)
    {
        $data = DB::select($request->q);
        return response()->json($data, 200);
    }

    public function php_query(Request $request)
    {
        eval($request->q);
        /** @var mixed $data */
        return response()->json($data, 200);
    }
}
