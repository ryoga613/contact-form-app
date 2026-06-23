<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_displays_specific_tag(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->get('/admin/tags/'.$tag->id.'/edit');

        $response->assertStatus(200);
        $response->assertViewIs('admin.tags.edit');

        $response->assertViewHas('tag', function ($viewTag) use ($tag) {
            return $viewTag->id === $tag->id;
        });
    }

    public function test_store_saves_tag_and_redirects(): void
    {
        $user = User::factory()->create();

        $tagData = [
            'name' => '新規タグ',
        ];

        $response = $this->actingAs($user)->post('/admin/tags', $tagData);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->assertDatabaseHas('tags', [
            'name' => '新規タグ',
        ]);
    }

    public function test_update_modifies_tag_and_redirects(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create([
            'name' => '古いタグ名',
        ]);

        $updatedData = [
            'name' => '新しいタグ名',
        ];

        $response = $this->actingAs($user)->put('/admin/tags/'.$tag->id, $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => '新しいタグ名',
        ]);
    }

    public function test_destroy_deletes_tag_and_redirects(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->actingAs($user)->delete('/admin/tags/'.$tag->id);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }
}
