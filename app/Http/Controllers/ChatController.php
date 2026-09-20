<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getAdmins()
    {
        $user = Auth::user();
        
        // Get admins from same municipality
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->select('id', 'full_name', 'municipality')
            ->get();

        if ($admins->isNotEmpty()) {
            $unreadByAdmin = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->whereIn('sender_id', $admins->pluck('id'))
                ->selectRaw('sender_id, COUNT(*) as unread_count')
                ->groupBy('sender_id')
                ->pluck('unread_count', 'sender_id');

            $admins->each(function ($admin) use ($unreadByAdmin) {
                $admin->unread_count = (int) ($unreadByAdmin[$admin->id] ?? 0);
            });
        }
        
        return response()->json($admins);
    }

    public function getUsers()
    {
        $admin = Auth::user();

        $users = User::where('role', 'user')
            ->where('municipality', $admin->municipality)
            ->where('id', '!=', $admin->id)
            ->select('id', 'full_name', 'municipality')
            ->get();

        $adminId = $admin->id;

        $users->each(function ($user) use ($adminId) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $adminId)
                ->where('is_read', false)
                ->count();

            $latest = Message::where(function ($q) use ($user, $adminId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $adminId);
                })
                ->orWhere(function ($q) use ($user, $adminId) {
                    $q->where('sender_id', $adminId)->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->value('created_at');

            $user->latest_message_at = $latest;
        });

        // Sort by latest message descending; users with no messages go to bottom
        $sorted = $users->sortByDesc(fn ($u) => $u->latest_message_at ?? '0000-00-00 00:00:00')
            ->values();

        return response()->json($sorted);
    }

    public function getMessages($userId)
    {
        $currentUserId = Auth::id();
        
        $messages = Message::where(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)->where('receiver_id', $currentUserId);
            })
            ->with(['sender:id,full_name', 'receiver:id,full_name'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark messages as read
        Message::where('receiver_id', $currentUserId)
            ->where('sender_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        $message->load(['sender:id,full_name', 'receiver:id,full_name']);
        
        return response()->json($message);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
