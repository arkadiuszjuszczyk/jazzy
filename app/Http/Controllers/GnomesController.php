<?php

namespace App\Http\Controllers;

use App\Gnome;
use Illuminate\Http\JsonResponse;
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
    public function getGnomes(): JsonResponse
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
     * @param int $id
     * @return JsonResponse
     */
    public function getGnome(int $id): JsonResponse
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
     *     @SWG\Parameter(
     *         name="avatar",
     *         in="body",
     *         description="Avatar of the gnome.",
     *         required=false,
     *         format="image",
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
     * @param Request $request
     * @return JsonResponse
     */
    public function createGnome(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'age', 'strength', 'avatar']);

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'age' => 'required|integer',
            'strength' => 'required|integer',
            'avatar' => 'file'
        ], [
            '*.required' => 'The field is required.',
            '*.integer' => 'The field must be an integer.',
            '*.string' => 'The field bust be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $avatarPath = isset($data['avatar']) ? $request->file('avatar')->store('avatars') : null;
        $data['avatar'] = $avatarPath;

        $gnome = Gnome::create($data);
        $gnomeId = $gnome['id'] ?? null;

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
     *     @SWG\Parameter(
     *         name="avatar",
     *         in="body",
     *         description="Avatar of the gnome.",
     *         required=false,
     *         format="image",
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
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateGnome(Request $request, int $id): JsonResponse
    {
        $data = $request->only(['name', 'age', 'strength', 'avatar']);

        $validator = Validator::make($data, [
            'name' => 'string',
            'age' => 'integer',
            'strength' => 'integer',
            'avatar' => 'file'
        ], [
            '*.integer' => 'The field must be an integer.',
            '*.string' => 'The field bust be a string.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $gnome = Gnome::find($id);

        if ($gnome) {
            $avatarPath = isset($data['avatar']) ? $request->file('avatar')->store('avatars') : null;
            $data['avatar'] = $avatarPath;

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
     * @param int $id
     * @return JsonResponse
     */
    public function deleteGnome(int $id): JsonResponse
    {
        $gnome = Gnome::find($id);

        if ($gnome) {
            $gnome->delete();
            return response()->json();
        }

        return response()->json([], 404);
    }
}
