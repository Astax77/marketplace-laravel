<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementService
{
    public function create(User $user, array $data, array $images = []): Announcement
    {
        $data['user_id'] = $user->id;
        $data['status']  = Announcement::STATUS_ACTIVE;
        // Pass array directly — Eloquent 'array' cast will json_encode it automatically
        $data['images']  = $this->uploadImages($images);

        return Announcement::create($data);
    }

    public function update(Announcement $announcement, array $data, array $newImages = []): Announcement
    {
        if (! empty($newImages)) {
            $this->deleteImages($announcement->images ?? []);
            // Pass array directly — Eloquent cast handles encoding
            $data['images'] = $this->uploadImages($newImages);
        }

        $announcement->update($data);

        return $announcement->fresh();
    }

    public function delete(Announcement $announcement): bool
    {
        $this->deleteImages($announcement->images ?? []);
        return $announcement->delete();
    }

    public function changeStatus(Announcement $announcement, string $status): Announcement
    {
        if (! in_array($status, Announcement::STATUSES, true)) {
            throw new \InvalidArgumentException("Statut invalide : {$status}");
        }
        $announcement->update(['status' => $status]);
        return $announcement->fresh();
    }

    public function incrementViews(Announcement $announcement): void
    {
        $announcement->increment('views_count');
    }

    public function getUserAnnouncements(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->announcements()
            ->with(['category', 'city'])
            ->latest()
            ->paginate($perPage);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    /**
     * Upload images directly into public/uploads/announcements/
     * Returns a plain PHP array of relative paths.
     * Do NOT json_encode here — Eloquent's 'array' cast handles that.
     *
     * @param  UploadedFile[]  $images
     * @return string[]
     */
    private function uploadImages(array $images): array
    {
        $paths = [];

        $uploadDir = public_path('uploads/announcements');

        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($images as $image) {
            if (! ($image instanceof UploadedFile) || ! $image->isValid()) {
                continue;
            }

            $extension = strtolower($image->getClientOriginalExtension());
            $filename  = time() . '_' . uniqid() . '.' . $extension;

            $image->move($uploadDir, $filename);

            $paths[] = 'announcements/' . $filename;
        }

        return $paths;
    }

    /**
     * Delete image files from public/uploads/
     *
     * @param  string[]|null  $paths
     */
    private function deleteImages(array|null $paths): void
    {
        if (empty($paths)) {
            return;
        }

        foreach ($paths as $path) {
            if (! $path) {
                continue;
            }
            $fullPath = public_path('uploads/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
