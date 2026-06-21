<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactModelTest extends TestCase
{
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function test_contact_belongs_to_category()
    {
        $category = Category::factory()->create();

        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(
            Category::class,
            $contact->category
        );
    }

    public function test_contact_belongs_to_many_tags()
    {
        $contact = Contact::factory()->create();
        $tag = Tag::factory()->create();

        $contact->tags()->attach($tag->id);

        $this->assertTrue(
            $contact->tags->contains($tag)
        );
    }

    public function test_gender_label_male()
    {
        $contact = new Contact([
            'gender' => 1,
        ]);

        $this->assertEquals(
            '男',
            $contact->gender_label
        );
    }

    public function test_gender_label_female()
    {
        $contact = new Contact([
            'gender' => 2,
        ]);

        $this->assertEquals(
            '女',
            $contact->gender_label
        );
    }

    public function test_gender_label_other()
    {
        $contact = new Contact([
            'gender' => 3,
        ]);

        $this->assertEquals(
            'その他',
            $contact->gender_label
        );
    }
}
