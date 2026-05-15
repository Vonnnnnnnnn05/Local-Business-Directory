<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_search_approved_businesses(): void
    {
        $category = Category::create(['name' => 'Clinics', 'slug' => 'clinics']);
        $owner = User::factory()->create(['role' => 'owner']);

        Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Community Care Clinic',
            'slug' => 'community-care-clinic',
            'contact_number' => '555-1111',
            'address' => '10 Health Road',
            'city' => 'Northside',
            'description' => 'Local primary care and family medicine clinic.',
            'status' => 'approved',
        ]);

        $this->get('/?search=Community&category=clinics&location=Northside')
            ->assertOk()
            ->assertSee('Community Care Clinic');
    }

    public function test_owner_cannot_edit_another_owners_business(): void
    {
        $category = Category::create(['name' => 'Stores', 'slug' => 'stores']);
        $owner = User::factory()->create(['role' => 'owner']);
        $otherOwner = User::factory()->create(['role' => 'owner']);

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Corner Store',
            'slug' => 'corner-store',
            'contact_number' => '555-2222',
            'address' => '45 Main Street',
            'description' => 'Daily essentials and household goods for local residents.',
            'status' => 'pending',
        ]);

        $this->actingAs($otherOwner)
            ->get(route('owner.businesses.edit', $business))
            ->assertForbidden();
    }

    public function test_admin_can_approve_business(): void
    {
        $category = Category::create(['name' => 'Restaurants', 'slug' => 'restaurants']);
        $owner = User::factory()->create(['role' => 'owner']);
        $admin = User::factory()->create(['role' => 'admin']);

        $business = Business::create([
            'owner_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'River Grill',
            'slug' => 'river-grill',
            'contact_number' => '555-3333',
            'address' => '22 River Lane',
            'description' => 'Casual restaurant serving grilled meals and local dishes.',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.businesses.status', [$business, 'approved']))
            ->assertRedirect();

        $this->assertDatabaseHas('businesses', [
            'id' => $business->id,
            'status' => 'approved',
        ]);
    }
}
