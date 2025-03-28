<?php
 

namespace App\Khranilischa\User;

use App\Models\User;

class RepositoriPolzovatelei
{
    public function containsChatId(int $telegram_chat_id)
    {
        return User::where(['telegram_chat_id' => $telegram_chat_id])->exists();
    }

    public function addChatId(int $user_id, int $chat_id): bool
    {
        $user = User::find($user_id);
        return $user?->update(['telegram_chat_id' => $chat_id]);
    }

    public function hasConnectedTelegram(int $user_id): bool
    {
        return null !== User::find($user_id)?->telegram_chat_id;
    }

    public function getTelegramChatId(string $phone)
    {
        return User::where(['phone' => $phone])->first()?->telegram_chat_id;
    }
}