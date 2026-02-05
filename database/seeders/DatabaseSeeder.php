<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Service;
use App\Models\CleaningJob;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create staff users
        $admin = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'admin@maidtoglow.com',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '(404) 555-0100',
            'hourly_rate' => 25.00,
            'color' => '#3B82F6',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Mike Chen',
            'email' => 'manager@maidtoglow.com',
            'password' => 'password',
            'role' => 'manager',
            'phone' => '(404) 555-0101',
            'hourly_rate' => 22.00,
            'color' => '#8B5CF6',
            'is_active' => true,
        ]);

        $tech1 = User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@maidtoglow.com',
            'password' => 'password',
            'role' => 'tech',
            'phone' => '(404) 555-0102',
            'hourly_rate' => 18.00,
            'color' => '#10B981',
            'is_active' => true,
        ]);

        $tech2 = User::create([
            'name' => 'James Wilson',
            'email' => 'james@maidtoglow.com',
            'password' => 'password',
            'role' => 'tech',
            'phone' => '(404) 555-0103',
            'hourly_rate' => 18.00,
            'color' => '#F59E0B',
            'is_active' => true,
        ]);

        $tech3 = User::create([
            'name' => 'Ashley Brown',
            'email' => 'ashley@maidtoglow.com',
            'password' => 'password',
            'role' => 'tech',
            'phone' => '(404) 555-0104',
            'hourly_rate' => 17.00,
            'color' => '#EF4444',
            'is_active' => true,
        ]);

        // Create services
        $standardClean = Service::create([
            'name' => 'Standard Clean',
            'description' => 'Regular maintenance cleaning including dusting, vacuuming, mopping, bathroom and kitchen cleaning.',
            'base_price' => 89.00,
            'price_per_bedroom' => 25.00,
            'price_per_bathroom' => 20.00,
            'price_per_sqft' => 0,
            'estimated_minutes' => 120,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $deepClean = Service::create([
            'name' => 'Deep Clean',
            'description' => 'Thorough cleaning including baseboards, inside appliances, windows, and detailed attention to all areas.',
            'base_price' => 149.00,
            'price_per_bedroom' => 40.00,
            'price_per_bathroom' => 35.00,
            'price_per_sqft' => 0,
            'estimated_minutes' => 180,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $moveInOut = Service::create([
            'name' => 'Move In/Out Clean',
            'description' => 'Complete top-to-bottom cleaning for moving. Includes inside cabinets, appliances, and all surfaces.',
            'base_price' => 199.00,
            'price_per_bedroom' => 50.00,
            'price_per_bathroom' => 45.00,
            'price_per_sqft' => 0.02,
            'estimated_minutes' => 240,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create customers (Atlanta area)
        $customers = [
            [
                'name' => 'Jennifer Smith',
                'email' => 'jennifer.smith@email.com',
                'password' => 'customer123',
                'phone' => '(404) 555-1001',
                'address' => '1234 Peachtree St NE',
                'city' => 'Atlanta',
                'state' => 'GA',
                'zip' => '30309',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'square_feet' => 1800,
                'has_pets' => true,
                'pet_details' => '1 golden retriever (friendly)',
                'access_instructions' => 'Gate code: 1234. Key under mat.',
            ],
            [
                'name' => 'Robert Thompson',
                'email' => 'robert.t@email.com',
                'password' => 'customer123',
                'phone' => '(404) 555-1002',
                'address' => '567 Buckhead Ave',
                'city' => 'Atlanta',
                'state' => 'GA',
                'zip' => '30305',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'square_feet' => 2400,
                'has_pets' => false,
                'access_instructions' => 'Ring doorbell. If no answer, code is 5678.',
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@email.com',
                'password' => 'customer123',
                'phone' => '(404) 555-1003',
                'address' => '890 Midtown Pkwy',
                'city' => 'Atlanta',
                'state' => 'GA',
                'zip' => '30308',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'square_feet' => 1200,
                'has_pets' => true,
                'pet_details' => '2 cats (indoor)',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@email.com',
                'password' => 'customer123',
                'phone' => '(404) 555-1004',
                'address' => '321 Virginia Highland',
                'city' => 'Atlanta',
                'state' => 'GA',
                'zip' => '30306',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'square_feet' => 1600,
                'has_pets' => false,
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.w@email.com',
                'password' => 'customer123',
                'phone' => '(404) 555-1005',
                'address' => '456 Decatur St',
                'city' => 'Decatur',
                'state' => 'GA',
                'zip' => '30030',
                'bedrooms' => 4,
                'bathrooms' => 2,
                'square_feet' => 2000,
                'has_pets' => true,
                'pet_details' => 'Small dog in crate during cleaning',
                'access_instructions' => 'Lockbox on back door. Code: 9876',
            ],
        ];

        $customerModels = [];
        foreach ($customers as $customerData) {
            $customerModels[] = Customer::create($customerData);
        }

        // Create jobs - mix of past, today, and future
        $techs = [$tech1, $tech2, $tech3];
        $services = [$standardClean, $deepClean, $moveInOut];

        // Past completed jobs
        foreach ($customerModels as $i => $customer) {
            $service = $services[$i % 3];
            $tech = $techs[$i % 3];
            $price = $service->calculatePrice($customer->bedrooms, $customer->bathrooms, $customer->square_feet);

            CleaningJob::create([
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'assigned_to' => $tech->id,
                'scheduled_date' => now()->subDays(rand(3, 14)),
                'scheduled_time' => sprintf('%02d:00', rand(8, 14)),
                'estimated_duration' => $service->estimated_minutes,
                'quoted_price' => $price,
                'final_price' => $price,
                'status' => 'completed',
                'is_recurring' => $i % 2 === 0,
                'recurrence_frequency' => $i % 2 === 0 ? 'biweekly' : 'one_time',
                'started_at' => now()->subDays(rand(3, 14))->setTime(rand(8, 10), 0),
                'completed_at' => now()->subDays(rand(3, 14))->setTime(rand(11, 14), 0),
                'rating' => rand(4, 5),
                'review' => $i % 2 === 0 ? 'Great job! Very thorough.' : null,
            ]);
        }

        // Today's jobs
        CleaningJob::create([
            'customer_id' => $customerModels[0]->id,
            'service_id' => $standardClean->id,
            'assigned_to' => $tech1->id,
            'scheduled_date' => today(),
            'scheduled_time' => '09:00',
            'estimated_duration' => 120,
            'quoted_price' => 159.00,
            'status' => 'completed',
            'started_at' => today()->setTime(9, 5),
            'completed_at' => today()->setTime(11, 15),
        ]);

        CleaningJob::create([
            'customer_id' => $customerModels[1]->id,
            'service_id' => $deepClean->id,
            'assigned_to' => $tech1->id,
            'scheduled_date' => today(),
            'scheduled_time' => '13:00',
            'estimated_duration' => 180,
            'quoted_price' => 289.00,
            'status' => 'in_progress',
            'started_at' => today()->setTime(13, 10),
        ]);

        CleaningJob::create([
            'customer_id' => $customerModels[2]->id,
            'service_id' => $standardClean->id,
            'assigned_to' => $tech2->id,
            'scheduled_date' => today(),
            'scheduled_time' => '10:00',
            'estimated_duration' => 90,
            'quoted_price' => 129.00,
            'status' => 'scheduled',
        ]);

        CleaningJob::create([
            'customer_id' => $customerModels[3]->id,
            'service_id' => $standardClean->id,
            'assigned_to' => $tech2->id,
            'scheduled_date' => today(),
            'scheduled_time' => '14:00',
            'estimated_duration' => 120,
            'quoted_price' => 154.00,
            'status' => 'scheduled',
        ]);

        CleaningJob::create([
            'customer_id' => $customerModels[4]->id,
            'service_id' => $deepClean->id,
            'assigned_to' => $tech3->id,
            'scheduled_date' => today(),
            'scheduled_time' => '09:30',
            'estimated_duration' => 180,
            'quoted_price' => 269.00,
            'status' => 'scheduled',
        ]);

        // Future scheduled jobs
        for ($day = 1; $day <= 7; $day++) {
            foreach ($techs as $techIndex => $tech) {
                $customer = $customerModels[($day + $techIndex) % count($customerModels)];
                $service = $services[($day + $techIndex) % 3];
                $price = $service->calculatePrice($customer->bedrooms, $customer->bathrooms, $customer->square_feet);

                CleaningJob::create([
                    'customer_id' => $customer->id,
                    'service_id' => $service->id,
                    'assigned_to' => $tech->id,
                    'scheduled_date' => today()->addDays($day),
                    'scheduled_time' => sprintf('%02d:00', 8 + ($techIndex * 3)),
                    'estimated_duration' => $service->estimated_minutes,
                    'quoted_price' => $price,
                    'status' => 'scheduled',
                    'is_recurring' => $day % 3 === 0,
                    'recurrence_frequency' => $day % 3 === 0 ? 'weekly' : 'one_time',
                ]);
            }
        }
    }
}
