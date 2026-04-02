<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Block any global Chart.js auto-rendering on this page -->
<script>window.__CHART_BLOCKED__ = true;</script>
<title>PRPWD Application Form – MSWDO</title>
<style>
/* ============================
   SCREEN ACTION BAR
   ============================ */
.action-bar {
    background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
    padding: 10px 0;
    position: sticky; top: 0; z-index: 100;
    box-shadow: 0 3px 14px rgba(44,62,143,.35);
}
.ab-inner {
    max-width: 860px; margin: 0 auto; padding: 0 16px;
    display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;
}
.ab-brand { color: #FDB913; font-weight: 800; font-size: 1rem; font-family: Arial, sans-serif; }
.ab-btns  { display: flex; gap: 8px; flex-wrap: wrap; }
.ab-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border-radius: 8px; padding: 8px 18px;
    font-weight: 700; font-size: .84rem; cursor: pointer;
    border: none; text-decoration: none; transition: all .25s;
    font-family: Arial, sans-serif;
}
.ab-btn.back  { background: rgba(255,255,255,.15); color: white; border: 2px solid rgba(255,255,255,.4); }
.ab-btn.back:hover { background: rgba(255,255,255,.28); }
.ab-btn.print { background: #FDB913; color: #2C3E8F; }
.ab-btn.print:hover { box-shadow: 0 4px 14px rgba(253,185,19,.5); transform: translateY(-1px); }
.ab-btn.submit { background: white; color: #2C3E8F; }
.ab-btn.submit:hover { box-shadow: 0 4px 14px rgba(255,255,255,.3); transform: translateY(-1px); }

/* ============================
   PAGE / PAPER  (screen)
   ============================ */
body {
    background: #d0d5dd;
    font-family: Arial, Helvetica, sans-serif;
    margin: 0; padding: 0;
}
.page-wrap {
    display: flex; justify-content: center;
    padding: 24px 16px 60px;
}

/* The A4 paper */
.a4 {
    background: white;
    width: 210mm;
    min-height: 297mm;
    padding: 8mm 10mm 8mm 10mm;
    box-shadow: 0 4px 32px rgba(0,0,0,.25);
    box-sizing: border-box;
    position: relative;
}

/* ============================
   TYPOGRAPHY INSIDE FORM
   ============================ */
.a4 * { box-sizing: border-box; }
.fl { font-size: 6.5pt; font-weight: bold; color: #111; line-height: 1.2; }
.sm { font-size: 7pt; line-height: 1.3; }

/* ============================
   HEADER
   ============================ */
.hdr {
    display: grid;
    grid-template-columns: 22mm 1fr 22mm;
    align-items: center;
    text-align: center;
    margin-bottom: 3mm;
    gap: 0 4mm;
}
.hdr-logo { width: 20mm; height: 20mm; object-fit: contain; display: block; }
.hdr-center { line-height: 1.25; }
.hdr-center h1 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; letter-spacing: .01em; white-space: nowrap; }
.hdr-center h2 { font-size: 10px; font-weight: bold; margin: 3px 0 0; }
.hdr-center h3 { font-size: 13px; font-weight: bold; text-decoration: underline; margin: 4px 0 0; }

/* ============================
   OUTER BORDER
   ============================ */
.form-border {
    border: 1.5pt solid #111;
    width: 100%;
}

/* ============================
   ALL ROWS / CELLS
   ============================ */
.row {
    display: flex;
    border-bottom: 1pt solid #111;
}
.row:last-child { border-bottom: none; }

.cell {
    padding: 1.5mm 2mm;
    border-right: 1pt solid #111;
    flex-shrink: 0;
    position: relative;
}
.cell:last-child { border-right: none; }

/* Widths as percentages of form width */
.w100 { width: 100%; }
.w70  { width: 70%; }
.w30  { width: 30%; }
.w50  { width: 50%; }
.w55  { width: 55%; }
.w45  { width: 45%; }
.w35  { width: 35%; }
.w65  { width: 65%; }
.w25  { width: 25%; }
.w28  { width: 28%; }
.w20  { width: 20%; }
.w15  { width: 15%; }
.w18  { width: 18%; }
.w12  { width: 12%; }
.w10  { width: 10%; }
.w8   { width: 8%; }
.wmx  { flex: 1; min-width: 0; }

/* ============================
   FIELD INPUTS
   ============================ */
.fi {
    display: block;
    width: 100%;
    border: none;
    border-bottom: 0.5pt solid #555;
    outline: none;
    font-size: 8pt;
    font-family: Arial, sans-serif;
    padding: 0.5mm 1mm;
    margin-top: 1mm;
    background: transparent;
    color: #111;
}
.fi:focus { border-bottom: 1pt solid #2C3E8F; background: rgba(44,62,143,.03); }
select.fi { cursor: pointer; height: auto; }
.fi-no-border {
    display: block; width: 100%;
    border: none; outline: none;
    font-size: 8pt; font-family: Arial, sans-serif;
    padding: 0.3mm 1mm; background: transparent; color: #111;
}

/* ============================
   RADIO & CHECKBOX
   ============================ */
.opts { display: flex; flex-wrap: wrap; gap: 1mm 4mm; margin-top: 1mm; align-items: center; }
.opts label { display: flex; align-items: center; gap: 1.5mm; font-size: 7.5pt; cursor: pointer; white-space: nowrap; }
.opts input[type=radio],
.opts input[type=checkbox] {
    width: 9px; height: 9px;
    margin: 0; cursor: pointer; accent-color: #2C3E8F; flex-shrink: 0;
}
.cb-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8mm 3mm; margin-top: 1mm; }
.cb-2col label { display: flex; align-items: center; gap: 1.5mm; font-size: 7pt; cursor: pointer; }
.cb-2col input { width: 9px; height: 9px; margin: 0; accent-color: #2C3E8F; flex-shrink: 0; cursor: pointer; }
.cb-1col label { display: flex; align-items: center; gap: 1.5mm; font-size: 7.5pt; cursor: pointer; margin-bottom: 0.5mm; }
.cb-1col input { width: 9px; height: 9px; margin: 0; accent-color: #2C3E8F; flex-shrink: 0; cursor: pointer; }

/* ============================
   PHOTO BOX — isolated from global canvases
   ============================ */
.photo-box {
    border: 0.8pt solid #555;
    width: 21mm; height: 25mm;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    font-size: 6.5pt; text-align: center; color: #666;
    cursor: pointer; position: relative; overflow: hidden;
    margin-top: 1mm;
    isolation: isolate;
    contain: strict;
    background: white;
}
.photo-box img { width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0; z-index:2; }
.photo-box canvas { display: none !important; }
.photo-box:hover { border-color: #2C3E8F; }

/* ============================
   INLINE TABLE (family/accomplished)
   ============================ */
.itbl { width: 100%; border-collapse: collapse; }
.itbl td { padding: 0.5mm 1.5mm; }
.itbl .col-label { width: 26%; }
.itbl .col-name { width: 25%; }

/* ============================
   UPLOAD + ERRORS (screen only)
   ============================ */
.upload-section {
    max-width: 860px; margin: 16px auto 0; padding: 0 16px;
}
.upload-card {
    background: white; border-radius: 14px;
    border: 1px solid #e2e8f0;
    padding: 20px 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,.04);
}

/* ============================
   SUCCESS MODAL
   ============================ */
.smodal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.55); z-index:999; align-items:center; justify-content:center; }
.smodal.show { display:flex; }
.sm-c { background:white; border-radius:18px; padding:32px 36px; max-width:440px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.3); font-family:Arial,sans-serif; }
.sm-c .icon { font-size:2.8rem; margin-bottom:10px; }
.sm-c h3 { font-size:1.2rem; font-weight:800; color:#2C3E8F; margin-bottom:8px; }
.sm-c p  { font-size:.88rem; color:#475569; line-height:1.6; margin-bottom:20px; }
.sm-c .btns { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
.sm-c .btns a,.sm-c .btns button { border-radius:9px; padding:10px 22px; font-weight:700; font-size:.88rem; cursor:pointer; border:none; transition:all .3s; text-decoration:none; display:inline-block; }
.sm-c .btns .p { background:linear-gradient(135deg,#2C3E8F,#1A2A5C); color:white; }
.sm-c .btns .s { background:#f1f5f9; color:#2C3E8F; }

/* ============================
   PRINT OVERRIDES
   ============================ */
@media print {
    .action-bar, .upload-section, .no-print { display: none !important; }
    body { background: white; margin: 0; padding: 0; }
    .page-wrap { padding: 0; background: white; }
    .a4 {
        width: 210mm;
        min-height: 297mm;
        padding: 8mm 10mm;
        box-shadow: none;
        margin: 0;
    }
    @page {
        size: A4 portrait;
        margin: 0;
    }
    .fi { border-bottom: 0.5pt solid #888; }
}
</style>
</head>
<body>

<!-- ═══ ACTION BAR (hidden on print) ═══ -->
<div class="action-bar no-print">
    <div class="ab-inner">
        <div class="ab-brand">📋 PRPWD Application Form — Philippine Registry for PWD v4.0</div>
        <div class="ab-btns">
            <a href="{{ route('user.pwd-application') }}" class="ab-btn back">← Back to PWD Guide</a>
            <button class="ab-btn print" onclick="window.print()">🖨️ Print Form</button>
            <button class="ab-btn submit" onclick="doSubmit()">📤 Submit Online</button>
        </div>
    </div>
</div>

<!-- ═══ FORM ═══ -->
<form id="prpwdForm" method="POST" action="{{ route('user.pwd-form.submit') }}" enctype="multipart/form-data">
@csrf

<div class="page-wrap">
<div class="a4">

    <!-- ══════════ HEADER ══════════ -->
    <div class="hdr">
        <div style="display:flex;justify-content:center;align-items:center;">
            <img src="{{ asset('images/ph-seal.png') }}" alt="Philippine Seal" class="hdr-logo"
                 onerror="this.style.display='none'">
        </div>
        <div class="hdr-center">
            <h1>Department of Health</h1>
            <h2>Philippine Registry For Persons with Disabilities Version 4.0</h2>
            <h3>Application Form</h3>
        </div>
        <div style="display:flex;justify-content:center;align-items:center;">
            <img src="{{ asset('images/doh-logo.png') }}" alt="DOH Logo" class="hdr-logo"
                 onerror="this.style.display='none'">
        </div>
    </div>

    <!-- ══════════ FORM BODY ══════════ -->
    <div class="form-border">

        {{-- ROW 1: Applicant type + Photo --}}
        <div class="row" style="align-items:stretch;">
            <div class="cell wmx" style="min-height:28mm;">
                <div style="display:flex;align-items:center;gap:6mm;flex-wrap:wrap;height:100%;">
                    <span class="sm" style="font-weight:bold;">1.</span>
                    <div class="opts" style="margin:0;gap:1mm 8mm;">
                        <label><input type="radio" name="applicant_type" value="new_applicant" checked>
                            <span class="sm" style="font-weight:bold;">○ NEW APPLICANT</span>
                        </label>
                        <label><input type="radio" name="applicant_type" value="renewal">
                            <span class="sm" style="font-weight:bold;">○ RENEWAL <span style="color:red;">*</span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="cell" style="width:28mm;text-align:center;border-right:none;">
                <div class="fl">Place 1"x1"<br>Photo Here</div>
                <div class="photo-box" onclick="document.getElementById('photoInput').click()">
                    <img id="photoPreview" src="" style="display:none;">
                    <span id="photoTxt" style="font-size:6pt;color:#888;line-height:1.4;">Click to<br>upload photo</span>
                </div>
                <input type="file" id="photoInput" name="applicant_photo" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
            </div>
        </div>

        {{-- ROW 2: PWD Number | Date Applied --}}
        <div class="row">
            <div class="cell w70">
                <div class="fl">2. &nbsp;PERSONS WITH DISABILITY NUMBER (RR-PPMM-BBB-NNNNNNN) <span style="color:red;">*</span></div>
                <input type="text" name="pwd_number" class="fi" placeholder="e.g. 01-0101-001-0000001" maxlength="30" value="{{ old('pwd_number') }}">
            </div>
            <div class="cell w30" style="border-right:none;">
                <div class="fl">3. &nbsp;Date Applied <span style="color:red;">*</span> (mm/dd/yyyy)</div>
                <input type="date" name="date_applied" class="fi" value="{{ old('date_applied', now()->format('Y-m-d')) }}">
            </div>
        </div>

        {{-- ROW 3: Personal Information --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">4. &nbsp;PERSONAL INFORMATION <span style="color:red;">*</span></div>
                <div style="display:grid;grid-template-columns:2.2fr 2.2fr 2.2fr 1fr;gap:0 3mm;margin-top:1mm;">
                    <div>
                        <div class="fl">LAST NAME: <span style="color:red;">*</span></div>
                        <input type="text" name="last_name" class="fi" required
                               value="{{ old('last_name') }}">
                    </div>
                    <div>
                        <div class="fl">FIRST NAME: <span style="color:red;">*</span></div>
                        <input type="text" name="first_name" class="fi" required
                               value="{{ old('first_name') }}">
                    </div>
                    <div>
                        <div class="fl">MIDDLE NAME: <span style="color:red;">*</span></div>
                        <input type="text" name="middle_name" class="fi" value="{{ old('middle_name') }}">
                    </div>
                    <div>
                        <div class="fl">SUFFIX: <span style="color:red;">*</span></div>
                        <select name="suffix" class="fi">
                            <option value="">—</option>
                            <option>Jr.</option><option>Sr.</option>
                            <option>II</option><option>III</option><option>IV</option>
                            <option>N/A</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 4: DOB | Sex --}}
        <div class="row">
            <div class="cell w55">
                <div class="fl">5. &nbsp;DATE OF BIRTH: <span style="color:red;">*</span> (mm/dd/yyyy)</div>
                <input type="date" name="date_of_birth" class="fi" required
                       value="{{ old('date_of_birth', Auth::user()->birthdate ?? '') }}">
            </div>
            <div class="cell w45" style="border-right:none;">
                <div class="fl">6. &nbsp;SEX: <span style="color:red;">*</span></div>
                <div class="opts">
                    <label><input type="radio" name="sex" value="female"> <span class="sm">○ FEMALE</span></label>
                    <label><input type="radio" name="sex" value="male"> <span class="sm">○ MALE</span></label>
                </div>
            </div>
        </div>

        {{-- ROW 5: Civil Status --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">7. &nbsp;CIVIL STATUS: <span style="color:red;">*</span></div>
                <div class="opts">
                    <label><input type="radio" name="civil_status" value="Single"> <span class="sm">○ Single</span></label>
                    <label><input type="radio" name="civil_status" value="Separated"> <span class="sm">○ Separated</span></label>
                    <label><input type="radio" name="civil_status" value="Cohabitation"> <span class="sm">○ Cohabitation (live-in)</span></label>
                    <label><input type="radio" name="civil_status" value="Married"> <span class="sm">○ Married</span></label>
                    <label><input type="radio" name="civil_status" value="Widow/er"> <span class="sm">○ Widow/er</span></label>
                </div>
            </div>
        </div>

        {{-- ROW 6: Type of Disability | Cause of Disability --}}
        <div class="row" style="align-items:stretch;">
            <div class="cell w55">
                <div class="fl">8. &nbsp;TYPE OF DISABILITY: <span style="color:red;">*</span></div>
                <div class="cb-2col">
                    <label><input type="checkbox" name="disability[]" value="Deaf or Hard of Hearing"> <span class="sm">□ Deaf or Hard of Hearing</span></label>
                    <label><input type="checkbox" name="disability[]" value="Psychosocial Disability"> <span class="sm">□ Psychosocial Disability</span></label>
                    <label><input type="checkbox" name="disability[]" value="Intellectual Disability"> <span class="sm">□ Intellectual Disability</span></label>
                    <label><input type="checkbox" name="disability[]" value="Speech and Language Impairment"> <span class="sm">□ Speech and Language Impairment</span></label>
                    <label><input type="checkbox" name="disability[]" value="Learning Disability"> <span class="sm">□ Learning Disability</span></label>
                    <label><input type="checkbox" name="disability[]" value="Visual Disability"> <span class="sm">□ Visual Disability</span></label>
                    <label><input type="checkbox" name="disability[]" value="Mental Disability"> <span class="sm">□ Mental Disability</span></label>
                    <label><input type="checkbox" name="disability[]" value="Cancer (RA11215)"> <span class="sm">□ Cancer (RA11215)</span></label>
                    <label><input type="checkbox" name="disability[]" value="Physical Disability (Orthopedic)"> <span class="sm">□ Physical Disability (Orthopedic)</span></label>
                    <label><input type="checkbox" name="disability[]" value="Rare Disease (RA10747)"> <span class="sm">□ Rare Disease (RA10747)</span></label>
                </div>
            </div>
            <div class="cell w45" style="border-right:none;">
                <div class="fl">9. &nbsp;CAUSE OF DISABILITY: <span style="color:red;">*</span></div>
                <div style="margin-top:1mm;">
                    {{-- Congenital --}}
                    <div style="display:flex;align-items:center;gap:2mm;margin-bottom:0.5mm;">
                        <input type="checkbox" name="cause_type[]" value="Congenital / Inborn" style="width:9px;height:9px;accent-color:#2C3E8F;">
                        <span class="fl">□ Congenital / Inborn</span>
                        &nbsp;&nbsp;
                        <input type="checkbox" name="cause_type[]" value="Acquired" style="width:9px;height:9px;accent-color:#2C3E8F;">
                        <span class="fl">□ Acquired</span>
                    </div>
                    <div class="cb-2col" style="padding-left:3mm;">
                        <label><input type="checkbox" name="cause_sub[]" value="Autism"> <span class="sm">□ Autism</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="Chronic Illness"> <span class="sm">□ Chronic Illness</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="ADHD"> <span class="sm">□ ADHD</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="Cerebral Palsy"> <span class="sm">□ Cerebral Palsy</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="Cerebral Palsy (Acquired)"> <span class="sm">□ Cerebral Palsy</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="Injury"> <span class="sm">□ Injury</span></label>
                        <label><input type="checkbox" name="cause_sub[]" value="Down Syndrome"> <span class="sm">□ Down Syndrome</span></label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 7: Residence Address --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">10. &nbsp;RESIDENCE ADDRESS <span style="color:red;">*</span></div>
                <div style="display:grid;grid-template-columns:2.5fr 1.5fr 1.5fr 1.5fr 1fr;gap:0 2mm;margin-top:1mm;">
                    <div>
                        <div class="fl">House No. and Street:<span style="color:red;">*</span></div>
                        <input type="text" name="house_no_street" class="fi" value="{{ old('house_no_street') }}" required>
                    </div>
                    <div>
                        <div class="fl">Barangay:<span style="color:red;">*</span></div>
                        <input type="text" name="barangay_address" class="fi" value="{{ old('barangay_address') }}" required>
                    </div>
                    <div>
                        <div class="fl">Municipality:<span style="color:red;">*</span></div>
                        <input type="text" name="municipality_address" class="fi"
                               value="{{ old('municipality_address', Auth::user()->municipality ?? '') }}" required>
                    </div>
                    <div>
                        <div class="fl">Province:<span style="color:red;">*</span></div>
                        <input type="text" name="province" class="fi" value="{{ old('province','Laguna') }}" required>
                    </div>
                    <div>
                        <div class="fl">Region:<span style="color:red;">*</span></div>
                        <input type="text" name="region" class="fi" value="{{ old('region','IV-A') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 8: Contact Details --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">11. &nbsp;CONTACT DETAILS</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 2fr;gap:0 3mm;margin-top:1mm;">
                    <div>
                        <div class="fl">Landline No.:</div>
                        <input type="text" name="landline" class="fi" value="{{ old('landline') }}">
                    </div>
                    <div>
                        <div class="fl">Mobile No.:</div>
                        <input type="text" name="mobile" class="fi" value="{{ old('mobile') }}">
                    </div>
                    <div>
                        <div class="fl">E-mail Address:</div>
                        <input type="email" name="email_address" class="fi"
                               value="{{ old('email_address', Auth::user()->email ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 9: Educational Attainment | Occupation --}}
        <div class="row" style="align-items:stretch;">
            <div class="cell w50">
                <div class="fl">12. &nbsp;EDUCATIONAL ATTAINMENT: <span style="color:red;">*</span></div>
                <div class="cb-2col">
                    <label><input type="radio" name="education" value="None"> <span class="sm">○ None</span></label>
                    <label><input type="radio" name="education" value="Senior High School"> <span class="sm">○ Senior High School</span></label>
                    <label><input type="radio" name="education" value="Kindergarten"> <span class="sm">○ Kindergarten</span></label>
                    <label><input type="radio" name="education" value="College"> <span class="sm">○ College</span></label>
                    <label><input type="radio" name="education" value="Elementary"> <span class="sm">○ Elementary</span></label>
                    <label><input type="radio" name="education" value="Vocational"> <span class="sm">○ Vocational</span></label>
                    <label><input type="radio" name="education" value="Junior High School"> <span class="sm">○ Junior High School</span></label>
                    <label><input type="radio" name="education" value="Post Graduate"> <span class="sm">○ Post Graduate</span></label>
                </div>
            </div>
            <div class="cell w50" style="border-right:none;">
                <div class="fl">14. &nbsp;OCCUPATION: <span style="color:red;">*</span></div>
                <div class="cb-1col" style="margin-top:1mm;">
                    <label><input type="radio" name="occupation" value="Managers"> <span class="sm">○ Managers</span></label>
                    <label><input type="radio" name="occupation" value="Professionals"> <span class="sm">○ Professionals</span></label>
                    <label><input type="radio" name="occupation" value="Technicians and Associate Professionals"> <span class="sm">○ Technicians and Associate Professionals</span></label>
                    <label><input type="radio" name="occupation" value="Clerical Support Workers"> <span class="sm">○ Clerical Support Workers</span></label>
                    <label><input type="radio" name="occupation" value="Service and Sales Workers"> <span class="sm">○ Service and Sales Workers</span></label>
                    <label><input type="radio" name="occupation" value="Skilled Agricultural, Forestry and Fishery Workers"> <span class="sm">○ Skilled Agricultural, Forestry and Fishery Workers</span></label>
                    <label><input type="radio" name="occupation" value="Craft and Related Trade Workers"> <span class="sm">○ Craft and Related Trade Workers</span></label>
                    <label><input type="radio" name="occupation" value="Plant and Machine Operators and Assemblers"> <span class="sm">○ Plant and Machine Operators and Assemblers</span></label>
                    <label><input type="radio" name="occupation" value="Elementary Occupations"> <span class="sm">○ Elementary Occupations</span></label>
                    <label><input type="radio" name="occupation" value="Armed Forces Occupations"> <span class="sm">○ Armed Forces Occupations</span></label>
                    <label style="align-items:center;">
                        <input type="radio" name="occupation" value="Others">
                        <span class="sm">○ Others, specify:</span>
                        <input type="text" name="occupation_other" class="fi" style="flex:1;min-width:30mm;margin-top:0;margin-left:1mm;">
                    </label>
                </div>
            </div>
        </div>

        {{-- ROW 10: Employment Status | Types | Category --}}
        <div class="row" style="align-items:stretch;">
            <div class="cell w28">
                <div class="fl">13. &nbsp;STATUS OF EMPLOYMENT: <span style="color:red;">*</span></div>
                <div class="cb-1col" style="margin-top:1mm;">
                    <label><input type="radio" name="employment_status" value="Employed"> <span class="sm">○ Employed</span></label>
                    <label><input type="radio" name="employment_status" value="Unemployed"> <span class="sm">○ Unemployed</span></label>
                    <label><input type="radio" name="employment_status" value="Self-employed"> <span class="sm">○ Self-employed</span></label>
                </div>
            </div>
            <div class="cell w28">
                <div class="fl">13 b. &nbsp;TYPES OF EMPLOYMENT: <span style="color:red;">*</span></div>
                <div class="cb-1col" style="margin-top:1mm;">
                    <label><input type="radio" name="employment_type" value="Permanent / Regular"> <span class="sm">○ Permanent / Regular</span></label>
                    <label><input type="radio" name="employment_type" value="Seasonal"> <span class="sm">○ Seasonal</span></label>
                    <label><input type="radio" name="employment_type" value="Casual"> <span class="sm">○ Casual</span></label>
                    <label><input type="radio" name="employment_type" value="Emergency"> <span class="sm">○ Emergency</span></label>
                </div>
            </div>
            <div class="cell wmx" style="border-right:none;">
                <div class="fl">13 a. &nbsp;CATEGORY OF EMPLOYMENT: <span style="color:red;">*</span></div>
                <div class="cb-1col" style="margin-top:1mm;">
                    <label><input type="radio" name="employment_category" value="Government"> <span class="sm">○ Government</span></label>
                    <label><input type="radio" name="employment_category" value="Private"> <span class="sm">○ Private</span></label>
                </div>
            </div>
        </div>

        {{-- ROW 11: Organization Info --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">15. &nbsp;ORGANIZATION INFORMATION:</div>
                <div style="display:grid;grid-template-columns:2fr 1.5fr 2fr 1fr;gap:0 2mm;margin-top:1mm;">
                    <div>
                        <div class="fl">Organization Affiliated:</div>
                        <input type="text" name="org_affiliated" class="fi" value="{{ old('org_affiliated') }}">
                    </div>
                    <div>
                        <div class="fl">Contact Person:</div>
                        <input type="text" name="org_contact_person" class="fi" value="{{ old('org_contact_person') }}">
                    </div>
                    <div>
                        <div class="fl">Office Address:</div>
                        <input type="text" name="org_address" class="fi" value="{{ old('org_address') }}">
                    </div>
                    <div>
                        <div class="fl">Tel. Nos.:</div>
                        <input type="text" name="org_tel" class="fi" value="{{ old('org_tel') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 12: ID Reference --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">16. &nbsp;ID REFERENCE NO.:</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:0 2mm;margin-top:1mm;">
                    <div><div class="fl">SSS NO.:</div><input type="text" name="sss_no" class="fi" value="{{ old('sss_no') }}"></div>
                    <div><div class="fl">GSIS NO.:</div><input type="text" name="gsis_no" class="fi" value="{{ old('gsis_no') }}"></div>
                    <div><div class="fl">PAG-IBIG NO.:</div><input type="text" name="pagibig_no" class="fi" value="{{ old('pagibig_no') }}"></div>
                    <div><div class="fl">PSN NO.:</div><input type="text" name="psn_no" class="fi" value="{{ old('psn_no') }}"></div>
                    <div><div class="fl">PhilHealth NO.:</div><input type="text" name="philhealth_no" class="fi" value="{{ old('philhealth_no') }}"></div>
                </div>
            </div>
        </div>

        {{-- ROW 13: Family Background --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;padding:1.5mm 2mm;">
                <table class="itbl">
                    <tr>
                        <td class="col-label"><div class="fl">17. &nbsp;FAMILY BACKGROUND:</div></td>
                        <td class="col-name" style="text-align:center;"><div class="fl">LAST NAME</div></td>
                        <td class="col-name" style="text-align:center;"><div class="fl">FIRST NAME</div></td>
                        <td style="text-align:center;"><div class="fl">MIDDLE NAME</div></td>
                    </tr>
                    <tr>
                        <td><div class="sm" style="font-style:italic;">FATHER'S NAME</div></td>
                        <td><input type="text" name="father_lname" class="fi" value="{{ old('father_lname') }}"></td>
                        <td><input type="text" name="father_fname" class="fi" value="{{ old('father_fname') }}"></td>
                        <td><input type="text" name="father_mname" class="fi" value="{{ old('father_mname') }}"></td>
                    </tr>
                    <tr>
                        <td><div class="sm" style="font-style:italic;">MOTHER'S NAME:</div></td>
                        <td><input type="text" name="mother_lname" class="fi" value="{{ old('mother_lname') }}"></td>
                        <td><input type="text" name="mother_fname" class="fi" value="{{ old('mother_fname') }}"></td>
                        <td><input type="text" name="mother_mname" class="fi" value="{{ old('mother_mname') }}"></td>
                    </tr>
                    <tr>
                        <td><div class="sm" style="font-style:italic;">GUARDIAN:</div></td>
                        <td><input type="text" name="guardian_lname" class="fi" value="{{ old('guardian_lname') }}"></td>
                        <td><input type="text" name="guardian_fname" class="fi" value="{{ old('guardian_fname') }}"></td>
                        <td><input type="text" name="guardian_mname" class="fi" value="{{ old('guardian_mname') }}"></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ROW 14: Accomplished By --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;padding:1.5mm 2mm;">
                <table class="itbl">
                    <tr>
                        <td class="col-label" style="vertical-align:top;">
                            <div class="fl">18. &nbsp;ACCOMPLISHED BY:</div>
                            <div class="cb-1col" style="margin-top:1mm;">
                                <label><input type="radio" name="accomplished_by" value="Applicant"> <span class="sm">○ &nbsp;APPLICANT</span></label>
                                <label><input type="radio" name="accomplished_by" value="Guardian"> <span class="sm">○ &nbsp;GUARDIAN</span></label>
                                <label><input type="radio" name="accomplished_by" value="Representative"> <span class="sm">○ &nbsp;REPRESENTATIVE</span></label>
                            </div>
                        </td>
                        <td class="col-name">
                            <div class="fl">LAST NAME</div>
                            <input type="text" name="accomplished_lname" class="fi" value="{{ old('accomplished_lname') }}">
                        </td>
                        <td class="col-name">
                            <div class="fl">FIRST NAME</div>
                            <input type="text" name="accomplished_fname" class="fi" value="{{ old('accomplished_fname') }}">
                        </td>
                        <td>
                            <div class="fl">MIDDLE NAME</div>
                            <input type="text" name="accomplished_mname" class="fi" value="{{ old('accomplished_mname') }}">
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ROW 15: Certifying Physician --}}
        <div class="row">
            <div class="cell wmx">
                <div class="fl">19. &nbsp;NAME OF CERTIFYING PHYSICIAN:</div>
                <input type="text" name="certifying_physician" class="fi" value="{{ old('certifying_physician') }}">
            </div>
            <div class="cell w35" style="border-right:none;">
                <div class="fl">LICENSE. NO.:</div>
                <input type="text" name="physician_license" class="fi" value="{{ old('physician_license') }}">
            </div>
        </div>

        {{-- ROW 16: Processing | Approving --}}
        <div class="row">
            <div class="cell w50">
                <div class="fl">20. &nbsp;PROCESSING OFFICER: <span style="color:red;">*</span></div>
                <input type="text" name="processing_officer" class="fi" value="{{ old('processing_officer') }}" placeholder="(to be filled by MSWDO)">
            </div>
            <div class="cell w50" style="border-right:none;">
                <div class="fl">21. &nbsp;APPROVING OFFICER: <span style="color:red;">*</span></div>
                <input type="text" name="approving_officer" class="fi" value="{{ old('approving_officer') }}" placeholder="(to be filled by MSWDO)">
            </div>
        </div>

        {{-- ROW 17: Encoder --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">22. &nbsp;ENCODER <span style="color:red;">*</span></div>
                <input type="text" name="encoder" class="fi" value="{{ old('encoder') }}" placeholder="(to be filled by MSWDO)">
            </div>
        </div>

        {{-- ROW 18: Reporting Unit --}}
        <div class="row">
            <div class="cell w100" style="border-right:none;">
                <div class="fl">23. &nbsp;NAME OF REPORTING UNIT: (OFFICE/SECTION)<span style="color:red;">*</span></div>
                <input type="text" name="reporting_unit" class="fi"
                       value="{{ old('reporting_unit', 'Municipal Social Welfare and Development Office (MSWDO)') }}">
            </div>
        </div>

        {{-- ROW 19: Control No + Revised Note --}}
        <div class="row" style="border-bottom:none;">
            <div class="cell wmx">
                <div class="fl">24. &nbsp;CONTROL NO.: <span style="color:red;">*</span></div>
                <input type="text" name="control_no" class="fi" value="{{ old('control_no') }}" placeholder="(to be filled by MSWDO)">
            </div>
            <div class="cell" style="border-right:none;width:35mm;display:flex;align-items:flex-end;justify-content:flex-end;padding-bottom:1.5mm;">
                <em style="font-size:6pt;color:#555;">Revised as of August 1, 2021</em>
            </div>
        </div>

    </div>{{-- /form-border --}}

    <!-- Hidden fields -->
    <input type="hidden" name="program_type" value="PWD_Assistance">
    <input type="hidden" name="municipality_hidden" value="{{ Auth::user()->municipality ?? '' }}">

</div>{{-- /a4 --}}
</div>{{-- /page-wrap --}}

<!-- ══ UPLOAD SECTION (screen only) ══ -->
<div class="upload-section no-print">
    <div class="upload-card">
        <h6 style="color:#2C3E8F;font-weight:800;margin-bottom:4px;font-size:.9rem;font-family:Arial,sans-serif;">
            📎 Upload Signed / Scanned Copy <span style="font-weight:400;color:#94a3b8;">(optional)</span>
        </h6>
        <p style="font-size:.8rem;color:#64748b;margin-bottom:12px;font-family:Arial,sans-serif;">
            If you printed, filled by hand and scanned the form, upload it here. Otherwise submitting online is sufficient.
        </p>
        <input type="file" name="signed_copy" accept=".jpg,.jpeg,.png,.pdf"
               style="font-size:.82rem;padding:8px;border:1px dashed #94a3b8;border-radius:8px;width:100%;background:#f8fafc;font-family:Arial,sans-serif;">
        <div style="font-size:.72rem;color:#94a3b8;margin-top:5px;font-family:Arial,sans-serif;">Max 5 MB · JPG, PNG, or PDF</div>

        @if($errors->any())
        <div style="background:#fee2e2;border-left:3px solid #dc3545;border-radius:8px;padding:10px 14px;margin-top:14px;font-size:.82rem;color:#721c24;font-family:Arial,sans-serif;">
            <strong>Please fix the following:</strong>
            <ul style="margin:4px 0 0 14px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

</form>

<!-- ══ SUCCESS MODAL ══ -->
<div class="smodal" id="successModal">
    <div class="sm-c">
        <div class="icon">🎉</div>
        <h3>Application Submitted!</h3>
        <p>Your PRPWD Application Form has been submitted. The MSWDO admin for your municipality will review it and notify you.</p>
        <div class="btns">
            <a href="{{ route('user.my-requirements') }}" class="p">View My Applications →</a>
            <button onclick="window.print()" class="s">🖨️ Print a Copy</button>
        </div>
    </div>
</div>

@if(session('pwd_form_success'))
<script>document.getElementById('successModal').classList.add('show');</script>
@endif

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
            document.getElementById('photoTxt').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function doSubmit() {
    const f = document.getElementById('prpwdForm');
    const fn = f.querySelector('[name=first_name]').value.trim();
    const ln = f.querySelector('[name=last_name]').value.trim();
    const dob = f.querySelector('[name=date_of_birth]').value;
    const sx  = f.querySelector('[name=sex]:checked');
    const cs  = f.querySelector('[name=civil_status]:checked');
    const dbs = f.querySelectorAll('[name="disability[]"]:checked');
    const bg  = f.querySelector('[name=barangay_address]').value.trim();

    if (!fn || !ln) return alert('Please enter your full name (sections 1-4).');
    if (!dob)       return alert('Please enter your date of birth (section 5).');
    if (!sx)        return alert('Please select your sex (section 6).');
    if (!cs)        return alert('Please select your civil status (section 7).');
    if (!dbs.length) return alert('Please select at least one type of disability (section 8).');
    if (!bg)        return alert('Please enter your barangay (section 10).');

    if (!confirm('Submit this PRPWD Application Form online?\n\nThe MSWDO admin for your municipality will be notified.\nYou can still print a copy after submitting.')) return;
    f.submit();
}
</script>
</body>
</html>
