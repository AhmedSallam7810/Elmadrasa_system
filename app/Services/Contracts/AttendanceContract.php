<?php

namespace App\Services\Contracts;

use App\Models\Attendance;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface AttendanceContract
{
    /**
     * Get attendance based on the request.
     *
     * @param \Illuminate\Http\Request $request
     * e
     */
    public function getAttendanceData(Request $request);
    public function createAttendance();


    // public function getAttendanceById($id);

    // public function createAttendance($request);

    // public function updateAttendance($request, $id);

    // public function deleteAttendance($id);
}
