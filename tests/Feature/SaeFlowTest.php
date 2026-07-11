<?php

namespace Tests\Feature;

use App\Models\{Asistencia, ClaseProfesor, Estudiante, Grado, Materia, Seccion, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaeFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_middleware_protects_admin_routes(): void
    {
        $student = User::factory()->create(['role' => 'estudiante']);

        $this->actingAs($student)->get('/admin/dashboard')->assertForbidden();
        $this->get('/estudiante/dashboard')->assertOk();
    }

    public function test_admin_can_create_a_student_with_unique_qr_token(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $grado = Grado::create(['nombre' => '1er año']);
        $seccion = Seccion::create(['nombre' => 'A']);

        $response = $this->actingAs($admin)->post('/admin/estudiantes', [
            'name' => 'Ana Pérez', 'email' => 'ana@example.com',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
            'grado_id' => $grado->id, 'seccion_id' => $seccion->id,
            'matricula' => 'MAT-001',
        ]);

        $student = Estudiante::firstOrFail();
        $response->assertRedirect(route('admin.estudiantes.show', $student));
        $this->assertSame(64, strlen($student->qr_token));
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com', 'role' => 'estudiante']);
    }

    public function test_professor_can_register_attendance_only_once_per_class_and_day(): void
    {
        [$professor, $student, $class] = $this->attendanceScenario();

        $payload = ['qr_token' => $student->qr_token, 'clase_profesor_id' => $class->id];
        $this->actingAs($professor)->postJson('/profesor/verificar', $payload)->assertCreated();
        $this->postJson('/profesor/verificar', $payload)->assertStatus(409);
        $this->assertDatabaseCount('asistencias', 1);
    }

    public function test_professor_cannot_register_student_from_another_section(): void
    {
        [$professor, $student, $class] = $this->attendanceScenario();
        $student->update(['seccion_id' => Seccion::create(['nombre' => 'B'])->id]);

        $this->actingAs($professor)->postJson('/profesor/verificar', [
            'qr_token' => $student->qr_token, 'clase_profesor_id' => $class->id,
        ])->assertUnprocessable();
        $this->assertDatabaseCount('asistencias', 0);
    }

    private function attendanceScenario(): array
    {
        $grado = Grado::create(['nombre' => '1er año']);
        $seccion = Seccion::create(['nombre' => 'A']);
        $materia = Materia::create(['nombre' => 'Matemática', 'codigo' => 'MAT']);
        $professor = User::factory()->create(['role' => 'profesor']);
        $studentUser = User::factory()->create(['role' => 'estudiante']);
        $student = Estudiante::create(['user_id' => $studentUser->id, 'grado_id' => $grado->id,
            'seccion_id' => $seccion->id, 'matricula' => 'MAT-001', 'qr_token' => str_repeat('a', 64)]);
        $class = ClaseProfesor::create(['profesor_id' => $professor->id, 'materia_id' => $materia->id,
            'grado_id' => $grado->id, 'seccion_id' => $seccion->id]);

        return [$professor, $student, $class];
    }
}
