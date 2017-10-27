<?php

function create($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = [];
    }
    return ($qty > 1) ?
        factory($what, $qty)->create($overrides)->map->fresh() :
        factory($what)->create($overrides)->fresh();
}

function create_array($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = [];
    }
    return ($qty > 1) ?
        factory($what, $qty)->create($overrides)->map->fresh()->map->toArray()->all() :
        factory($what)->create($overrides)->fresh()->toArray();
}


function make($what, $overrides = [], $qty = 1)
{
    if (is_numeric($overrides)) {
        $qty = $overrides;
        $overrides = null;
    }
    return ($qty > 1) ?
        factory($what, $qty)->make($overrides) :
        factory($what)->make($overrides);
}


