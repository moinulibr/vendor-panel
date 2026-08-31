<?php

namespace App\Repositories\Notification\Interface;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getUserNotifications(User $user, string $channel = 'app', int $perPage = 15): LengthAwarePaginator;
    public function getUnreadCount(User $user, string $channel = 'app'): int;
    public function markAsRead(User $user, int $notificationId): bool;
    public function markAllAsRead(User $user): bool;
    public function createNotification(array $data): Notification;
}
