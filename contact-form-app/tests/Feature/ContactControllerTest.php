<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_can_show_contact_index(): void
    {

        $categories = Category::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertViewIs('contact.index');
        $response->assertViewHas('categories');
    }

    public function test_can_confirm_contact()
    {

        $categories = Category::factory()->create();
        $validated = ([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 2,
            'email' => 'test@example.com',
            'tel' => '12345678910',
            'address' => 'test',
            'building' => 'test house',
            'category_id' => $categories->first()->id,
            'detail' => 'test test',
        ]);

        $response = $this->post('/contacts/confirm', $validated);

        $response->assertStatus(200);

        $response->assertViewIs('contact.confirm');

        $response->assertViewHas('validated', $validated);
        $response->assertViewHas('category');
    }

    public function test_can_store_contact()
    {
        $this->withoutExceptionHandling();

        $categories = Category::factory()->create();
        $validated = ([
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 2,
            'email' => 'test@example.com',
            'tel' => '12345678910',
            'address' => 'test',
            'building' => 'test house',
            'category_id' => $categories->first()->id,
            'detail' => 'test test',
        ]);

        $response = $this->post('/contacts', $validated);

        $response->assertStatus(302);
        $response->assertRedirect('/contacts/thanks');
    }

    public function test_can_show_thanks()
    {

        $response = $this->get('/contacts/thanks');

        $response->assertViewIs('contact.thanks');
    }
}
