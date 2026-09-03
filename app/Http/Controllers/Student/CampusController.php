<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    // Menampilkan daftar kampus beserta relasi jurusannya dalam satu halaman
    public function index(Request $request)
    {
        $search = $request->input('search');
        $accreditation = $request->input('accreditation');

        $campuses = Campus::with(['majors' => function($query) {
                $query->orderBy('name', 'asc');
            }])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('city', 'like', "%{$search}%");
            })
            ->when($accreditation, function ($query, $accreditation) {
                return $query->where('accreditation', $accreditation);
            })
            ->orderBy('name', 'asc')
            ->paginate(6)
            ->withQueryString();

        $accreditations = Campus::select('accreditation')->distinct()->whereNotNull('accreditation')->pluck('accreditation');

        return view('student.campuses.index', compact('campuses', 'search', 'accreditation', 'accreditations'));
    }
}