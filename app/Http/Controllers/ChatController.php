<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * صفحة الشات (للزبون)
     */
    public function customerIndex()
    {
        $user = auth()->user();

        $conversations = ChatConversation::where('customer_id', $user->id)
            ->with(['store', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest('last_message_at')
            ->get();

        return view('chat.customer', compact('conversations'));
    }

    /**
     * صفحة الشات (للتاجر)
     */
    public function merchantIndex()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            return redirect()->route('merchant.dashboard');
        }

        $conversations = ChatConversation::where('store_id', $store->id)
            ->with(['customer', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest('last_message_at')
            ->get();

        return view('chat.merchant', compact('store', 'conversations'));
    }

    /**
     * بدء محادثة جديدة من الزبون مع متجر
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
        ]);

        $user = auth()->user();

        $conversation = ChatConversation::firstOrCreate(
            [
                'customer_id' => $user->id,
                'store_id' => $validated['store_id'],
            ],
            [
                'last_message_at' => now(),
            ]
        );

        // ✅ الاسم الصحيح للـ route
        return redirect()->route('customer.chat.show', $conversation);
    }

    /**
     * عرض محادثة معينة
     */
   public function show(ChatConversation $conversation)
{
    $user = auth()->user();

    // تحقق الصلاحية
    $isCustomer = $user->id === $conversation->customer_id;
    $isMerchant = $user->role === 'merchant' 
        && $conversation->store->merchant_id === $user->id;

    if (!$isCustomer && !$isMerchant) {
        abort(403);
    }

    $conversation->load(['customer', 'store', 'messages.sender']);

    // تعليم الرسائل كمقروءة
    ChatMessage::where('conversation_id', $conversation->id)
        ->where('sender_id', '!=', $user->id)
        ->update(['is_read' => true]);

    // ═══════════════════════════════════════
    // تحضير الرسائل كمصفوفة (لتجنب PHP في Blade)
    // ═══════════════════════════════════════
    $messagesJson = $conversation->messages->map(function ($m) use ($user) {
        return [
            'id' => $m->id,
            'message' => $m->message,
            'sender_id' => $m->sender_id,
            'sender_name' => $m->sender->full_name,
            'created_at' => $m->created_at->toISOString(),
            'is_mine' => $m->sender_id === $user->id,
        ];
    })->values()->toArray();

    $lastMessageId = $conversation->messages->last()?->id ?? 0;

    // اختيار الـ view حسب الدور
    $view = $isMerchant ? 'chat.merchant-show' : 'chat.customer-show';

    return view($view, compact('conversation', 'messagesJson', 'lastMessageId'));
}

    /**
     * إرسال رسالة (AJAX)
     */
    public function send(Request $request, ChatConversation $conversation)
    {
        $user = auth()->user();

        // تحقق الصلاحية
        $isCustomer = $user->id === $conversation->customer_id;
        $isMerchant = $user->role === 'merchant' 
            && $conversation->store->merchant_id === $user->id;

        if (!$isCustomer && !$isMerchant) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // إشعار للطرف الآخر
        try {
            $recipient = $isCustomer ? $conversation->store->merchant : $conversation->customer;

            \App\Services\NotificationService::send(
                user: $recipient,
                type: 'chat',
                title: '💬 رسالة جديدة',
                body: substr($validated['message'], 0, 80) . (strlen($validated['message']) > 80 ? '...' : ''),
                actionUrl: $isCustomer 
                    ? route('merchant.chat.show', $conversation)
                    : route('customer.chat.show', $conversation)
            );
        } catch (\Throwable $e) {
            \Log::error('Chat notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'sender_name' => $user->full_name,
                'created_at' => $message->created_at->toISOString(),
                'is_mine' => true,
            ],
        ]);
    }

    /**
     * جلب رسائل جديدة (AJAX)
     */
    public function fetch(Request $request, ChatConversation $conversation)
    {
        $user = auth()->user();

        // تحقق الصلاحية
        $isCustomer = $user->id === $conversation->customer_id;
        $isMerchant = $user->role === 'merchant' 
            && $conversation->store->merchant_id === $user->id;

        if (!$isCustomer && !$isMerchant) {
            return response()->json(['success' => false], 403);
        }

        $lastId = (int) $request->input('last_id', 0);

        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('id')
            ->get()
            ->map(fn($msg) => [
                'id' => $msg->id,
                'message' => $msg->message,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender->full_name,
                'created_at' => $msg->created_at->toISOString(),
                'is_mine' => $msg->sender_id === $user->id,
            ]);

        // تعليم الرسائل الجديدة كمقروءة
        ChatMessage::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}