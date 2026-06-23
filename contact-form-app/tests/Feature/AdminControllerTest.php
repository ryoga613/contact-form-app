<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_index_filters_contacts_by_keyword(): void
    {

        $user = User::factory()->create();

        $hitContact = Contact::factory()->create([
            'first_name' => '太郎',
            'last_name' => '山田',
            'email' => 'taro@example.com',
        ]);

        $missContact = Contact::factory()->create([
            'first_name' => '花子',
            'last_name' => '鈴木',
            'email' => 'hanako@example.com',
        ]);

        $response = $this->actingAs($user)->get('/admin?keyword=太郎');

        $response->assertStatus(200);
        $response->assertViewIs('admin.index');

        $response->assertViewHas('contacts', function ($contacts) use ($hitContact, $missContact) {
            return $contacts->contains($hitContact) && ! $contacts->contains($missContact);
        });
    }

    public function test_show_returns_404_when_contact_not_found(): void
    {
        // 0. ログイン用のユーザーを作成
        $user = User::factory()->create();

        $nonExistentId = 999;

        $response = $this->actingAs($user)->get('/admin/'.$nonExistentId);

        $response->assertStatus(404);
    }

    public function test_destroy_deletes_contact_and_redirects(): void
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->create();
        // $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->delete('/admin/contacts/'.$contact->id);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    }
}
