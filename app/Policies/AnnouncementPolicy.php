<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Only the owner can update an announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->user_id;
    }

    /**
     * Only the owner can delete an announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->user_id;
    }
}
