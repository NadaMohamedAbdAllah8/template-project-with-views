<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminsTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->getAdmin();
    }

    public function test_welcome_page_opens_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_page_does_not_open_for_unauthenticated()
    {
        $response = $this->get('/admins');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_index_page_opens_for_admin()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins');

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.admins.index');
    }

    public function test_index_page_is_empty()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins');

        $response->assertSee(config('global.no_records'));
    }

    public function test_create_page_does_not_open_for_unauthenticated()
    {
        $response = $this->get('/admins/create');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_creation_validation()
    {
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            [
                'email' => 'email',
                'name' => '1',
                'password' => '',
            ]
        );

        $response->assertStatus(302);
        $response->assertInvalid(['email', 'name', 'password']);
    }

    public function test_create_an_admin_successfully()
    {
        $email = 'new_email@new.new';
        $name = 'new';
        $password = 'new';
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            [
                'email' => $email,
                'name' => $name,
                'password' => $password,
            ]);

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');
    }

    public function test_newly_created_admin_is_returned_in_data()
    {
        $email = 'new_email@new.new';
        $name = 'new';
        $password = 'new';
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            [
                'email' => $email,
                'name' => $name,
                'password' => $password,
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admins');

        // admin is returned in the data
        // get all the admin data
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');

        // the admin is returned from the data function
        $data = json_decode($response->content(), true)['data'];
        $data_count = count($data);
        $latest_index = $data_count - 1;

        $this->assertEquals($email, $data[$latest_index]['email']);
        $this->assertEquals($name, $data[$latest_index]['name']);
    }

    public function test_newly_created_admin_is_in_index_page()
    {
        $email = 'new_email@new.new';
        $name = 'new';
        $password = 'new';
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            [
                'email' => $email,
                'name' => $name,
                'password' => $password,
            ]);
        $response->assertStatus(302);
        $response->assertRedirect('/admins');

        $response = $this->actingAs($this->admin, 'admin')->get('/admins');

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.admins.index');
        // $response->assertDontSee(config('global.no_records'));
        $response->assertSee($email);
        $response->assertSee($name);

    }

    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }
}