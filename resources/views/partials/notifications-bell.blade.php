{{-- ═══════════════════════════════════════ --}}
{{--  Notification Bell --}}
{{-- ═══════════════════════════════════════ --}}
<div x-data="notificationsBell()" x-init="init()" class="relative">

    {{-- Bell Icon --}}
    <button @click="toggle()"
            class="relative w-10 h-10 flex items-center justify-center text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-100 dark:hover:bg-zinc-900 rounded transition-colors"
            title="الإشعارات">
        <i class="fa-solid fa-bell text-lg"></i>

        {{-- Badge --}}
        <template x-if="unreadCount > 0">
            <span class="absolute -top-0.5 -left-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-red-600 rounded-full">
                <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
            </span>
        </template>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         @click.outside="open = false"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute left-0 mt-2 w-80 sm:w-96 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg shadow-lg z-50 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-stone-200 dark:border-stone-800">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bell text-forest-700 dark:text-gold-400"></i>
                <h3 class="font-bold text-sm text-ink dark:text-cream">الإشعارات</h3>
                <template x-if="unreadCount > 0">
                    <span class="text-xs px-2 py-0.5 bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400 rounded-full"
                          x-text="unreadCount + ' جديد'"></span>
                </template>
            </div>

            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()"
                        class="text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                    <i class="fa-solid fa-check-double text-[10px]"></i>
                    تعليم الكل
                </button>
            </template>
        </div>

        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-ink-muted"></i>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                        <i class="fa-regular fa-bell text-2xl text-ink-muted dark:text-cream/40"></i>
                    </div>
                    <p class="text-sm text-ink-muted dark:text-cream/60">لا توجد إشعارات</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length > 0">
                <div class="divide-y divide-stone-100 dark:divide-stone-800">
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="markAsRead(notification)"
                             class="p-4 hover:bg-stone-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors"
                             :class="!notification.is_read ? 'bg-forest-50/50 dark:bg-forest-950/20' : ''">
                            <div class="flex items-start gap-3">
                                {{-- Icon --}}
                                <div class="w-9 h-9 flex items-center justify-center rounded shrink-0"
                                     :class="getIconClass(notification.type)">
                                    <i class="fa-solid text-sm" :class="getIcon(notification.type)"></i>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="font-medium text-sm text-ink dark:text-cream line-clamp-1"
                                           x-text="notification.title"></p>
                                        <template x-if="!notification.is_read">
                                            <span class="w-2 h-2 bg-forest-700 dark:bg-gold-400 rounded-full shrink-0 mt-1"></span>
                                        </template>
                                    </div>
                                    <p class="text-xs text-ink-muted dark:text-cream/60 mt-1 line-clamp-2"
                                       x-text="notification.body"></p>
                                    <p class="text-[10px] text-ink-muted dark:text-cream/40 mt-1.5">
                                        <i class="fa-regular fa-clock text-[9px] ml-1"></i>
                                        <span x-text="timeAgo(notification.created_at)"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-2 border-t border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-950">
            <a href="{{ route('notifications.all') }}"
               class="block text-center text-xs font-medium text-forest-700 dark:text-gold-400 hover:opacity-70 transition-opacity">
                <i class="fa-solid fa-list text-[10px]"></i>
                عرض كل الإشعارات
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════ --}}
{{--  Alpine.js Script --}}
{{-- ═══════════════════════════════════════ --}}
@once
@push('scripts')
<script>
function notificationsBell() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        fetched: false,

        init() {
            // جلب عدد الإشعارات غير المقروءة
            this.fetchUnreadCount();
            
            // تحديث كل 60 ثانية
            setInterval(() => this.fetchUnreadCount(), 60000);
        },

        async toggle() {
            this.open = !this.open;

            if (this.open && !this.fetched) {
                await this.fetchNotifications();
                this.fetched = true;
            }
        },

        async fetchUnreadCount() {
            try {
                const res = await fetch('{{ route('notifications.unreadCount') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await res.json();
                this.unreadCount = data.count;
            } catch (e) {
                console.error('Failed to fetch unread count');
            }
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('notifications.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await res.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (e) {
                console.error('Failed to fetch notifications');
            } finally {
                this.loading = false;
            }
        },

        async markAsRead(notification) {
            if (notification.is_read) return;

            try {
                await fetch(`/notifications/${notification.id}/read`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                notification.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            } catch (e) {
                console.error('Failed to mark as read');
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route('notifications.markAllAsRead') }}', {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
                showToast('تم تعليم كل الإشعارات كمقروءة', 'success');
            } catch (e) {
                console.error('Failed to mark all as read');
            }
        },

        getIcon(type) {
            const icons = {
                'login': 'fa-right-to-bracket',
                'order_created': 'fa-shopping-cart',
                'order_placed': 'fa-check-circle',
                'order_status_changed': 'fa-rotate',
                'product_created': 'fa-box',
                'review_created': 'fa-star',
                'review_approved': 'fa-check-double',
                'offer_created': 'fa-fire',
                'test': 'fa-flask',
                'general': 'fa-bell',
            };
            return icons[type] || 'fa-bell';
        },

        getIconClass(type) {
            const classes = {
                'login': 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400',
                'order_created': 'bg-forest-50 text-forest-700 dark:bg-forest-950/40 dark:text-gold-400',
                'order_placed': 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400',
                'order_status_changed': 'bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400',
                'product_created': 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400',
                'review_created': 'bg-yellow-50 text-yellow-600 dark:bg-yellow-950/40 dark:text-yellow-400',
                'review_approved': 'bg-green-50 text-green-600 dark:bg-green-950/40 dark:text-green-400',
                'offer_created': 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400',
                'test': 'bg-stone-100 text-stone-600 dark:bg-zinc-800 dark:text-cream/60',
            };
            return classes[type] || 'bg-stone-100 text-stone-600 dark:bg-zinc-800 dark:text-cream/60';
        },

        timeAgo(datetime) {
            const date = new Date(datetime);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'الآن';
            if (seconds < 3600) return `منذ ${Math.floor(seconds / 60)} دقيقة`;
            if (seconds < 86400) return `منذ ${Math.floor(seconds / 3600)} ساعة`;
            if (seconds < 604800) return `منذ ${Math.floor(seconds / 86400)} يوم`;
            return date.toLocaleDateString('ar-EG');
        }
    }
}
</script>
@endpush
@endonce