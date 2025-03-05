export interface Task {
    id?: number;
    title: string;
    description?: string;
    due_date?: string;
    status: 'pending' | 'completed';
    user_id?: number;
}