<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Message;
use App\Models\Conversation;

return new class extends Migration
{
    public function up(): void
    {
        // সব পুরোনো message-এ conversation_id set করো
        $messages = Message::where('conversation_id', null)->get();
        
        foreach ($messages as $msg) {
            $conv = Conversation::getOrCreate($msg->sender_id, $msg->recipient_id);
            $msg->update(['conversation_id' => $conv->id]);
        }
    }

    public function down(): void
    {
        // revert করতে চাইলে
    }
};