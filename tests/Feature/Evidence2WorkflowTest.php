<?php

namespace Tests\Feature;

use App\Customer;
use App\EvidencePhoto;
use App\Material;
use App\Order;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Evidence2WorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_home_works()
    {
        $this->get('/')->assertOk()->assertSee('Invoice number');
    }

    public function test_valid_invoice_search_returns_in_process_information()
    {
        $this->get(route('orders.track', ['invoice_number' => 'HAL-1002']))
            ->assertOk()
            ->assertSee('In Process')
            ->assertSee('Current process')
            ->assertSee('Process date');
    }

    public function test_delivered_invoice_search_shows_delivery_evidence()
    {
        $this->get(route('orders.track', ['invoice_number' => 'HAL-1004']))
            ->assertOk()
            ->assertSee('Delivered')
            ->assertSee('Delivery evidence')
            ->assertSee('demo-delivery.png');
    }

    public function test_unknown_invoice_search_returns_a_validation_message()
    {
        $this->get(route('orders.track', ['invoice_number' => 'DOES-NOT-EXIST']))
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('invoice_number');
    }

    public function test_guest_cannot_access_dashboard()
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_users()
    {
        $this->get(route('users.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_orders()
    {
        $this->get(route('orders.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $this->actingAs($this->administrator())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Archived Orders');
    }

    public function test_authenticated_user_can_create_a_user()
    {
        $role = Role::where('name', 'Warehouse')->firstOrFail();

        $this->actingAs($this->administrator())->post(route('users.store'), [
            'name' => 'Warehouse Test User',
            'email' => 'warehouse.user@halcon.test',
            'role_id' => $role->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => 1,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'warehouse.user@halcon.test', 'role_id' => $role->id]);
    }

    public function test_authenticated_user_can_edit_a_user()
    {
        $user = User::where('email', 'sales@halcon.test')->firstOrFail();

        $this->actingAs($this->administrator())->put(route('users.update', $user), [
            'name' => 'Updated Sales User',
            'email' => $user->email,
            'role_id' => $user->role_id,
            'password' => '',
            'password_confirmation' => '',
            'is_active' => 1,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Sales User']);
    }

    public function test_authenticated_user_can_mark_a_user_inactive()
    {
        $user = User::where('email', 'sales@halcon.test')->firstOrFail();

        $this->actingAs($this->administrator())->put(route('users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'password' => '',
            'password_confirmation' => '',
            'is_active' => 0,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => 0]);
    }

    public function test_orders_are_listed_newest_first()
    {
        $expected = Order::orderByDesc('order_date')->pluck('invoice_number')->all();

        $this->actingAs($this->administrator())
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSeeInOrder($expected);
    }

    public function test_authenticated_user_can_create_an_order()
    {
        $this->actingAs($this->administrator())
            ->post(route('orders.store'), $this->newOrderPayload('HAL-2001'))
            ->assertRedirect();

        $this->assertDatabaseHas('orders', ['invoice_number' => 'HAL-2001', 'status' => Order::STATUS_ORDERED]);
        $this->assertDatabaseHas('order_details', ['quantity' => 3]);
    }

    public function test_authenticated_user_can_update_an_order()
    {
        $order = Order::where('invoice_number', 'HAL-1001')->firstOrFail();
        $payload = $this->existingOrderPayload($order);
        $payload['delivery_address'] = 'Updated delivery address';

        $this->actingAs($this->administrator())
            ->put(route('orders.update', $order), $payload)
            ->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'delivery_address' => 'Updated delivery address']);
    }

    public function test_authenticated_user_can_change_order_status()
    {
        $order = Order::where('invoice_number', 'HAL-1001')->firstOrFail();
        $payload = $this->existingOrderPayload($order);
        $payload['status'] = Order::STATUS_IN_PROCESS;

        $this->actingAs($this->administrator())->put(route('orders.update', $order), $payload);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::STATUS_IN_PROCESS]);
    }

    public function test_authenticated_user_can_upload_valid_evidence()
    {
        Storage::fake('public');
        $order = Order::where('invoice_number', 'HAL-1001')->firstOrFail();
        $payload = $this->existingOrderPayload($order);
        $payload['status'] = Order::STATUS_IN_ROUTE;
        $payload['evidence_photo'] = UploadedFile::fake()->image('loaded-unit.jpg', 800, 600);

        $this->actingAs($this->administrator())->put(route('orders.update', $order), $payload);

        $evidence = EvidencePhoto::where('order_id', $order->id)->firstOrFail();
        $this->assertSame(EvidencePhoto::TYPE_LOADED, $evidence->type);
        Storage::disk('public')->assertExists($evidence->file_path);
    }

    public function test_authenticated_user_can_view_an_order()
    {
        $order = Order::where('invoice_number', 'HAL-1004')->firstOrFail();

        $this->actingAs($this->administrator())
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee('Photographic evidence');
    }

    public function test_authenticated_user_can_archive_an_order()
    {
        $order = Order::where('invoice_number', 'HAL-1001')->firstOrFail();

        $this->actingAs($this->administrator())
            ->delete(route('orders.destroy', $order))
            ->assertRedirect(route('orders.index'));

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    public function test_archived_order_disappears_from_normal_list()
    {
        $order = Order::where('invoice_number', 'HAL-1001')->firstOrFail();
        $order->delete();

        $this->actingAs($this->administrator())
            ->get(route('orders.index'))
            ->assertDontSee('HAL-1001');
    }

    public function test_archived_order_appears_in_archive()
    {
        $this->actingAs($this->administrator())
            ->get(route('archived-orders.index'))
            ->assertOk()
            ->assertSee('HAL-1005');
    }

    public function test_authenticated_user_can_restore_an_order()
    {
        $order = Order::onlyTrashed()->where('invoice_number', 'HAL-1005')->firstOrFail();

        $this->actingAs($this->administrator())
            ->patch(route('archived-orders.restore', $order->id))
            ->assertRedirect(route('archived-orders.index'));

        $this->assertNull(Order::withTrashed()->findOrFail($order->id)->deleted_at);
    }

    public function test_restored_order_returns_to_normal_list()
    {
        $order = Order::onlyTrashed()->where('invoice_number', 'HAL-1005')->firstOrFail();
        $order->restore();

        $this->actingAs($this->administrator())
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('HAL-1005');
    }

    private function administrator()
    {
        return User::where('email', 'admin@halcon.test')->firstOrFail();
    }

    private function newOrderPayload($invoice)
    {
        return [
            'invoice_number' => $invoice,
            'customer_id' => Customer::firstOrFail()->id,
            'order_date' => now()->format('Y-m-d H:i:s'),
            'delivery_address' => 'Test delivery address',
            'notes' => 'Automated test order.',
            'status' => Order::STATUS_ORDERED,
            'material_id' => Material::firstOrFail()->id,
            'quantity' => 3,
        ];
    }

    private function existingOrderPayload(Order $order)
    {
        $order->load('details');

        return [
            'invoice_number' => $order->invoice_number,
            'customer_id' => $order->customer_id,
            'order_date' => $order->order_date->format('Y-m-d H:i:s'),
            'delivery_address' => $order->delivery_address,
            'notes' => $order->notes,
            'status' => $order->status,
            'material_id' => $order->details->first()->material_id,
            'quantity' => $order->details->first()->quantity,
        ];
    }
}
