<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $users = Message::with('user')
            ->select('user_id')
            ->distinct()
            ->get();

        return view('admin.messages.index', compact('users'));
    }

    public function show($userId)
    {
        $messages = Message::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        $user = User::findOrFail($userId);

        Message::where('user_id', $userId)->where('is_from_admin', false)->update(['is_read' => true]);

        return view('admin.messages.show', compact('messages', 'user'));
    }

    public function reply(Request $request, $userId)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
        ]);

        Message::create([
            'user_id' => $userId,
            'message' => $validated['message'],
            'is_from_admin' => true,
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }
}
