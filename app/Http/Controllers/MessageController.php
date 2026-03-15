<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function page()
    {
        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('user_id', Auth::id())
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_from_admin' => false,
            'is_read' => false,
        ]);

        return redirect()->route('chat.index')->with('success', 'Pesan terkirim.');
    }

    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['messages' => [], 'unread_admin_count' => 0]);
        }

        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        $unreadAdminCount = Message::where('user_id', Auth::id())
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'messages' => $messages,
            'unread_admin_count' => $unreadAdminCount,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_from_admin' => false,
            'is_read' => false,
        ]);

        return response()->json(['message' => $message]);
    }

    public function markAdminRead()
    {
        if (!Auth::check()) {
            return response()->json(['ok' => true]);
        }

        Message::where('user_id', Auth::id())
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
