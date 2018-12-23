<?php

namespace App\Http\Controllers;

use App\Entity;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use JWTAuth;

class EntityController extends Controller
{
    public function getEntities(Request $request)
    {
        $entities = Entity::where('user_id', $request->user->id)->get()->toArray();

        return response()->json($entities);
    }

    public function createEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toArray(), 400);
        }

        $entity = new Entity();
        $entity->user_id = $request->user->id;
        $entity->name = $request->name;
        $entity->save();

        return response()->json($entity);
    }

    public function updateEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'id' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toArray(), 400);
        }

        $entity = Entity::where('id', $request->id)->where('user_id', $request->user->id)->first();

        if(empty($entity)) {
            return response()->json('No entity found', 404);
        }

        $entity->name = $request->name;
        $entity->save();

        return response()->json($entity);
    }

    public function deleteEntity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toArray(), 400);
        }

        $entity = Entity::where('id', $request->id)->where('user_id', $request->user->id)->first();

        if(empty($entity)) {
            return response()->json('Could not find entity to delete', 404);
        }

        $entity->delete();

        return response()->json();
    }
}
