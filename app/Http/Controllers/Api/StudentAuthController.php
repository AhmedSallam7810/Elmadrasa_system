<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
            'national_id' => 'nullable|string',
            'school_name' => 'nullable|string',
            'grade' => 'nullable|string',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'school_name' => $request->school_name,
            'grade' => $request->grade,
        ]);

        $token = $student->createToken('student-token')->plainTextToken;

        return response()->json([
            'student' => $student,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('student')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $student = Student::where('email', $request->email)->first();
        $token = $student->createToken('student-token')->plainTextToken;

        return response()->json([
            'student' => $student,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
