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
    private $email_updated;
    private $name_updated;
    private $password;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getAdmin();
        $this->email = 'new_email@new.new';
        $this->name = 'new';
        $this->password = 'new';
        $this->email_updated = 'new_email_updated@new.new';
        $this->name_updated = 'new_updated';
    }

    public function test_welcome_page_opens_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    // start: index test
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
    // end: index test

    // start: create test
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
            $this->getAdminData());

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');
        // saved in the database
        $this->assertDatabaseHas('admins', ['email' => $this->email, 'name' => $this->name]);

        $last_admin = Admin::latest('id')->first();
        $this->assertEquals($this->email, $last_admin->email);
        $this->assertEquals($this->name, $last_admin->name);
    }

    public function test_creating_admin_fails_on_duplicated_emails()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getAdminData());

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');

        // save the same admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getAdminData());

        $response->assertInvalid(['email']);
    }

    public function test_newly_created_admin_is_returned_in_data()
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->post('/admins',
            $this->getAdminData());

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
    // end: create test

    // start: edit test
    public function test_edit_page_does_not_open_for_unauthenticated()
    {
        $response = $this->get('/admins/edit');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_admin_editing_validation()
    {
        // create an admin
        $admin = $this->getAdmin();

        $response = $this->actingAs($this->admin, 'admin')->patch('/admins/' . $admin->id,
            [
                //  'id'=>$admin->id
                'email' => 'email',
                'name' => '1',
            ]
        );

        $response->assertStatus(302);
        $response->assertInvalid(['email', 'name']);
    }

    public function test_edit_an_admin_successfully()
    {
        // create an admin
        $admin = $this->getAdmin();
        $response = $this->actingAs($this->admin, 'admin')
            ->get('/admins/' . $admin->id . '/edit/');
        $response->assertStatus(200);

        // save admin
        $response = $this->actingAs($this->admin, 'admin')->patch('/admins/' . $admin->id,
            $this->getAdminData());

        // redirecting to admins index
        $response->assertStatus(302);
        $response->assertRedirect('/admins');
        // saved in the database
        $this->assertDatabaseHas('admins', ['email' => $this->email, 'name' => $this->name]);

        $last_admin = Admin::latest('id')->first();
        $this->assertEquals($this->email, $last_admin->email);
        $this->assertEquals($this->name, $last_admin->name);
    }

    // start: delete test

    public function test_delete_does_not_open_for_unauthenticated()
    {
        $response = $this->delete('/admins/delete');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_deleting_admin_successfully()
    {
        // creating a new admin
        $admin = $this->createAdmin();

        $response = $this->actingAs($this->admin, 'admin')->delete('/admins/' . $admin->id);

        // correct response
        $response->assertStatus(204);
        // missing in the database
        $this->assertDatabaseMissing('admins',
            ['email' => $this->email, 'name' => $this->name]);
        // does not exist in the data route data
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');
        $data = json_decode($response->content(), true)['data'];
        $data_count = count($data);
        $latest_index = $data_count - 1;
        $this->assertNotEquals($this->email, $data[$latest_index]['email']);
        $this->assertNotEquals($this->name, $data[$latest_index]['name']);
    }

    public function test_deleting_admin_unsuccessfully()
    {
        // creating a new admin
        $admin = $this->createAdmin();
        $wrong_id = $admin->id + 1;
        $response = $this->actingAs($this->admin, 'admin')->delete('/admins/' . $wrong_id);

        // correct response
        $response->assertStatus(302);
        // The model is not found. Please, check you id
        $response->assertSessionHas('error', 'The model is not found. Please, check you id');
        // missing in the database
        $this->assertDatabaseHas('admins',
            ['email' => $this->email, 'name' => $this->name]);
        // does not exist in the data route data
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');
        $data = json_decode($response->content(), true)['data'];
        $data_count = count($data);
        $latest_index = $data_count - 1;
        $this->assertEquals($this->email, $data[$latest_index]['email']);
        $this->assertEquals($this->name, $data[$latest_index]['name']);
    }
    // end: delete test

    private function createAdmin(): Admin
    {
        return Admin::create($this->getCreatedAdminData());
    }

    private function getLatestAdminIndexFromData(): int
    {
        // does not exist in the data route data
        $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');
        // the admin is returned from the data function
        $data = json_decode($response->content(), true)['data'];
        $data_count = count($data);
        return $data_count - 1;
    }

    // public function test_deleting_admin_with_wrong_id()
    // {
    //     $response = $this->actingAs($this->admin, 'admin')->get('/admins/create');
    //     $response->assertStatus(200);

    //     // save admin
    //     $response = $this->actingAs($this->admin, 'admin')->post('/admins',
    //         $this->getAdminData());

    //     $response->assertStatus(302);
    //     $response->assertRedirect('/admins');

    //     // admin is returned in the data
    //     // get all the admin data
    //     $response = $this->actingAs($this->admin, 'admin')->get('/admins/data');

    //     // the admin is returned from the data function
    //     $data = json_decode($response->content(), true)['data'];
    //     $data_count = count($data);
    //     $latest_index = $data_count - 1;

    //     $this->assertEquals($this->email, $data[$latest_index]['email']);
    //     $this->assertEquals($this->name, $data[$latest_index]['name']);
    // }

    private function getAdminData(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
        ];
    }

    private function getAdminUpdatedData(): array
    {
        return [
            'email' => $this->email_updated,
            'name' => $this->email_updated,
        ];
    }

    private function getCreatedAdminData(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => bcrypt($this->password),
        ];
    }

    private function getAdmin(): Admin
    {
        $this->seed(AdminSeeder::class);
        return Admin::first();
    }
}
