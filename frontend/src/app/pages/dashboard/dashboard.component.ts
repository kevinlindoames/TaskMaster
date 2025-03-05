import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { Task } from '../../models/task.model';
import { TaskService } from '../../services/task.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {
  tasks: Task[] = [];
  currentTask: Task = { title: '', status: 'pending' };
  showTaskModal: boolean = false;
  editMode: boolean = false;

  constructor(
    private taskService: TaskService,
    private authService: AuthService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.loadTasks();
  }

  loadTasks(): void {
    this.taskService.getTasks().subscribe({
      next: (response) => {
        this.tasks = response.data.tasks;
      },
      error: (error) => {
        console.error('Error al cargar tareas', error);
        if (error.status === 401) {
          this.authService.removeToken();
          this.router.navigate(['/login']);
        }
      }
    });
  }

  openTaskForm(): void {
    this.currentTask = { title: '', status: 'pending' };
    this.editMode = false;
    this.showTaskModal = true;
  }

  editTask(task: Task): void {
    this.currentTask = { ...task };
    this.editMode = true;
    this.showTaskModal = true;
  }

  closeTaskForm(): void {
    this.showTaskModal = false;
  }

  saveTask(): void {
    if (this.editMode) {
      this.taskService.updateTask(this.currentTask.id!, this.currentTask).subscribe({
        next: (response) => {
          this.loadTasks();
          this.closeTaskForm();
        },
        error: (error) => {
          console.error('Error al actualizar tarea', error);
        }
      });
    } else {
      this.taskService.createTask(this.currentTask).subscribe({
        next: (response) => {
          this.loadTasks();
          this.closeTaskForm();
        },
        error: (error) => {
          console.error('Error al crear tarea', error);
        }
      });
    }
  }

  deleteTask(task: Task): void {
    if (confirm('¿Estás seguro de eliminar esta tarea?')) {
      this.taskService.deleteTask(task.id!).subscribe({
        next: (response) => {
          this.loadTasks();
        },
        error: (error) => {
          console.error('Error al eliminar tarea', error);
        }
      });
    }
  }

  toggleStatus(task: Task): void {
    const updatedTask: Task = {
      ...task,
      status: (task.status === 'pending' ? 'completed' : 'pending') as 'pending' | 'completed'
    };

    this.taskService.updateTask(task.id!, updatedTask).subscribe({
      next: (response) => {
        this.loadTasks();
      },
      error: (error) => {
        console.error('Error al actualizar estado', error);
      }
    });
  }
}