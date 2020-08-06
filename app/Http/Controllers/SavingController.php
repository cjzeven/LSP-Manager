<?php

namespace App\Http\Controllers;

use App\Saving;
use App\SavingItem;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('saving.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function apiCreate(Request $request)
    {
        $saving = Saving::create($request->all());
        return response()->json($saving, 200);
    }

    public function apiAll()
    {
        $savings = Saving::with('items')->get();
        return response()->json($savings, 200);
    }

    public function apiFind($id)
    {
        $saving = Saving::find($id);
        $saving->items;
        return response()->json($saving, 200);
    }

    public function apiCreateItem(Request $request, $id)
    {
        $items = SavingItem::create($request->all());
        return response()->json($items, 200);
    }
}
