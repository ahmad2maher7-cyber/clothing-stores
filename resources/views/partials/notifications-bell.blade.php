@auth
<div x-data="notificationsBell()" class="relative">
    <button @click="open = !open; if(open) loadNotifications()"
            class="relative w-10 h-10 flex items-center justify-center text-ink-muted hover:text-forest-700 dark:text-cream/60 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors">
        <i class="fa-solid fa-bell text-lg"></i>
        <template x-if="unreadCount > 0">
            <span class="absolute -top-0.5 -left-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-red-600 rounded-full"
                  x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </template>
    </button>

    <div x-show="open" @click.outside="open = false" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute left-0 mt-2 w-80 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg shadow-lg z-50">

        {{-- Header --}}
        <div class="p-4 border-b border-stone-200 dark:border-stone-800 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-ink dark:text-cream">الإشعارات</h3>
                <p class="text-xs text-ink-muted dark:text-cream/50">
                    <span x-text="unreadCount"></span> غير مقروءة
                </p>
            </div>
            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()"
                        class="text-xs text-forest-700 dark:text-gold-400 hover:opacity-70 transition">
                    تعليم الكل كمقروء
                </button>
            </template>
        </div>

        {{-- List --}}
        <div class="max-h-96 overflow-y-auto">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-ink-muted"></i>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <i class="fa-solid fa-bell-slash text-2xl text-ink-muted dark:text-cream/40"></i>
                    </div>
                    <p class="text-sm text-ink-muted dark:text-cream/60">لا توجد إشعارات</p>
                </div>
            </template>

            <template x-if="!loading">
                <div>
                    <template x-for="notif in notifications" :key="notif.id">
                        <a :href="notif.url || '#'"
                           @click="if(!notif.is_read) markAsRead(notif.id)"
                           class="flex gap-3 p-3 border-b border-stone-100 dark:border-stone-800 hover:bg-stone-50 dark:hover:bg-zinc-800 transition"
                           :class="!notif.is_read ? 'bg-forest-50/50 dark:bg-forest-950/20' : ''">

                            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-full">
                                <i :class="getIcon(notif.type)"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-ink dark:text-cream" x-text="notif.title"></p>
                                <p class="text-xs text-ink-muted dark:text-cream/60 line-clamp-2 mt-0.5" x-text="notif.body"></p>
                                <p class="text-[10px] text-ink-faint dark:text-cream/40 mt-1" x-text="notif.time"></p>
                            </div>
                            <template x-if="!notif.is_read">
                                <span class="w-2 h-2 bg-forest-700 dark:bg-gold-400 rounded-full mt-2"></span>
                            </template>
                        </a>
                    </template>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="p-3 border-t border-stone-200 dark:border-stone-800 text-center">
            <a href="{{ route('notifications.all') }}"
               class="text-sm font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition">
                عرض كل الإشعارات
                <i class="fa-solid fa-arrow-left text-xs ml-1"></i>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function notificationsBell() {
    return {
        open: false,
        loading: false,
        unreadCount: {{ auth()->user()->notifications()->where('is_read', false)->count() }},
        notifications: [],

        async loadNotifications() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('notifications.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async markAsRead(id) {
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const notif = this.notifications.find(n => n.id === id);
                if (notif) notif.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            } catch (e) {
                console.error(e);
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route('notifications.markAllAsRead') }}', {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            } catch (e) {
                console.error(e);
            }
        },

        getIcon(type) {
            const icons = {
                'login': 'fa-solid fa-right-to-bracket',
                'order_created': 'fa-solid fa-cart-shopping',
                'order_placed': 'fa-solid fa-check',
                'order_status_changed': 'fa-solid fa-truck',
                'product_created': 'fa-solid fa-shirt',
                'review_created': 'fa-solid fa-star',
                'review_approved': 'fa-solid fa-circle-check',
                'offer_created': 'fa-solid fa-fire',
                'test': 'fa-solid fa-flask',
            };
            return icons[type] || 'fa-solid fa-bell';
        }
    }
}
</script>
@endpush
@endauth