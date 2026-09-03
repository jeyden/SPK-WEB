<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    /**
     * Menampilkan daftar kriteria paten sistem.
     */
    public function index()
    {
        return view('counselor.criteria.index');
    }

    /**
     * Menampilkan informasi kriteria (menggantikan form create karena bersifat paten).
     */
    public function create()
    {
        return view('counselor.criteria.create');
    }

    /**
     * Menampilkan informasi kriteria (menggantikan form edit karena bersifat paten).
     */
    public function edit($id)
    {
        return view('counselor.criteria.edit');
    }
}