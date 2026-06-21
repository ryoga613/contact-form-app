<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ContactValidationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_required_fields_fail_when_empty()
    {
        $data = [];

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'tel' => 'required',
            'gender' => 'required|in:1,2,3',
            'category_id' => 'required|exists:categories,id',
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
    }

    public function test_email_must_be_valid_format()
    {
        $validator = Validator::make(
            ['email' => 'invalid-email'],
            ['email' => 'email']
        );

        $this->assertTrue($validator->fails());
    }

    public function test_tel_must_be_numeric()
    {
        $validator = Validator::make(
            ['tel' => 'abc'],
            ['tel' => ['regex:/^[0-9]+$/']]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_gender_must_be_valid_value()
    {
        $validator = Validator::make(
            ['gender' => 9],
            ['gender' => 'in:1,2,3']
        );

        $this->assertTrue($validator->fails());
    }
}
