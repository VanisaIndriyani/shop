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
        $userIds = Message::query()
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->filter()
            ->values()
            ->all();

        $users = User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $lastMessageIds = Message::query()
            ->whereIn('user_id', $userIds)
            ->selectRaw('MAX(id) as id')
            ->groupBy('user_id')
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        $lastMessages = Message::query()
            ->whereIn('id', $lastMessageIds)
            ->get(['id', 'user_id', 'message', 'is_from_admin', 'created_at'])
            ->keyBy('user_id');

        $unreadCounts = Message::query()
            ->whereIn('user_id', $userIds)
            ->where('is_from_admin', false)
            ->where('is_read', false)
            ->selectRaw('user_id, COUNT(*) as cnt')
            ->groupBy('user_id')
            ->pluck('cnt', 'user_id')
            ->toArray();

        $threads = collect($userIds)
            ->map(function ($userId) use ($users, $lastMessages, $unreadCounts) {
                $user = $users->get($userId);
                if (!$user) {
                    return null;
                }
                $last = $lastMessages->get($userId);
                $unread = (int) ($unreadCounts[$userId] ?? 0);
                return [
                    'user' => $user,
                    'last_message' => $last,
                    'unread_count' => $unread,
                    'last_at' => $last?->created_at,
                ];
            })
            ->filter()
            ->sortByDesc(fn ($t) => $t['last_at'] ? $t['last_at']->timestamp : 0)
            ->values();

        return view('admin.messages.index', compact('threads'));
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
