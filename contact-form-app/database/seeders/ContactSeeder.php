<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Tag;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = Contact::factory(20)->create();

        foreach ($contacts as $contact) {

            $tagIds = Tag::inRandomOrder()
                ->limit(rand(1, 3))
                ->pluck('id');

            $contact->tag()->attach($tagIds);
        }
    }
}
