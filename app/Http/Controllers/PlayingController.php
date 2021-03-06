<?php

namespace App\Http\Controllers;

use App\Playing;
use App\PlayingItem;
use Illuminate\Http\Request;

class PlayingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('playing.index');
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
        $playing = Playing::create($request->all());
        return response()->json($playing, 200);
    }

    public function apiAll()
    {
        $playings = Playing::with('items')->get();

        return response()->json($playings, 200);
    }

    public function apiFind($id)
    {
        $playing = Playing::find($id);
        $playing->items;
        return response()->json($playing, 200);
    }

    public function apiCreateItem(Request $request, $id)
    {
        $playingItem = PlayingItem::create($request->all());

        if ($request->file('file')) {
            $upload = (new HomeController())->apiUploadFile($request, 'playing');
            if ($upload->original) {
                if ($upload->original['status'] === 1) {
                    $playingItem->receipt_photo = $upload->original['image'];
                    $playingItem->save();
                }
            }
        }

        return response()->json($playingItem, 200);
    }

    public function apiDeleteItem($id)
    {
        PlayingItem::destroy($id);
        return response()->json(['status' => 1], 200);
    }

    public function apiDestroy($id)
    {
        Playing::destroy($id);
        return response()->json(['status' => 1], 200);
    }
}
