<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Pernyataan Digital</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; background: #fff; padding: 40px 60px; }

    .header-wrapper { border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 6px; }
    .logo { width: 85px; height: 85px; }
    .logo img { width: 100%; height: 100%; }
    .header-text { text-align: center; }
    .header-text .gov { font-size: 12px; font-weight: normal; }
    .header-text .dept { font-size: 12px; font-weight: normal; }
    .header-text .school { font-size: 15px; font-weight: bold; text-transform: uppercase; }
    .header-text .address { font-size: 11px; margin-top: 3px; }
    .header-text .contact { font-size: 11px; }

    .jakarta-kodepos-row { margin-top: 2px; font-size: 11px; }
    .jakarta-left { font-size: 11px; text-align: center; }
    .kode-pos { font-size: 11px; white-space: nowrap; }

    .title-section { text-align: center; margin: 20px 0 18px; }
    .title-section h2 { font-size: 14px; font-weight: bold; text-decoration: underline; text-transform: uppercase; letter-spacing: 1px; }

    .body-text { font-size: 12px; line-height: 1.8; margin-bottom: 10px; text-align: justify; }

    .student-info { margin: 12px 0 16px 20px; }
    .info-row { font-size: 12px; line-height: 1.7; }
    .info-label { width: 160px; font-style: italic; }
    .info-colon { width: 20px; }
    .info-value { font-weight: normal; }

    .violation-section { margin: 14px 0; font-size: 12px; line-height: 1.8; }
    .violation-list { margin: 6px 0 6px 10px; }
    .violation-item { }
    .viol-num { width: 20px; }
    .viol-line { border-bottom: 1px solid #000; min-height: 20px; padding-bottom: 2px; }

    .closing { font-size: 12px; line-height: 1.8; margin-top: 14px; text-align: justify; }

    .signature-section { margin-top: 30px; }
    .sig-block { text-align: center; width: 220px; }
    .sig-block p { font-size: 12px; line-height: 1.7; }
    .sig-space { height: 70px; }
    .sig-name { border-top: 1px solid #000; padding-top: 4px; margin-top: 4px; font-size: 12px; }
</style>
</head>
<body>

<div class="header-wrapper" style="border-bottom: 3px solid #000; padding-bottom: 12px; margin-bottom: 6px;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <!-- Left Logo -->
            <td style="width: 85px; vertical-align: top;">
                <div class="logo" style="width: 85px; height: 85px;">
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('logo-jaya-raya.jpg'))) }}" alt="Logo Jaya Raya" style="width: 100%; height: 100%;">
                </div>
            </td>
            <!-- Center Header Text (perfectly centered by matching left/right cell widths) -->
            <td class="header-text" style="text-align: center; vertical-align: top; padding: 0 10px;">
                <div class="gov" style="font-size: 12px; font-weight: normal;">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</div>
                <div class="dept" style="font-size: 12px; font-weight: normal;">DINAS PENDIDIKAN</div>
                <div class="school" style="font-size: 15px; font-weight: bold; text-transform: uppercase; margin: 2px 0;">SEKOLAH MENENGAH PERTAMA NEGERI 216 JAKARTA</div>
                <div class="address" style="font-size: 11px; margin-top: 3px;">Jalan Salemba Raya No.18, Jakarta Pusat</div>
                <div class="contact" style="font-size: 11px;">Telepon 31931857 Faksimili 31931857</div>
                <div class="contact" style="font-size: 11px;">Website: www.smpn216jkt.sch.id Email: smpn216_jp@yahoo.co.id</div>
                <div class="jakarta" style="font-size: 11px; margin-top: 4px; font-weight: bold;">JAKARTA</div>
            </td>
            <!-- Right Spacer / Kode Pos (matches logo cell width to keep center text centered) -->
            <td style="width: 85px; vertical-align: bottom; text-align: right; font-size: 11px; white-space: nowrap;">
                Kode Pos 10430
            </td>
        </tr>
    </table>
</div>

<div class="title-section">
    <h2>SURAT PERNYATAAN</h2>
</div>

<div class="body-text">
    Saya yang bertanda tangan dibawah ini:
</div>

<table class="student-info" style="width: 100%; border-collapse: collapse; margin: 12px 0 16px 20px;">
    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Nama</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $student->full_name }}</td>
    </tr>
    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Tempat tanggal lahir</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? $student->birth_date->locale('id')->isoFormat('D MMMM Y') : '-' }}</td>
    </tr>
    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Jenis Kelamin</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
    </tr>
    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Kelas</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $class?->class_name }}</td>
    </tr>
    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Nama Orang Tua / Wali</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $student->parent_name ?? '-' }}</td>
    </tr>

    <tr class="info-row">
        <td class="info-label" style="width: 160px; font-style: italic; vertical-align: top; padding-bottom: 3px; font-size: 12px;">Alamat</td>
        <td class="info-colon" style="width: 20px; vertical-align: top; padding-bottom: 3px; font-size: 12px;">:</td>
        <td class="info-value" style="vertical-align: top; padding-bottom: 3px; font-size: 12px; font-weight: normal;">{{ $student->address ?? '-' }}</td>
    </tr>
</table>

<div class="body-text">
    Dengan ini saya menyatakan akan sungguh sungguh untuk tidak mengulangi
    kesalahan yang pernah dilakukan, dan akan mematuhi segala peraturan yang ada di
    SMP Negeri 216 Jakarta, dan apabila saya tetap melakukan perbuatan tersebut maka
    saya bersedia <strong>MENERIMA SANKSI</strong> dari SMP Negeri 216 Jakarta tanpa tuntutan
    apapun.
</div>

<div class="violation-section">
    <div>Adapun jenis pelanggaran yang saya lakukan antara lain:</div>
    <table class="violation-list" style="width: 100%; border-collapse: collapse; margin: 6px 0 6px 10px;">
        @foreach($violations as $index => $v)
        <tr class="violation-item">
            <td class="viol-num" style="width: 20px; vertical-align: bottom; font-size: 12px; padding-bottom: 2px;">{{ $index + 1 }}.</td>
            <td class="viol-line" style="border-bottom: 1px solid #000; min-height: 20px; padding-bottom: 2px; font-size: 12px; font-weight: normal;">{{ $v }}</td>
        </tr>
        @endforeach
        @for($i = count($violations); $i < 5; $i++)
        <tr class="violation-item">
            <td class="viol-num" style="width: 20px; vertical-align: bottom; font-size: 12px; padding-bottom: 2px;">{{ $i + 1 }}.</td>
            <td class="viol-line" style="border-bottom: 1px solid #000; min-height: 20px; padding-bottom: 2px; font-size: 12px;">&nbsp;</td>
        </tr>
        @endfor
    </table>
</div>

<div class="closing">
    Demikian pernyataan ini saya buat dengan sesungguhnya.
</div>

<table class="signature-section" style="width: 100%; border-collapse: collapse; margin-top: 30px; border: none;">
    <tr>
        <td style="width: 60%; border: none;"></td>
        <td class="sig-block" style="text-align: center; width: 220px; vertical-align: top; border: none;">
            <p>Jakarta, {{ $date }}</p>
            <p>Yang membuat pernyataan,</p>
            <div class="sig-space"></div>
            <div class="sig-name">
                <p>{{ $student->full_name }}</p>
            </div>
        </td>
    </tr>
</table>

</body>
</html>
