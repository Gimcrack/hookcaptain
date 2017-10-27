<?php

namespace App\Http\Controllers;

use App\Hook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HookController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $data = Hook::all();

        return response()->json( $data, 200 );
    }


    /**
     * @param $hook
     *
     * @return JsonResponse
     */
    public function show( $hook )
    {
        return response()->json( $hook, 200 );
    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function store( Request $request )
    {
        $hook = new Hook( $request->validate( [
            'name' => 'required',
            'description' => 'required',
            'ttl' => 'required|integer',
        ] ) );

        $hook->save();

        return response( [], 201 );
    }


    /**
     * @param Hook    $hook
     * @param Request $request
     *
     * @return Response
     */
    public function update( Hook $hook, Request $request )
    {
        $hook->update( $request->only( [
            'name',
            'description',
            'ttl',
        ] ) );

        return response( [], 202 );
    }


    /**
     * @param Hook $hook
     *
     * @return Response
     */
    public function destroy( Hook $hook )
    {
        $hook->delete();

        return response( [], 202 );
    }
}
