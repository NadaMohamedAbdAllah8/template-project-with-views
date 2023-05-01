<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_opens()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_redirect_to_login_page_when_not_authenticated()
    {
        $response = $this->get('/subscribers');

        $response->assertRedirect('/login');
    }

    public function test_an_admin_can_login()
    {
        $this->seed(AdminSeeder::class);

        $admin = Admin::first();

        $response = $this->post('/login-post', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/subscribers');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_login_failed_with_an_email_that_does_not_exist_in_admins_table()
    {
        $response = $this->post('/login-post', [
            'email' => 'invalid@example.com',
            'password' => 'invalid_password',
        ]);

        // the email does not exist in the admins table
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_failed_with_wrong_password()
    {
        $this->seed(AdminSeeder::class);

        $response = $this->post('/login-post', [
            'email' => 'admin@admin.com',
            'password' => 'pass',
        ]);

        $this->assertEquals('Bad credentials', session('error'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_logout_successfully()
    {
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    }
}
