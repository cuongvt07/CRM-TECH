<?php

namespace App\Livewire;

use App\Models\AppNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public function markAsRead($id)
    {
        $notification = AppNotification::where('user_id', Auth::id())->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }
    }

    public function markAllAsRead()
    {
        AppNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $notifications = AppNotification::where('user_id', Auth::id())
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.notification-list', [
            'notifications' => $notifications
        ])->layout('components.layouts.app');
    }
}
