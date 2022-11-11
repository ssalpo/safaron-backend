<?php

namespace App\Services;

use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    /**
     * Отправляет сообщение
     *
     * @param string $message
     * @param string $receiverId
     * @param string|null $senderId
     * @return Conversation
     */
    public function store(string $message, string $receiverId, string $senderId = null): Conversation
    {
        return Conversation::create([
            'message' => $message,
            'receiver_id' => $receiverId,
            'sender_id' => $senderId ?? auth()->id()
        ]);
    }

    /**
     * Устанавливает флаг прочитан во все непрочитанные сообщения
     *
     * @param string $receiverId
     * @return void
     */
    public function readMessages(string $receiverId): void
    {
        Conversation::whereReceiverId($receiverId)
            ->update(['read' => true, 'read_at' => now()]);
    }

    public function getLastRecorOfEveryUser(string $userId)
    {
        return DB::table('conversations')
            ->selectRaw('MAX(id) as lastId')
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->groupBy(DB::raw("CONCAT(LEAST(receiver_id, sender_id), ' . ', GREATEST(receiver_id, sender_id))"))
            ->pluck('lastId');
    }
}
