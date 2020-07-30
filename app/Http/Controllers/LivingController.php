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

        $totalSpentRequiredItems = 0;

        foreach ($requiredItems as $item) {
            $totalSpentRequiredItems += $item['amount'];
        }

        $living = new Living();
        $living->datetime = $datetime;
        $living->target_budget = $targetBudget;
        $living->total_spent = $totalSpentRequiredItems;
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

    public function apiDestroy($id)
    {
        Living::destroy($id);
        return response()->json(['status' => 1], 200);
    }

    public function apiUpdate(Request $request, $id)
    {
        $living = Living::find($id);
        
        $targetBudget = $request->get('targetBudget');
        $totalSpent = $request->get('totalSpent');

        if ($targetBudget) {
            $living->target_budget = $targetBudget;
        }

        if ($totalSpent) {
            $living->total_spent = $totalSpent;
        }

        $living->save();

        return response()->json($living, 200);
    }

    public function apiDuplicate(Request $request, $id)
    {
        $datetime = $request->get('datetime');

        $living = Living::find($id);
        $items = LivingItem::where(['living_id' => $id, 'is_required' => 1])->get();

        $totalSpentRequiredItems = 0;
        
        foreach ($items as $item) {
            $totalSpentRequiredItems += $item->amount;
        }

        $newLiving = new Living();
        $newLiving->datetime = $datetime;
        $newLiving->target_budget = $living->target_budget;
        $newLiving->total_spent = $totalSpentRequiredItems;
        $newLiving->save();

        foreach ($items as $item) {
            $livingItem = new LivingItem();
            $livingItem->living_id = $newLiving->id;
            $livingItem->name = $item->name;
            $livingItem->amount = $item->amount;
            $livingItem->is_required = $item->is_required;
            $livingItem->save();
        }

        $data = Living::find($id);
        $data->items;

        return response()->json($data, 200);
    }
}
