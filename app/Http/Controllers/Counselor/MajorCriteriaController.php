<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorCriteriaController extends Controller
{
    /**
     * Menampilkan daftar master alternatif jurusan beserta matriks bobot kriteria RIASEC.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Definisikan daftar kriteria RIASEC
        $criteria = [
            'R' => 'Realistic',
            'I' => 'Investigative',
            'A' => 'Artistic',
            'S' => 'Social',
            'E' => 'Enterprising',
            'C' => 'Conventional',
        ];

        // Ambil data jurusan beserta relasi rumpun dan kriteria bobotnya
        $majors = Major::with(['fieldOfStudy.parent.parent', 'criteria'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        // Mapping data ke dalam bentuk baris perhitungan (calculationRows)
        $calculationRows = $majors->map(function ($major) {
            $criteriaData = $major->criteria;
            
            return [
                'major' => $major,
                'weights' => [
                    'R' => $criteriaData ? (float) $criteriaData->r_std : 0,
                    'I' => $criteriaData ? (float) $criteriaData->i_std : 0,
                    'A' => $criteriaData ? (float) $criteriaData->a_std : 0,
                    'S' => $criteriaData ? (float) $criteriaData->s_std : 0,
                    'E' => $criteriaData ? (float) $criteriaData->e_std : 0,
                    'C' => $criteriaData ? (float) $criteriaData->c_std : 0,
                ]
            ];
        });

        return view('counselor.majors.index', compact('calculationRows', 'criteria', 'search'));
    }
}