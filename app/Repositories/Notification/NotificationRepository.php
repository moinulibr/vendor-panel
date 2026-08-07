<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\User;
use App\Repositories\Notification\Interface\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function getUserNotifications(User $user, string $channel = 'app', int $perPage = 15): LengthAwarePaginator
    {
        return Notification::where('user_id', $user->id)
            ->forChannel($channel)
            ->latest()
            ->paginate($perPage);
    }

    public function getUnreadCount(User $user, string $channel = 'app'): int
    {
        return Notification::where('user_id', $user->id)
            ->forChannel($channel)
            ->unread()
            ->count();
    }

    public function markAsRead(User $user, int $notificationId): bool
    {
        return (bool) Notification::where('user_id', $user->id)
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(User $user): bool
    {
        return (bool) Notification::where('user_id', $user->id)
            ->unread()
            ->update(['read_at' => now()]);
    }

    public function createNotification(array $data)
    {
        return Notification::create([
            'user_id'        => $data['user_id'],
            'title'          => $data['title'],
            'body'           => $data['body'],
            'type'           => $data['type'] ?? 'system',
            'target_channel' => $data['target_channel'] ?? 'all',
            'data'           => $data['data'] ?? null,
        ]);
    }
}