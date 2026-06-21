<?php

namespace Tests\Unit;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TagValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    use RefreshDatabase;

    public function test_tag_name_is_required()
    {
        $validator = Validator::make(
            ['name' => ''],
            ['name' => 'required']
        );

        $this->assertTrue(
            $validator->fails()
        );
    }

    public function test_tag_name_must_be_unique()
    {
        Tag::factory()->create([
            'name' => 'Laravel',
        ]);

        $validator = Validator::make(
            ['name' => 'Laravel'],
            ['name' => 'unique:tags,name']
        );

        $this->assertTrue(
            $validator->fails()
        );
    }
}
