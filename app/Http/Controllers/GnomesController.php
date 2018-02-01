<?php

namespace App\Http\Controllers;

use App\Gnome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GnomesController extends Controller
{
    public function getGnomes()
    {
        $gnomes = Gnome::all();
        return response()->json($gnomes);
    }

    public function getGnome(int $id)
    {
        $gnome = Gnome::find($id);
        return response()->json($gnome, $gnome ? 200 : 404);
    }

    public function createGnome(Request $request)
    {
        $data = $request->only(['name', 'age', 'strength']);

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'age' => 'required|integer',
            'strength' => 'required|integer'
        ], [
            '*.required' => 'The field is required.',
            '*.integer' => 'The field must be an integer.',
            '*.string' => 'The field bust be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $gnome = Gnome::create($data);
        $gnomeId = $gnome->id ?? null;

        return response()->json(['id' => $gnomeId], 201);
    }

    public function updateGnome(Request $request, int $id)
    {
        $data = $request->only(['name', 'age', 'strength']);

        $validator = Validator::make($data, [
            'name' => 'string',
            'age' => 'integer',
            'strength' => 'integer'
        ], [
            '*.integer' => 'The field must be an integer.',
            '*.string' => 'The field bust be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $gnome = Gnome::find($id);

        if ($gnome) {
            $gnome->update($data);
            return response()->json();
        }

        return response()->json([], 404);
    }

    public function deleteGnome(int $id)
    {
        $gnome = Gnome::find($id);

        if ($gnome) {
            $gnome->delete();
            return response()->json();
        }

        return response()->json([], 404);
    }
}
