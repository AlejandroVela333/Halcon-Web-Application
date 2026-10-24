<?php

use App\Customer;
use App\EvidencePhoto;
use App\Material;
use App\Order;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $administratorRole = Role::where('name', 'Administrator')->firstOrFail();
        $salesRole = Role::where('name', 'Sales')->firstOrFail();
        $routeRole = Role::where('name', 'Route')->firstOrFail();

        User::updateOrCreate(
            ['email' => 'admin@halcon.test'],
            ['role_id' => $administratorRole->id, 'name' => 'Halcon Administrator', 'password' => Hash::make('password'), 'is_active' => true]
        );
        $sales = User::updateOrCreate(
            ['email' => 'sales@halcon.test'],
            ['role_id' => $salesRole->id, 'name' => 'Halcon Sales', 'password' => Hash::make('password'), 'is_active' => true]
        );
        $route = User::updateOrCreate(
            ['email' => 'route.inactive@halcon.test'],
            ['role_id' => $routeRole->id, 'name' => 'Inactive Route User', 'password' => Hash::make('password'), 'is_active' => false]
        );

        $customerOne = Customer::updateOrCreate(
            ['customer_number' => 'CUST-001'],
            ['name' => 'Test Customer One', 'fiscal_data' => 'Demonstration fiscal data.']
        );
        $customerTwo = Customer::updateOrCreate(
            ['customer_number' => 'CUST-002'],
            ['name' => 'Test Customer Two', 'fiscal_data' => 'Demonstration fiscal data.']
        );

        $cement = Material::updateOrCreate(['name' => 'Cement Bag'], ['stock' => 120]);
        $steel = Material::updateOrCreate(['name' => 'Steel Rod'], ['stock' => 80]);

        $orders = [
            ['invoice_number' => 'HAL-1001', 'customer_id' => $customerOne->id, 'order_date' => now(), 'status' => Order::STATUS_ORDERED, 'material' => $cement, 'quantity' => 10],
            ['invoice_number' => 'HAL-1002', 'customer_id' => $customerOne->id, 'order_date' => now()->subDay(), 'status' => Order::STATUS_IN_PROCESS, 'material' => $steel, 'quantity' => 8],
            ['invoice_number' => 'HAL-1003', 'customer_id' => $customerTwo->id, 'order_date' => now()->subDays(2), 'status' => Order::STATUS_IN_ROUTE, 'material' => $cement, 'quantity' => 15],
            ['invoice_number' => 'HAL-1004', 'customer_id' => $customerTwo->id, 'order_date' => now()->subDays(3), 'status' => Order::STATUS_DELIVERED, 'material' => $steel, 'quantity' => 12],
            ['invoice_number' => 'HAL-1005', 'customer_id' => $customerOne->id, 'order_date' => now()->subDays(4), 'status' => Order::STATUS_IN_PROCESS, 'material' => $cement, 'quantity' => 5, 'archived' => true],
        ];

        foreach ($orders as $data) {
            $order = Order::withTrashed()->updateOrCreate(
                ['invoice_number' => $data['invoice_number']],
                [
                    'customer_id' => $data['customer_id'],
                    'created_by' => $sales->id,
                    'order_date' => $data['order_date'],
                    'delivery_address' => '100 Demonstration Avenue',
                    'notes' => 'Reproducible Evidence 2 test order.',
                    'status' => $data['status'],
                    'deleted_at' => null,
                ]
            );

            $order->details()->updateOrCreate(
                ['material_id' => $data['material']->id],
                ['quantity' => $data['quantity']]
            );

            if (!empty($data['archived'])) {
                $order->delete();
            }
        }

        $this->createEvidenceImage('order-evidence/demo-loaded.png', 'IN ROUTE TEST EVIDENCE', [31, 111, 235]);
        $this->createEvidenceImage('order-evidence/demo-delivery.png', 'DELIVERY TEST EVIDENCE', [22, 163, 74]);

        $inRoute = Order::where('invoice_number', 'HAL-1003')->firstOrFail();
        $delivered = Order::where('invoice_number', 'HAL-1004')->firstOrFail();

        EvidencePhoto::updateOrCreate(
            ['order_id' => $inRoute->id, 'type' => EvidencePhoto::TYPE_LOADED],
            ['uploaded_by' => $route->id, 'file_path' => 'order-evidence/demo-loaded.png', 'uploaded_at' => now()->subDays(2)]
        );
        EvidencePhoto::updateOrCreate(
            ['order_id' => $delivered->id, 'type' => EvidencePhoto::TYPE_DELIVERY],
            ['uploaded_by' => $route->id, 'file_path' => 'order-evidence/demo-delivery.png', 'uploaded_at' => now()->subDays(3)]
        );
    }

    private function createEvidenceImage($path, $label, array $color)
    {
        Storage::disk('public')->makeDirectory('order-evidence');
        $fullPath = Storage::disk('public')->path($path);
        $image = imagecreatetruecolor(640, 360);
        $background = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 640, 360, $background);
        imagestring($image, 5, 180, 150, $label, $white);
        imagestring($image, 3, 225, 185, 'LOCAL TEST IMAGE', $white);
        imagepng($image, $fullPath);
        imagedestroy($image);
    }
}
