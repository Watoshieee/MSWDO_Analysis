<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solo Parent Application Form - MSWDO</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 25mm;
            margin: auto;
            background: white;
            border: 1px solid #000;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .title {
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }

        .section {
            margin-top: 15px;
            font-weight: bold;
        }

        .line-input {
            border: none;
            border-bottom: 1px solid black;
            width: 100%;
            display: inline-block;
            background: transparent;
        }

        .line-input:focus {
            outline: none;
            border-bottom-color: #2C3E8F;
        }

        .inline {
            display: inline-block;
            margin-right: 10px;
        }

        .row {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
        }

        .row div {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            font-size: 12px;
        }

        td input, td select {
            width: 100%;
            border: none;
            padding: 3px;
            font-size: 11px;
        }

        td input:focus, td select:focus {
            outline: none;
            background: #f9f9f9;
        }

        textarea {
            width: 100%;
            border: none;
            border-bottom: 1px solid black;
            height: 60px;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-bottom-color: #2C3E8F;
        }

        .footer {
            margin-top: 20px;
        }

        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2C3E8F;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #1a2a5c;
        }

        .btn-container {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-family: "Times New Roman", serif;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
        }

        .btn-submit {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-family: "Times New Roman", serif;
        }

        .btn-submit:hover {
            background: #218838;
        }

        @media print {
            .print-btn, .btn-container {
                display: none;
            }
            .page {
                border: none;
                padding: 0;
                margin: 0;
                box-shadow: none;
            }
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
        }
    </style>
</head>

<body>

<div class="btn-container">
    <a href="{{ route('user.programs') }}" class="btn-back">← Back to Programs</a>
    <button type="submit" form="soloParentForm" class="btn-submit">✓ Submit Application</button>
</div>

<button class="print-btn" onclick="window.print()">🖨️ Print</button>

<div class="page">
    <form method="POST" action="{{ route('applications.store') }}" id="soloParentForm">
        @csrf
        <input type="hidden" name="program_type" value="Solo_Parent">
        <input type="hidden" name="municipality" value="Majayjay">

        <!-- HEADER -->
        <div class="center">
            <p>Republika ng Pilipinas</p>
            <p>Tanggapan ng Kagalingang Panlipunan at Pagpapaunlad</p>
            <p>Majayjay Laguna</p>

            <div class="title">APLIKASYON SA PAGIGING SOLONG MAGULANG</div>
        </div>

        <p><b>Form#012</b></p>

        <!-- SECTION 1 -->
        <p class="section">I. Pangunahing Impormasyon:</p>

        <div class="row">
            <div>Pangalan: <input type="text" name="additional_data[pangalan]" class="line-input" required></div>
            <div style="max-width:80px;">Edad: <input type="number" name="additional_data[edad]" class="line-input" required></div>
            <div style="max-width:120px;">Kasarian: 
                <select name="additional_data[kasarian]" class="line-input" required>
                    <option value="">Piliin</option>
                    <option value="Male">Lalaki</option>
                    <option value="Female">Babae</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div>Kapanganakan: <input type="date" name="additional_data[kapanganakan]" class="line-input"></div>
            <div>Lugar ng Kapanganakan: <input type="text" name="additional_data[lugar_kapanganakan]" class="line-input"></div>
        </div>

        <div class="row">
            <div>Relihiyon: <input type="text" name="additional_data[relihiyon]" class="line-input"></div>
            <div>Tirahan: <input type="text" name="additional_data[tirahan]" class="line-input" placeholder="Buong address"></div>
        </div>

        <div class="row">
            <div>Pinakamataas na Edukasyon: <input type="text" name="additional_data[edukasyon]" class="line-input"></div>
            <div>Trabaho: <input type="text" name="additional_data[trabaho]" class="line-input"></div>
        </div>

        <div class="row">
            <div>Buwanang Sahod: <input type="text" name="additional_data[sahod]" class="line-input" placeholder="₱"></div>
            <div>Contact Number: <input type="text" name="additional_data[contact_number]" class="line-input" required></div>
        </div>

        <div class="row">
            <div>Barangay: 
                <select name="additional_data[barangay]" class="line-input" required>
                    <option value="">Pumili ng Barangay</option>
                    @foreach($barangays ?? [] as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <div>Miyembro ng Philhealth? 
                <input type="radio" name="additional_data[philhealth]" value="Oo"> Oo
                <input type="radio" name="additional_data[philhealth]" value="Hindi"> Hindi
            </div>
        </div>

        <!-- TABLE - Komposisyon ng Pamilya -->
        <p class="section">II. Komposisyon ng Pamilya:</p>

        <table>
            <thead>
                <tr>
                    <th>Pangalan</th>
                    <th>Kapanganakan</th>
                    <th>Edad</th>
                    <th>Relasyon</th>
                    <th>Natamong Edukasyon</th>
                    <th>Trabaho</th>
                    <th>Puna</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><input type="text" name="additional_data[family][{{$i}}][name]" placeholder="Pangalan"></td>
                    <td><input type="text" name="additional_data[family][{{$i}}][birth]" placeholder="MM/DD/YYYY"></td>
                    <td><input type="number" name="additional_data[family][{{$i}}][age]" placeholder="Edad"></td>
                    <td>
                        <select name="additional_data[family][{{$i}}][relation]">
                            <option value="">Piliin</option>
                            <option value="Anak">Anak</option>
                            <option value="Asawa">Asawa</option>
                            <option value="Magulang">Magulang</option>
                            <option value="Kapatid">Kapatid</option>
                            <option value="Iba">Iba</option>
                        </select>
                    </td>
                    <td><input type="text" name="additional_data[family][{{$i}}][education]" placeholder="Edukasyon"></td>
                    <td><input type="text" name="additional_data[family][{{$i}}][job]" placeholder="Trabaho"></td>
                    <td><input type="text" name="additional_data[family][{{$i}}][remarks]" placeholder="Puna"></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <!-- SECTION 3 -->
        <p class="section">III. Klasipikasyon/Dahilan ng Pagiging Solong Magulang:</p>
        <textarea name="additional_data[reason]" placeholder="Ilagay ang dahilan kung bakit kayo ay itinuturing na solong magulang..."></textarea>

        <!-- SECTION 4 -->
        <p class="section">IV. Pangangailangan/Problema Bilang Solong Magulang:</p>
        <textarea name="additional_data[needs]" placeholder="Ilagay ang inyong mga pangangailangan at problema bilang solong magulang..."></textarea>

        <!-- SECTION 5 -->
        <p class="section">V. Pinagkukunan ng Kabuhayan:</p>
        <textarea name="additional_data[livelihood]" placeholder="Ilagay ang inyong pinagkukunan ng kabuhayan..."></textarea>

        <!-- DECLARATION -->
        <p style="margin-top:20px;">
            Pinatutunayan ko ang kawastuhan at katotohanan na nasasaad sa itaas nito. Nauunawaan ko na ang anumang maling impormasyon na aking inilagay ay maaaring magdulot ng kasong kriminal at sibil ayon sa itinadhana ng batas.
        </p>

        <!-- SIGNATURE -->
        <div class="signature">
            <div>
                Petsa:<br>
                <input type="date" name="additional_data[petsa]" class="line-input" value="{{ date('Y-m-d') }}">
            </div>

            <div>
                Pirma/Dit sa Ibabaw ng Pangalan:<br>
                <input type="text" name="additional_data[pirma]" class="line-input" placeholder="Pirma">
            </div>
        </div>

    </form>
</div>

</body>
</html>