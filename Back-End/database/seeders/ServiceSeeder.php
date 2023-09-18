<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = ['Wi-Fi Gratuito', 'Colazione Inclusa', 'Parcheggio', 'Servizio in Camera', 'Piscina e Spa', 'Servizio di Lavanderia', 'Ristorante', 'Centro Business', 'Servizio di Noleggio Auto'];

        foreach ($services as $serviceName) {
            $service = new Service();

            $service->name = $serviceName;

            $service->save();
        }
    }
}
