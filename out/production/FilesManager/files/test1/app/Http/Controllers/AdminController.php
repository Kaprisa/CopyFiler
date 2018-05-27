<?php

namespace App\Http\Controllers;

use App\Field;
use App\Section;
use DB;
use Doctrine\DBAL\DBALException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return null
     */
    public function add(Request $request)
    {
        $s = Section::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'page_id' => $request->page,
            'langs' => $request->langs,
            'color' => $request->color,
            'validation' => $request->validation
        ]);

        foreach ($request->fields as $i => $f) {
            $id = array_has($f, 'id') && is_int($f['id']) ? $f['id']
                  : Field::Create([
                    'name' => $f['name'],
                    'type' => $f['type'],
                    'relationship' => $f['relationship'],
                    'icon' => $f['icon']
                ])->id;
            $s->fields()->attach($id, ['sort' => DB::table('field_section')->max('sort') + 1]);
        }
        return null;
        //Storage::put('/sections/'.$s->name.'.js', $request->cases."\n\n\n".$request->lang);
        //return response()->json($request->fields, 200);
    }

    public function getTables()
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
    }

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
