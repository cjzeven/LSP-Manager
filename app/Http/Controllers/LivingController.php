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
        $living = Living::find($id);
        $living->items;
        return response()->json($living, 200);
    }

    public function apiPaid($id)
    {
        $livingItem = LivingItem::find($id);
        $livingItem->paid = !(int) $livingItem->paid;
        $livingItem->save();
        return response()->json($livingItem, 200);
    }

    public function apiCreateItem(Request $request, $id)
    {
        $name = $request->get('name');
        $amount = $request->get('amount');
        $paid = $request->get('paid');
        $isRequired = $request->get('isRequired');
        $receiptPhoto = $request->get('receiptPhoto');

        $item = new LivingItem();
        $item->living_id = $id;
        $item->name = $name;
        $item->amount = $amount;
        $item->paid = $paid;
        $item->is_required = $isRequired;
        $item->receipt_photo = $receiptPhoto;
        $item->save();

        return response()->json($item, 200);
    }

    public function apiUpdateItem(Request $request, $id)
    {
        $name = $request->get('name');
        $amount = $request->get('amount');
        $paid = $request->get('paid');
        $isRequired = $request->get('isRequired');
        $receiptPhoto = $request->get('receiptPhoto');

        $item = LivingItem::find($id);

        if ($name) {
            $item->name = $name;
        }
        if ($amount) {
            $item->amount = $amount;
        }
        if ($paid) {
            $item->paid = $paid;
        }
        if ($isRequired) {
            $item->is_required = $isRequired;
        }
        if ($receiptPhoto) {
            $item->receipt_photo = $receiptPhoto;
        }

        $item->save();

        return response()->json($item, 200);

    }

    public function apiDeleteItem($id)
    {
        $item = LivingItem::destroy($id);
        return response()->json(['status' => 1], 200);
    }
}
