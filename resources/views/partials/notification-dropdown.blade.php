<div x-data="notificationDropdown()" x-init="init()" class="relative">

    {{-- Bell Icon --}}
    <button @click="toggle()" 
            class="relative w-10 h-10 flex items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors">
        <i class="fa-solid fa-bell text-lg"></i>

        {{-- Badge --}}
        <template x-if="unreadCount > 0">
            <span class="absolute top-1.5 right-1.5 min-w-[16px] h-[16px] px-1 flex items-center justify-center text-[9px] font-bold text-white bg-red-600 rounded-full"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </template>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" 
         @click.outside="open = false" 
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute left-0 mt-2 w-80 md:w-96 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded shadow-lg z-50 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center bg-stone-50 dark:bg-zinc-900/50">
            <h3 class="font-bold text-sm text-ink dark:text-cream flex items-center gap-2">
                <i class="fa-solid fa-bell text-gold-500"></i>
                الإشعارات
            </h3>
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-xs text-forest-700 dark:text-gold-400 hover:underline">
                تعليم الكل كمقروء
            </button>
        </div>

        {{-- List --}}
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="text-center py-12 text-ink-muted dark:text-cream/40">
                    <i class="fa-solid fa-bell-slash text-4xl mb-3"></i>
                    <p class="text-sm">لا توجد إشعارات</p>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <a :href="notif.action_url || '#'"
                   @click="markAsRead(notif.id)"
                   class="block px-4 py-3 border-b border-stone-100 dark:border-stone-800 hover:bg-stone-50 dark:hover:bg-zinc-800 transition-colors"
                   :class="!notif.is_read ? 'bg-forest-50/50 dark:bg-forest-950/20' : ''">
                    <div class="flex gap-3">
                        {{-- Icon --}}
                        <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0"
                             :class="{
                                'bg-blue-100 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400': notif.type === 'order',
                                'bg-yellow-100 dark:bg-yellow-950/30 text-yellow-600 dark:text-yellow-400': notif.type === 'review',
                                'bg-green-100 dark:bg-green-950/30 text-green-600 dark:text-green-400': notif.type === 'store',
                                'bg-purple-100 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400': notif.type === 'welcome',
                                'bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400': notif.type === 'stock',
                                'bg-stone-100 dark:bg-zinc-800 text-ink-muted dark:text-cream/60': !['order','review','store','welcome','stock'].includes(notif.type)
                             }">
                            <i class="fa-solid text-sm"
                               :class="{
                                    'fa-cart-shopping': notif.type === 'order',
                                    'fa-star': notif.type === 'review',
                                    'fa-store': notif.type === 'store',
                                    'fa-gift': notif.type === 'welcome',
                                    'fa-triangle-exclamation': notif.type === 'stock',
                                    'fa-bell': !['order','review','store','welcome','stock'].includes(notif.type)
                               }"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-ink dark:text-cream truncate" x-text="notif.title"></p>
                            <p class="text-xs text-ink-soft dark:text-cream/70 line-clamp-2 mt-0.5" x-text="notif.body"></p>
                            <p class="text-[10px] text-ink-muted dark:text-cream/40 mt-1" x-text="formatDate(notif.created_at)"></p>
                        </div>

                        {{-- Unread Dot --}}
                        <template x-if="!notif.is_read">
                            <span class="w-2 h-2 rounded-full bg-forest-700 dark:bg-gold-400 mt-2 shrink-0"></span>
                        </template>
                    </div>
                </a>
            </template>
        </div>

        {{-- Footer --}}
        <a href="{{ route('notifications.index') }}"
           class="block text-center py-3 text-sm font-medium text-forest-700 dark:text-gold-400 hover:bg-stone-50 dark:hover:bg-zinc-800 border-t border-stone-200 dark:border-stone-800 transition-colors">
            عرض كل الإشعارات
            <i class="fa-solid fa-arrow-left mr-1 text-xs"></i>
        </a>
    </div>

    <script>
        function notificationDropdown() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                
                init() {
                    this.fetchUnreadCount();
                    setInterval(() => this.fetchUnreadCount(), 30000);
                },

                async toggle() {
                    this.open = !this.open;
                    if (this.open) await this.fetchLatest();
                },

                async fetchLatest() {
                    try {
                        const res = await fetch('{{ route("notifications.latest") }}', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count;
                    } catch (e) {
                        console.error(e);
                    }
                },

                async fetchUnreadCount() {
                    try {
                        const res = await fetch('{{ route("notifications.unread") }}', {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await res.json();
                        this.unreadCount = data.count;
                    } catch (e) {}
                },

                async markAsRead(id) {
                    this.notifications = this.notifications.map(n => 
                        n.id === id ? {...n, is_read: true} : n
                    );
                    this.unreadCount = Math.max(0, this.unreadCount - 1);

                    try {
                        await fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        });
                    } catch (e) {}
                },

                async markAllAsRead() {
                    this.notifications = this.notifications.map(n => ({...n, is_read: true}));
                    this.unreadCount = 0;

                    try {
                        await fetch('{{ route("notifications.readAll") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        });
                    } catch (e) {}
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = Math.floor((now - date) / 1000);

                    if (diff < 60) return 'الآن';
                    if (diff < 3600) return `قبل ${Math.floor(diff / 60)} دقيقة`;
                    if (diff < 86400) return `قبل ${Math.floor(diff / 3600)} ساعة`;
                    if (diff < 604800) return `قبل ${Math.floor(diff / 86400)} يوم`;
                    return date.toLocaleDateString('ar-EG');
                }
            }
        }
    </script>
</div>