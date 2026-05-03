<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);
    }

    /** @test */
    public function customer_creation_shows_success_flash_message()
    {
        $this->actingAs($this->admin);

        $customerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'status' => 'active',
            'phone' => '1234567890',
            'region' => 'Region',
            'province' => 'Province',
            'municipality' => 'Municipality',
            'barangay' => 'Barangay',
            'street' => '123 Main St',
            'zip_code' => '12345',
            'latitude' => '14.5995',
            'longitude' => '120.9842',
            'dob' => '1990-01-01',
            'gender' => 'male'
        ];

        $response = $this->post(route('customers.store'), $customerData);

        $response->assertRedirect(route('customers.list'));
        $response->assertSessionHas('success', 'Customer created successfully and password sent to email.');
    }

    /** @test */
    public function customer_update_shows_success_flash_message()
    {
        $this->actingAs($this->admin);

        // Create a customer first
        $customerUser = User::create([
            'name' => 'Existing Customer',
            'email' => 'existing@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $customer = Customer::create([
            'user_id' => $customerUser->id,
            'account_number' => 'ACC-TEST123',
            'status' => 'active',
            'phone' => '0987654321'
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'active'
        ];

        $response = $this->put(route('customers.update', $customer->id), $updateData);

        $response->assertRedirect(route('customers.list'));
        $response->assertSessionHas('success', 'Customer updated successfully.');
    }

    /** @test */
    public function customer_deletion_shows_success_flash_message()
    {
        $this->actingAs($this->admin);

        // Create a customer first
        $customerUser = User::create([
            'name' => 'Customer to Delete',
            'email' => 'delete@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $customer = Customer::create([
            'user_id' => $customerUser->id,
            'account_number' => 'ACC-DELETE123',
            'status' => 'active',
            'phone' => '1111111111'
        ]);

        $response = $this->delete(route('customers.destroy', $customer->id));

        $response->assertRedirect(route('customers.list'));
        $response->assertSessionHas('success', 'Customer deleted successfully.');
    }

    /** @test */
    public function customer_creation_validation_error_shows_error_flash_message()
    {
        $this->actingAs($this->admin);

        // Try to create customer with duplicate email
        User::create([
            'name' => 'Existing User',
            'email' => 'duplicate@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $customerData = [
            'name' => 'John Doe',
            'email' => 'duplicate@example.com', // Duplicate email
            'status' => 'active'
        ];

        $response = $this->post(route('customers.store'), $customerData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function customer_update_validation_error_shows_error_flash_message()
    {
        $this->actingAs($this->admin);

        // Create two customers
        $customerUser1 = User::create([
            'name' => 'Customer 1',
            'email' => 'customer1@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $customer1 = Customer::create([
            'user_id' => $customerUser1->id,
            'account_number' => 'ACC-001',
            'status' => 'active'
        ]);

        $customerUser2 = User::create([
            'name' => 'Customer 2',
            'email' => 'customer2@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer'
        ]);

        $customer2 = Customer::create([
            'user_id' => $customerUser2->id,
            'account_number' => 'ACC-002',
            'status' => 'active'
        ]);

        // Try to update customer1 with customer2's email
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'customer2@example.com', // Duplicate email
            'status' => 'active'
        ];

        $response = $this->put(route('customers.update', $customer1->id), $updateData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }
}
