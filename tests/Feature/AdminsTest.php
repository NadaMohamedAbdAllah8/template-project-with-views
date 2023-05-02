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
    private $email;
    private $name;
    private $password;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getAdmin();
        $this->email = 'new_email@new.new';
        $this->name = 'new';
        $this->password = 'new';
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
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getTestAdminData());

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');
    }

    public function test_creating_admin_fails_on_duplicated_emails()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getTestAdminData());

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');

        // save the same admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getTestAdminData());

        $response->assertInvalid(['email']);
    }

    public function test_newly_created_admin_is_returned_in_data()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getTestAdminData());

        $response->assertStatus(302);
        $response->assertRedirect('/admins');

        // admin is returned in the data
        // get all the admin data
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');

        // the admin is returned from the data function
        $data = json_decode($response->content(), true)['data'];
        $data_count = count($data);
        $latest_index = $data_count - 1;

        $this->assertEquals($this->email, $data[$latest_index]['email']);
        $this->assertEquals($this->name, $data[$latest_index]['name']);
    }

    private function getTestAdminData(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ];
    }
    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }
}