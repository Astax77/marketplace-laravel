<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = Announcement::where('status', 'active')->take(8)->get();
        $users         = User::all();

        if ($users->count() < 2 || $announcements->isEmpty()) {
            $this->command->warn('Skipping ConversationSeeder: not enough data.');
            return;
        }

        $exchanges = [
            'Bonjour, est-ce que larticle est toujours disponible ?',
            'Oui, toujours disponible. Vous avez des questions ?',
            'Quel est votre meilleur prix ?',
            'Je peux faire une petite reduction si vous venez chercher en main propre.',
            'Daccord, je vous contacte pour fixer un rendez-vous. Merci !',
        ];

        foreach ($announcements as $announcement) {
            // Find a buyer that is NOT the seller
            $buyer = $users->first(fn ($u) => $u->id !== $announcement->user_id);

            if (! $buyer) {
                continue;
            }

            // Avoid duplicate conversations
            $alreadyExists = Conversation::where('announcement_id', $announcement->id)
                ->where('buyer_id', $buyer->id)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $conversation = Conversation::create([
                'announcement_id' => $announcement->id,
                'buyer_id'        => $buyer->id,
                'seller_id'       => $announcement->user_id,
                'last_message_at' => now()->subMinutes(rand(10, 1440)),
            ]);

            // Alternate messages between buyer and seller
            foreach ($exchanges as $index => $body) {
                $senderId = ($index % 2 === 0) ? $buyer->id : $announcement->user_id;

                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $senderId,
                    'body'            => $body,
                    'is_read'         => true,
                ]);
            }

            // Add one unread message at the end
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $buyer->id,
                'body'            => 'A bientot !',
                'is_read'         => false,
            ]);
        }

        $this->command->info('Conversations seeded successfully.');
    }
}
