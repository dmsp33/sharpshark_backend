<?php

namespace Database\Factories;

use App\Models\Certificado;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificado::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->word,
        'version' => $this->faker->word,
        'autor' => $this->faker->word,
        'titulo' => $this->faker->text,
        'contenido' => $this->faker->text,
        'uuid' => $this->faker->word,
        'red' => $this->faker->word,
        'traza' => $this->faker->word,
        'ipfs' => $this->faker->word,
        'clave' => $this->faker->word,
        'bloqueado' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
