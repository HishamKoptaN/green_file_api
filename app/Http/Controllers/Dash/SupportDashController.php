<?php

namespace App\Http\Controllers\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Message;


class SupportDashController extends Controller
{
    public function handleRequest(
        Request $request,
        $id = null,
    ) {
        switch ($request->method()) {
            case 'GET':
                if (!$id) {
                    return $this->getChats(
                        $request,
                    );
                } else {
                    return $this->getMsgs(
                        $request,
                        $id,
                    );
                }
            case 'POST':
                return $this->sendMsg(
                    $request,
                );

            default:
                return $this->failureResponse();
        }
    }
    protected function getChats(Request $request)
    {
        try {
            $chats = Chat::with(['user:id,name,image'])
                ->withCount(
                    [
                        'messages as unread_messages_count' => function ($query) {
                            $query->whereNull('readed_at')
                                ->whereColumn('messages.user_id', '=', 'chats.user_id');
                        },
                    ],
                )
                ->orderBy('updated_at', 'desc')
                ->get();
            return successResponse(
                $chats
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }
    protected function getMsgs(
        Request $request,
        $id,
    ) {

        try {
            $chat = Chat::where('id', $id)->first();
            if (!$id) {
                return response()->json([
                    'status' => false,
                    'error' => 'chat_id is required'
                ], 400);
            }
            $messages = Message::where('chat_id', $id)->orderBy('created_at', 'desc')->get();
            foreach ($messages as $message) {
                if (is_null($message->readed_at) && $message->user_id === $chat->user_id) {
                    $message->readed_at = now();
                    $message->save();
                }
            }
            return successResponse(
                $messages
            );
        } catch (\Exception $e) {
            return failureResponse(
                $e->getMessage(),
            );
        }
    }

    protected function sendMsg(
        Request $request,
    ) {
        try {
            // التحقق من المدخلات
            $request->validate([
                'id' => 'required|integer|exists:chats,id',
                'message' => 'required_without:file|string',
                'file' => 'required_without:message|file|max:2048',
            ]);

            $user = Auth::guard('sanctum')->user();
            $chat = Chat::find($request->id);

            // إذا كان هناك ملف مرفق
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                if ($file->isValid()) {
                    // رفع الملف والحصول على الرابط
                    $fileUrl = uploadImage($file, 'chat_files');

                    // إنشاء الرسالة مع معلومات الملف
                    $message = Message::create([
                        'message' => null, // يمكن أن تكون null إذا كان الملف فقط
                        'user_id' => $user->id,
                        'chat_id' => $chat->id,
                        'is_file' => true,
                        'file_name' => $fileUrl,
                        'file_original_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientOriginalExtension(),
                    ]);
                } else {
                    return failureResponse('Uploaded file is not valid', 400);
                }
            }

            // إذا كان هناك رسالة نصية
            if ($request->filled('message')) {
                $messageText = $request->input('message');
                $message = Message::create([
                    'message' => $messageText,
                    'user_id' => $user->id,
                    'chat_id' => $chat->id,
                    'is_file' => false,
                ]);
            }
            $chat->load('messages');
            return successResponse($message);
        } catch (\Exception $e) {
            return failureResponse($e->getMessage(), 500);
        }
    }
}
