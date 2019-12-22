<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Module;

class ModulesController extends Controller
{
    function getActiveModules()
    {
        try {

            $modules = [];

            $results = Module::where([
                ['parent_id', '=', NULL],
                ['is_active', '=', 1],
            ])->get();

            foreach ($results as $result) {
                $children = Module::where('parent_id', $result->id)->get();
                $push = [];
                foreach ($children as $child) {
                    array_push($push, $child->getAttributes());
                }
                $result->children = $push;
                array_push($modules, $result->getAttributes());
            }

            return response()->json([
                'status' => 'success',
                'data' => $modules
            ]);

        } catch(\Exception $ex) {

        }

    }

    function getAllModules()
    {
        try {
            $results = Module::where('parent_id', NULL)->get();
            $modules = [];
            foreach ($results as $result) {
                array_push($modules, $result->getAttributes());
            }

            return response()->json([
                'status' => 'success',
                'data' => $modules
            ], 200);

        } catch (\Exception $ex) {

            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
                'details' => 'File - ' . $ex->getFile() . ' Line - ' . $ex->getLine()
            ], 500);

        }
    }

    function updateModules(Request $request)
    {
        $values = $request->all();

        foreach ($values as $value) {
            $module = Module::where('id', $value['id'])->first();
            $module->is_active = $value['value'];
            $module->save();
        };

        return response()->json([
            'status' => 'success',
            'message' => 'Updated'
        ], 200);
    }
}
