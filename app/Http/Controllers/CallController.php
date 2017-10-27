<?php

namespace App\Http\Controllers;

use App\Call;
use App\Hook;
use App\Http\Requests\NewCallRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( Call::all(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param NewCallRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( NewCallRequest $request )
    {

        $hook = $request->getHook();

        $hook->call( $request->all() );

        return response()->json([
            'hook' => $hook,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        return response()->json($call,200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call)
    {
        $call->update([
            'payload' => $request->all()
        ]);

        return response()->json([],202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call)
    {
        $call->delete();

        return response()->json([],202);
    }
}
