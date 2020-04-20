<?php

namespace Tests\Feature;

use App\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_contact_can_be_added()
    {
        $this->withoutExceptionHandling();
        $this->post(
            '/api/contacts',
            $this->data()
        );

        $contact = Contact::first();

        $this->assertEquals('Test name', $contact->name);
        $this->assertEquals('test@email.com', $contact->email);
        $this->assertEquals('01/01/1990', $contact->birthday);
        $this->assertEquals('Company Name', $contact->company);
    }

    /** @test */
    public function fields_are_required()
    {
        collect(['name', 'email', 'birthday', 'company'])
            ->each(function ($field) {
                $response = $this->post(
                    '/api/contacts',
                    array_merge($this->data(), [$field => ''])
                );

                $response->assertSessionHasErrors($field);
                $this->assertCount(0, Contact::all());
            });
    }

    private function data()
    {
        return [
            'name' => 'Test name',
            'email' => 'test@email.com',
            'birthday' => '01/01/1990',
            'company' => 'Company Name'
        ];
    }
}
