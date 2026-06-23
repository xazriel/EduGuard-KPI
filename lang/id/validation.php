<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'exists' => 'Kolom :attribute yang dipilih tidak valid.',
    'in' => 'Kolom :attribute yang dipilih tidak valid.',
    'date' => 'Kolom :attribute bukan tanggal yang valid.',
    'before_or_equal' => 'Kolom :attribute harus sebelum atau sama dengan :date.',
    'string' => 'Kolom :attribute harus berupa string.',
    'max' => [
        'numeric' => 'Kolom :attribute tidak boleh lebih dari :max.',
        'file' => 'Kolom :attribute tidak boleh lebih dari :max kilobita.',
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
        'array' => 'Kolom :attribute tidak boleh lebih dari :max anggota.',
    ],
    'custom' => [],
    'attributes' => [
        'student_id' => 'Siswa',
        'category' => 'Kategori',
        'sub_category' => 'Sub Kategori',
        'severity' => 'Tingkat Keparahan',
        'violation_date' => 'Tanggal Pelanggaran',
        'follow_up' => 'Tindak Lanjut',
        'nis' => 'NIS',
        'full_name' => 'Nama Lengkap',
        'gender' => 'Jenis Kelamin',
        'class_id' => 'Kelas',
        'status' => 'Status',
    ],
];
