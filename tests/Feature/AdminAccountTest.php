<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /** @test */
    public function admin_can_access_profile_page()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.account.profile'));

        $response->assertStatus(200);
        $response->assertSee($this->admin->email);
    }

    /** @test */
    public function admin_can_update_profile()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.account.profile.update'), [
                'name' => 'Updated Name',
                'username' => 'updated_username',
                'email' => 'updated@example.com',
                'lang' => 'en',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'name' => 'Updated Name',
            'username' => 'updated_username',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function admin_can_access_security_page()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.account.security'));

        $response->assertStatus(200);
        $response->assertSee(__('Change Password'));
    }

    /** @test */
    public function admin_can_update_password()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.account.password.update'), [
                'current_password' => 'password123',
                'password' => 'new_password123',
                'password_confirmation' => 'new_password123',
            ]);

        $response->assertStatus(302);
        $this->assertTrue(Hash::check('new_password123', $this->admin->fresh()->password));
    }
}
