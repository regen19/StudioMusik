<?php

namespace Database\Factories\Admin;

use App\Models\Admin\DataAlatModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataAlatModelFactory extends Factory
{
    protected $model = DataAlatModel::class;

    public function definition()
    {
        return [
            'nama_alat' => $this->faker->word(),
            'tipe_alat' => $this->faker->randomElement(['Petik', 'Tiup', 'Pukul']),
            'jumlah_alat' => $this->faker->numberBetween(1, 10),
            'biaya_perawatan' => $this->faker->numberBetween(5000, 20000),
            'status' => 'tersedia',
            'foto_alat' => null,
        ];
    }
}
