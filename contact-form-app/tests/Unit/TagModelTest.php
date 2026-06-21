<?php

namespace Tests\Unit;

use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagModelTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_tag_belongs_to_many_contacts()
    {
        $tag = Tag::factory()->create();
        $contact = Contact::factory()->create();

        $tag->contacts()->attach($contact->id);

        $this->assertTrue(
            $tag->contacts->contains($contact)
        );
    }
}
