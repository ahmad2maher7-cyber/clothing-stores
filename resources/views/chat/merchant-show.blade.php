@extends('merchant.layouts.app')

@section('title', 'محادثة مع ' . $conversation->customer->full_name)
@section('page-title', 'المحادثة')

@section('content')
<div class="max-w-3xl mx-auto" 
     x-data="chatRoom({{ $conversation->id }}, {{ auth()->id() }}, {{ $lastMessageId }})"
     x-init="init()">

    {{-- Header --}}
    <div class="bg-white rounded-t-lg border border-gray-200 p-4 flex items-center gap-3">
        <a href="{{ route('merchant.chat.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-arrow-right"></i>
        </a>
        <div class="w-10 h-10 rounded-full bg-forest-900 text-white flex items-center justify-center text-xl font-bold">
            {{ mb_substr($conversation->customer->full_name, 0, 1) }}
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-gray-900">{{ $conversation->customer->full_name }}</h3>
            <p class="text-xs text-gray-500">{{ $conversation->customer->email }}</p>
        </div>
    </div>

    {{-- Messages --}}
    <div x-ref="messagesContainer"
         class="bg-gray-50 border-x border-gray-200 p-4 space-y-3 overflow-y-auto"
         style="height: calc(100vh - 320px);">

        <template x-for="msg in messages" :key="msg.id">
            <div :class="msg.is_mine ? 'flex justify-end' : 'flex justify-start'">
                <div class="max-w-[75%] rounded-2xl px-4 py-2"
                     :class="msg.is_mine 
                        ? 'bg-forest-700 text-white rounded-br-sm' 
                        : 'bg-white text-gray-900 border border-gray-200 rounded-bl-sm'">
                    <p class="text-sm whitespace-pre-wrap" x-text="msg.message"></p>
                    <p class="text-[10px] mt-1 opacity-70" x-text="formatTime(msg.created_at)"></p>
                </div>
            </div>
        </template>

        <template x-if="messages.length === 0">
            <div class="text-center py-12 text-gray-400">
                <div class="text-6xl mb-3">💬</div>
                <p class="text-sm">لا توجد رسائل بعد</p>
            </div>
        </template>
    </div>

    {{-- Input --}}
    <div class="bg-white rounded-b-lg border border-gray-200 p-3">
        <form @submit.prevent="sendMessage()" class="flex gap-2">
            <input type="text" 
                   x-model="newMessage"
                   placeholder="اكتب رسالتك..."
                   maxlength="2000"
                   class="flex-1 border-gray-300 rounded-lg focus:border-forest-600 focus:ring-forest-600 text-sm">
            <button type="submit" 
                    :disabled="!newMessage.trim() || sending"
                    class="bg-forest-700 hover:bg-forest-800 text-white px-5 rounded-lg disabled:opacity-50 transition">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
    function chatRoom(conversationId, userId, lastMessageId) {
        return {
            messages: {!! $messagesJson !!},
            newMessage: '',
            sending: false,
            lastId: lastMessageId,

            init() {
                this.scrollToBottom();
                setInterval(() => this.fetchNew(), 3000);
            },

            async sendMessage() {
                if (!this.newMessage.trim() || this.sending) return;
                this.sending = true;
                const text = this.newMessage;
                this.newMessage = '';

                try {
                    const res = await fetch(`/chat/${conversationId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ message: text }),
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.messages.push(data.message);
                        this.lastId = data.message.id;
                        this.scrollToBottom();
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.sending = false;
                }
            },

            async fetchNew() {
                try {
                    const res = await fetch(`/chat/${conversationId}/fetch?last_id=${this.lastId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    });
                    const data = await res.json();
                    if (data.success && data.messages.length > 0) {
                        this.messages.push(...data.messages);
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.scrollToBottom();
                    }
                } catch (e) {}
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const el = this.$refs.messagesContainer;
                    if (el) el.scrollTop = el.scrollHeight;
                });
            },

            formatTime(iso) {
                const date = new Date(iso);
                return date.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' });
            }
        }
    }
    </script>
</div>
@endsection