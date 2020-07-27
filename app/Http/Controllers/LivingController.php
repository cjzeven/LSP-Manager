<?php

namespace App\Http\Controllers;

use App\Living;
use App\LivingItem;
use Illuminate\Http\Request;

class LivingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('living.index');
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

    public function apiAll()
    {
        $livings = Living::all();
        return response()->json($livings, 200);
    }

    public function apiCreate(Request $request)
    {
        $datetime = $request->get('datetime');
        $requiredItems = $request->get('requiredItems');
        $targetBudget = $request->get('targetBudget');

        $living = new Living();
        $living->datetime = $datetime;
        $living->target_budget = $targetBudget;
        $living->save();

        foreach ($requiredItems as $item) {
            $livingItem = new LivingItem();
            $livingItem->living_id = $living->id;
            $livingItem->name = $item['name'];
            $livingItem->amount = $item['amount'];
            $livingItem->paid = $item['paid'];
            $livingItem->is_required = 1;
            $livingItem->receipt_photo = $item['receiptPhoto'];
            $livingItem->save();
        }

        return response()->json(['status' => 1], 200);
    }

    public function apiFind($id)
    {
        $living = LivingItem::where(['living_id' => $id])->get();
        return response()->json($living, 200);
    }
}
