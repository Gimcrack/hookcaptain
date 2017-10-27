<?php

namespace App\Http\Controllers;

use App\Hook;
use Illuminate\Http\Request;

class HookCallController extends Controller
{
    /**
     * Get all the calls for a hook
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Hook $hook)
    {
        return response()->json( $hook->calls,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Hook                      $hook
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Hook $hook, Request $request)
    {
        $hook->call($request->all());

        return response([],201);
    }
}
