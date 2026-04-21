<?php

namespace App\Http\Controllers;

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
        
        return response()->json($admins);
    }

    public function getUsers()
    {
        $admin = Auth::user();
        
        // Get users who have sent messages to this admin
        $userIds = Message::where(function($query) use ($admin) {
                $query->where('sender_id', $admin->id)
                    ->orWhere('receiver_id', $admin->id);
            })
            ->get()
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->filter(fn($id) => $id != $admin->id)
            ->values();
        
        $users = User::whereIn('id', $userIds)
            ->where('municipality', $admin->municipality)
            ->select('id', 'full_name', 'municipality')
            ->get();
        
        // Add unread count for each user
        $users->each(function($user) use ($admin) {
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $admin->id)
                ->where('is_read', false)
                ->count();
        });
        
        return response()->json($users);
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
