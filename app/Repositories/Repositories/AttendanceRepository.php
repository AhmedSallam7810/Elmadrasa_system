<?php

namespace App\Repositories\Repositories;

use App\Models\Attendance;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Attendance();
    }

    public function filter($filters)
    {

        $query = $this->model::with(['student', 'student.classes']);
        $date = !empty($filters['date']) ? $filters['date'] : now()->format('Y-m-d');

        $query->whereDate('date', $date);

        // Apply class filter
        if (!empty($filters['class_id'])) {
            $query->whereHas('student.classes', function ($q) use ($filters) {
                $q->where('classes.id', $filters['class_id']);
            });
        }

        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply degree filter
        // if ($filters['degree']) {
        //     $query->where('degree', $filters['degree']);
        // }


        return $query;
    }
}
