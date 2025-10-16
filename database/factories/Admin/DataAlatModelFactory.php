<?php

namespace Database\Factories\Admin;

use App\Models\Admin\DataAlatModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataAlatModelFactory extends Factory
{
    protected $model = DataAlatModel::class;

    public function definition(): array
    {
        return [
            'nama_alat' => $this->faker->word(),
            'tipe_alat' => $this->faker->randomElement(['Pukul', 'Tiup', 'Petik', 'Gesek']),
            'jumlah_alat' => $this->faker->numberBetween(1, 10),
            'biaya_perawatan' => $this->faker->numberBetween(10000, 50000),
            'status' => $this->faker->randomElement(['Tersedia', 'Dipinjam', 'Rusak']),
            'foto_alat' => null,
        ];
    }
}
