<?php

namespace Tests\Feature\API;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario y generar token para las pruebas
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_user_can_get_tasks()
    {
        // Crear algunas tareas para el usuario
        Task::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonCount(3, 'data.tasks');
    }

    public function test_user_can_create_task()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'due_date' => '2025-04-01',
            'status' => 'pending'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tarea creada exitosamente')
            ->assertJsonPath('data.task.title', 'Test Task');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id
        ]);
    }

    public function test_user_can_get_specific_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'My Specific Task'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.task.id', $task->id)
            ->assertJsonPath('data.task.title', 'My Specific Task');
    }

    public function test_user_cannot_get_other_users_task()
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(404);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Old Title'
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'due_date' => '2025-05-01',
            'status' => 'completed'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/tasks/' . $task->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tarea actualizada exitosamente')
            ->assertJsonPath('data.task.title', 'Updated Title')
            ->assertJsonPath('data.task.status', 'completed');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed'
        ]);
    }

    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', true)
            ->assertJsonPath('message', 'Tarea eliminada exitosamente');

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
