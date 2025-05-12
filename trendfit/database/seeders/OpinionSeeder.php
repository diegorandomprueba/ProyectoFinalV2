<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OpinionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existen opiniones
        if (DB::table('opiniones')->count() > 0) {
            return;
        }
        
        // Verificar que existan productos y usuarios
        $productosCount = DB::table('producte')->count();
        $usuariosCount = DB::table('users')->count();
        
        if ($productosCount == 0 || $usuariosCount == 0) {
            echo "No hay productos o usuarios para asociar con las opiniones.\n";
            return;
        }
        
        // Obtener IDs de productos y usuarios
        $productoIds = DB::table('producte')->pluck('id')->toArray();
        $usuarioIds = DB::table('users')->pluck('id')->toArray();
        $userNames = DB::table('users')->pluck('name', 'id')->toArray();
        
        // Comentarios de ejemplo
        $comentarios = [
            'Excelente producto, muy buena calidad. Material resistente y diseño elegante.',
            'Me ha gustado mucho, pero el envío tardó más de lo esperado. El producto es bueno.',
            'Fantástico, justo lo que buscaba. Relación calidad-precio excelente.',
            'El producto está bien, pero esperaba algo mejor por el precio. La calidad es aceptable.',
            'Muy cómodo y de buena calidad. Lo recomendaría a mis amigos.',
            'Increíble producto, superó mis expectativas. Excelente servicio también.',
            'Algo decepcionante. No cumple con lo que promete en la descripción.',
            'Buen producto, llegó antes de lo esperado. Buena experiencia de compra.',
            'Calidad excepcional, totalmente recomendable. Volveré a comprar.',
            'Calidad aceptable pero el precio es algo elevado para lo que ofrece.'
        ];
        
        // Opiniones a insertar
        $opiniones = [];
        
        // Crear opiniones aleatorias
        foreach ($productoIds as $productoId) {
            // Determinar número de opiniones (entre 0 y 5)
            $numOpiniones = rand(0, 5);
            
            for ($i = 0; $i < $numOpiniones; $i++) {
                // Seleccionar un usuario aleatorio
                $userId = $usuarioIds[array_rand($usuarioIds)];
                $userName = $userNames[$userId] ?? 'Usuario';
                
                // Valoración aleatoria entre 1 y 5
                $rating = rand(1, 5);
                
                // Seleccionar un comentario aleatorio
                $comment = $comentarios[array_rand($comentarios)];
                
                // Añadir opinión a la lista
                $opiniones[] = [
                    'product_id' => $productoId,
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'rating' => $rating,
                    'comment' => $comment,
                    'date' => now()->subDays(rand(1, 30))->format('Y-m-d H:i:s')
                ];
            }
        }
        
        // Insertar todas las opiniones de una vez
        if (!empty($opiniones)) {
            DB::table('opiniones')->insert($opiniones);
            echo "Se han insertado " . count($opiniones) . " opiniones.\n";
        } else {
            echo "No se han generado opiniones.\n";
        }
    }
}