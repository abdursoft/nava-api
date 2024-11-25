<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'file' => 'file|mimes:jpeg,jpg,png,webp,webm,mp4,mp3,wav,aac',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Sender and Receiver id required',
            ], 400);

            try {
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $file_name = Storage::disk('public')->put('conversations',$file);
                }

                if(!empty($request->message) || $request->hasFile('file')){
                    Conversation::create([
                        'file' => $file_name ?? Null,
                        'file_type' => $extension ?? Null,
                        'message' => $request->message ?? Null,
                        'sender_id' => $request->header('id'),
                        'receiver_id' => $request->user_id
                    ]);
                    return response()->json([
                        'code' => 'MESSAGE_SENT',
                        'message' => 'Message successfully sent'
                    ],201);
                }else{
                    return response()->json([
                        'code' => 'INVALID_DATA',
                        'message' => 'Message Or File have to fill up'
                    ],400);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'code' => 'INTERNAL_SERVER_ERROR',
                    'message' => $th->getMessage(),
                ], 400);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id=null)
    {
        if($id === null){
            $conversation_receiver = Conversation::where('sender_id', $request->header('id'))->orWhere('receiver_id',$request->header('id'))->distinct()->get('receiver_id')->toArray();
            $conversation_sender = Conversation::where('sender_id', $request->header('id'))->orWhere('receiver_id',$request->header('id'))->distinct()->get('sender_id')->toArray();
            foreach($conversation_sender as $sender){
                if(!in_array($sender,$conversation_receiver)){
                    $conversation_receiver[] = $sender;
                }
            }
            $persons = [];
            foreach($conversation_receiver as $person){
                $persons[] = User::with('activeStatus')->find($person);
            }
            return response()->json([
                'code' => 'CONVERSATION_LIST',
                'message' => 'Conversation list successfully retrieved',
                'conversation' => $persons
            ],200);
        }else{
            $message = Conversation::where('sender_id', $request->header('id'))->orWhere('receiver_id',$request->header('id'))->orWhere()->get();
            return response()->json([
                'code' => 'CONVERSATION_LIST',
                'message' => 'User conversation list successfully retrieved',
                'conversation' => $message
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        //
    }
}
