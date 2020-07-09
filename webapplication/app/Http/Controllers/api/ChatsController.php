<?php

namespace App\Http\Controllers\api;

use App\Chat;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ChatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $id = auth('api')->user()->id;
      return  User::where('id', '!=' , $id)->get(['name' , 'email' , 'id']);
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
         $id = auth('api')->user()->id;
         Chat::create([
        'sender_id' => $id,
        'sender_status' => 1,
        'reciever_id' => $request->input('reciever_id'),
        'reciever_status' => 0,
        'message' => $request->input('message'),
        ]);
        return ['message sent'];
    }
  public function getMessage(Request $request){
    $sender_id = auth('api')->user()->id;
    // return Chat::where('sender_id', '=' , $sender_id)
    // ->where('reciever_id', '=' , $request->input('reciever_id'))->get();
   $reciever_id= $request->input('reciever_id');
   $resulted = DB::table('chats')
            ->where(function ($query) use ($sender_id, $request) {
                $query->where('sender_id', '=' , $sender_id)
                ->where('reciever_id', '=' , $request->input('reciever_id'))->get();
            })
            ->orWhere(function ($query) use ($sender_id, $request) {
                $query->where('reciever_id', '=' , $sender_id)
                ->where('sender_id', '=' , $request->input('reciever_id'))->get();
            })->get();
$status_check = DB::table('chats')
            ->where(function ($query) use ($sender_id, $request) {
                $query->where('sender_id', '=' , $sender_id)
                ->where('reciever_id', '=' , $request->input('reciever_id'))->get();
            })
            ->orWhere(function ($query) use ($sender_id, $request) {
                $query->where('reciever_id', '=' , $sender_id)
                ->where('sender_id', '=' , $request->input('reciever_id'))->get();
            })->orderBy('id', 'DESC')->first();
    if($status_check){      
    if($status_check->sender_id != $sender_id){
        Chat::where('id' , $status_check->id)->update(['reciever_status' => '1']);
    }
    }
    return $resulted;
            
  }
  public function getDetail(Request $request){
      $data['my_id'] = auth('api')->user()->id;
      $data['current_name'] =  User::where('id', $request->input('reciever_id'))->first()->name;
      $chat_check = Chat::where('sender_id', '=' , $data['my_id'])
      ->where('reciever_id', '=' , $request->input('reciever_id'))->orderBy('id', 'DESC')->first();
      if($chat_check){
        $data['current_status'] = $chat_check->reciever_status;
      }
      $data['current_status'] = 0;
    return $data;
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
}
