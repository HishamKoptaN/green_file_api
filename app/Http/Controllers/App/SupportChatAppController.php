<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\File;

class SupportChatAppController extends Controller
{
    public function handleRequest(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return $this->getMessages(
                    $request,
                );
            case 'POST':
                return $this->sendMessage(
                    $request,
                );
            case 'PUT':
                return $this->sendFile(
                    $request,
                );
            default:
                return response()->json(['status' => false, 'message' => 'Invalid request method'], 405);
        }
    }
    public function getMessages(Request $request)
    {
        try {
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }
            $chat = Chat::where('user_id', $user->id)->firstOrCreate([
                'admin_id' => User::where('id', 3)->first()->id ?? null,
                'user_id' => $user->id
            ]);
            $messages = Message::where('chat_id', $chat->id)->orderBy('created_at', 'desc')->get();
            foreach ($messages as $message) {
                if (is_null($message->readed_at) && $message->user_id != $user->id) {
                    $message->readed_at = now();
                    $message->save();
                }
            }
            return response()->json([
                'status' => true,
                'messages' => $messages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function sendMessage(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
        $chat = Chat::where('user_id', $user->id)->first();
        if (!$chat) {
            $chat = Chat::create(
                [
                    'admin_id' => User::where('id', 3)->first()->id ?? null,
                    'user_id' => $user->id
                ],
            );
        }
        if ($request->hasFile('message')) {
            $file = $request->file('message');
            if ($file->isValid()) {
                $name = strtolower(Str::random(10)) . '-' . str_replace([' ', '_'], '-', $file->getClientOriginalName());
                $destinationPath = public_path('images/chat_files/');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $name);
                Message::create([
                    'message' =>  "https://aquan.aquan.website/api/show/image/chat_files/$name",
                    'user_id' => $user->id,
                    'chat_id' => $chat->id,
                    'is_file' => 'yes',
                    'file_name' => $name,
                    'file_original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),

                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Uploaded file is not valid',
                ], 400);
            }
        } else {
            $messageText = $request->input('message');
            if (empty($messageText)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Message text is required',
                ], 400);
            }
            Message::create([
                'message' => $messageText,
                'user_id' => $user->id,
                'chat_id' => $chat->id,
            ]);
        }
        $chat->touch();
        $chat->load('messages');
        return response()->json(
            [
                'status' => true,
                'messages' => $chat->messages,
                'user' => $user
            ],
        );
    }
}
