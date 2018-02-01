<?php

namespace App\Http\Controllers;

use App\Gnome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GnomesController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/gnomes",
     *     description="Returns all gnomes.",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Gnomes",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/Gnome")
     *         ),
     *     )
     * )
     */
    public function getGnomes()
    {
        $gnomes = Gnome::all();
        return response()->json($gnomes);
    }

    /**
     * @SWG\Get(
     *     path="/gnome/{id}",
     *     description="Returns one specified gnome.",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of gnome to retrieve.",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Gnome",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/Gnome")
     *         ),
     *     )
     * )
     */
    public function getGnome(int $id)
    {
        $gnome = Gnome::find($id);
        return response()->json($gnome, $gnome ? 200 : 404);
    }

    /**
     * @SWG\Post(
     *     path="/gnome",
     *     description="Creates a new gnome.",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="name",
     *         in="body",
     *         description="Name of the gnome.",
     *         required=true,
     *         format="string",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Parameter(
     *         name="age",
     *         in="body",
     *         description="Age of the gnome.",
     *         required=true,
     *         format="int64",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Parameter(
     *         name="strength",
     *         in="body",
     *         description="Strength of the gnome.",
     *         required=true,
     *         format="int64",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Gnomes",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/Gnome")
     *         ),
     *     )
     * )
     */
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

    /**
     * @SWG\Put(
     *     path="/gnome/{id}",
     *     description="Updates the specified gnome.",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="ID of gnome to update.",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="name",
     *         in="body",
     *         description="Name of the gnome.",
     *         required=false,
     *         format="string",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Parameter(
     *         name="age",
     *         in="body",
     *         description="Age of the gnome.",
     *         required=false,
     *         format="int64",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Parameter(
     *         name="strength",
     *         in="body",
     *         description="Strength of the gnome.",
     *         required=false,
     *         format="int64",
     *         @SWG\Schema(ref="#/definitions/Gnome")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Gnomes",
     *         @SWG\Schema(
     *             type="json",
     *             @SWG\Items(ref="#/definitions/Gnome")
     *         ),
     *     )
     * )
     */
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

    /**
     * @SWG\Delete(
     *     path="/gnome/{id}",
     *     description="Deletes the specified gnome.",
     *     @SWG\Parameter(
     *         description="ID of gnome to delete.",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Gnome deleted."
     *     )
     * )
     */
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
