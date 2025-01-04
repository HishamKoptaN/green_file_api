<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class DashSupportController extends Controller
{
    public function index(Request $request, $chat_id = null)
    {
        $chat = null;
        if ($chat_id) {
            $chat = Chat::where('id', $chat_id)
                ->with(['user', 'messages'])
                ->firstOrFail();
            $chat->messages()->update(
                [
                    'readed_at' => now()
                ],
            );
        }
        $chats = Chat::withCount('has_unreaded_message')
            ->with(['user'])
            ->where('admin_id', Auth::id())
            ->get();

        return response()->json([
            'status' => true,
            'chats' => $chats,
            'chat' => $chat
        ]);
    }

    public function getChatsByAdmin(Request $request)
    {
        $adminId = $request->input('admin_id');
        if (!$adminId) {
            return response()->json([
                'status' => false,
                'error' => 'admin_id is required'
            ], 400);
        }

        $chats = Chat::with(['user', 'messages'])
            ->where('admin_id', $adminId)
            ->get();

        return response()->json([
            'status' => true,
            'chats' => $chats
        ]);
    }
    public function index(Request $request, $chat_id = null)
    {
        $chat = null;

        if ($chat_id) {
            $chat = Chat::where('id', $chat_id)
                ->with(['user', 'messages'])
                ->firstOrFail();
            $chat->messages()->update([
                'readed_at' => now()
            ]);
        }
        $chats = Chat::withCount('has_unreaded_message')
            ->with(['user'])
            ->where('admin_id', Auth::id())
            ->get();

        return response()->json([
            'status' => true,
            'chats' => $chats,
            'chat' => $chat
        ]);
    }
}
