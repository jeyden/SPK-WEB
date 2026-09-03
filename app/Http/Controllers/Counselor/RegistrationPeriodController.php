<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPeriod;
use App\Models\Student;
use Illuminate\Http\Request;

class RegistrationPeriodController extends Controller
{
    /**
     * Daftar pilihan tahun akademik untuk dropdown (2024/2025 s.d. 2040/2041).
     */
    private function academicYearOptions(): array
    {
        $options = [];

        for ($year = 2024; $year <= 2040; $year++) {
            $label = $year . '/' . ($year + 1);
            $options[] = $label;
        }

        return $options;
    }

    public function index()
    {
        RegistrationPeriod::refreshStatuses();

        $periods = RegistrationPeriod::withCount([
                // Jumlah siswa yang "mendaftar" pada periode ini diasumsikan
                // sebagai siswa dengan academic_year yang sama dengan periode.
                // Sesuaikan relasi ini bila skema Anda menggunakan tabel
                // pendaftaran/pivot tersendiri.
            ])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(10);

        // Hitung jumlah pendaftar per periode secara terpisah (academic_year match)
        // agar tidak bergantung pada relasi Eloquent yang belum tentu ada.
        $periods->getCollection()->transform(function ($period) {
            $period->registrants_count = Student::where('academic_year', $period->academic_year)->count();

            return $period;
        });

        return view('counselor.registration-periods.index', compact('periods'));
    }

    public function create()
    {
        $academicYears = $this->academicYearOptions();

        return view('counselor.registration-periods.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => ['required', 'string', 'in:' . implode(',', $this->academicYearOptions())],
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'description'   => 'nullable|string|max:1000',
        ]);

        $validated['status'] = RegistrationPeriod::STATUS_BELUM_DIBUKA;

        RegistrationPeriod::create($validated);

        return redirect()
            ->route('counselor.registration-periods.index')
            ->with('success', 'Periode pendaftaran berhasil dibuat.');
    }

    public function edit(RegistrationPeriod $registrationPeriod)
    {
        $academicYears = $this->academicYearOptions();

        return view('counselor.registration-periods.edit', [
            'period'        => $registrationPeriod,
            'academicYears' => $academicYears,
        ]);
    }

    public function update(Request $request, RegistrationPeriod $registrationPeriod)
    {
        $validated = $request->validate([
            'academic_year' => ['required', 'string', 'in:' . implode(',', $this->academicYearOptions())],
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'description'   => 'nullable|string|max:1000',
        ]);

        $registrationPeriod->update($validated);

        return redirect()
            ->route('counselor.registration-periods.index')
            ->with('success', 'Periode pendaftaran berhasil diperbarui.');
    }

    public function open(RegistrationPeriod $registrationPeriod)
    {
        RegistrationPeriod::refreshStatuses();
        $registrationPeriod->refresh();

        if ($registrationPeriod->isClosed()) {
            return back()->with('error', 'Periode yang sudah melewati tanggal berakhir tidak dapat dibuka kembali.');
        }

        $registrationPeriod->update(['status' => RegistrationPeriod::STATUS_DIBUKA]);

        return back()->with('success', 'Pendaftaran berhasil dibuka.');
    }

    public function close(RegistrationPeriod $registrationPeriod)
    {
        $registrationPeriod->update(['status' => RegistrationPeriod::STATUS_DITUTUP]);

        return back()->with('success', 'Pendaftaran berhasil ditutup.');
    }

    public function destroy(RegistrationPeriod $registrationPeriod)
    {
        $registrationPeriod->delete();

        return back()->with('success', 'Periode pendaftaran berhasil dihapus.');
    }

    /**
     * Menampilkan daftar siswa yang sudah mendaftar pada periode ini.
     *
     * CATATAN: karena tidak ada tabel pendaftaran/pivot khusus pada kode
     * yang diberikan, "sudah mendaftar" diasumsikan sebagai siswa dengan
     * `academic_year` yang sama dengan `academic_year` periode ini. Jika
     * sistem Anda memiliki tabel pendaftaran tersendiri (mis. `registrations`
     * dengan `registration_period_id` & `student_id`), ganti query di bawah
     * ini dengan relasi tersebut.
     */
    public function registrants(Request $request, RegistrationPeriod $registrationPeriod)
    {
        $search = $request->get('search');

        $registrants = Student::with(['user', 'riasecScore'])
            ->where('academic_year', $registrationPeriod->academic_year)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                        ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('counselor.registration-periods.registrants', [
            'period' => $registrationPeriod,
            'registrants' => $registrants,
            'search' => $search,
        ]);
    }
/**
     * Menampilkan detail data diri siswa pendaftar.
     */
    public function showRegistrant(RegistrationPeriod $registrationPeriod, Student $student)
    {
        if ($student->academic_year !== $registrationPeriod->academic_year) {
            return back()->with('error', 'Siswa tersebut tidak terdaftar pada periode ini.');
        }

        $student->load('user');

        return view('counselor.registration-periods.registrant-detail', [
            'period'  => $registrationPeriod,
            'student' => $student,
        ]);
    } 
    /**
     * Menghapus satu siswa dari daftar pendaftar periode ini.
     *
     * Lihat catatan pada registrants() di atas — jika Anda memiliki tabel
     * pendaftaran tersendiri, method ini sebaiknya menghapus baris
     * pendaftaran tsb, bukan data siswa itu sendiri. Sebagaimana kode saat
     * ini tidak menyediakan tabel tersebut, method ini menghapus record
     * Student terkait secara langsung.
     */
    public function destroyRegistrant(RegistrationPeriod $registrationPeriod, Student $student)
    {
        if ($student->academic_year !== $registrationPeriod->academic_year) {
            return back()->with('error', 'Siswa tersebut tidak terdaftar pada periode ini.');
        }

        $student->delete();

        return back()->with('success', 'Pendaftar berhasil dihapus dari periode ini.');
    }
}