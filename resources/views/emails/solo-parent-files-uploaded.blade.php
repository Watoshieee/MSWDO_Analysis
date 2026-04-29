<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>New Solo Parent Documents Uploaded</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Segoe UI',Arial,sans-serif;background:#f0f4ff;color:#1e293b;}
  .wrapper{max-width:620px;margin:32px auto;background:white;border-radius:18px;overflow:hidden;box-shadow:0 4px 24px rgba(44,62,143,.12);}
  .header{background:linear-gradient(135deg,#2C3E8F,#1A2A5C);padding:28px 32px;text-align:center;}
  .header h1{color:white;font-size:1.25rem;font-weight:800;margin-bottom:4px;}
  .header p{color:rgba(255,255,255,.75);font-size:.85rem;}
  .badge-row{background:#3b5bdb;color:white;text-align:center;padding:10px 20px;font-size:.82rem;font-weight:700;letter-spacing:.04em;}
  .body{padding:28px 32px;}
  .greeting{font-size:1rem;font-weight:700;margin-bottom:10px;}
  .intro{font-size:.9rem;color:#475569;line-height:1.7;margin-bottom:20px;}
  .detail-box{background:#f8faff;border:1.5px solid #c7d2fe;border-radius:12px;padding:18px 20px;margin-bottom:18px;}
  .detail-box h3{font-size:.82rem;text-transform:uppercase;letter-spacing:.06em;color:#6366f1;font-weight:700;margin-bottom:12px;}
  .detail-row{display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #e0e7ff;font-size:.85rem;}
  .detail-row:last-child{border-bottom:none;}
  .detail-label{color:#64748b;font-weight:600;}
  .detail-value{color:#1e293b;font-weight:700;text-align:right;max-width:60%;}
  .file-table{width:100%;border-collapse:collapse;font-size:.82rem;margin-top:10px;}
  .file-table th{background:#e0e7ff;color:#3730a3;font-weight:700;padding:8px 12px;text-align:left;}
  .file-table td{padding:8px 12px;border-bottom:1px solid #f1f5f9;color:#1e293b;}
  .file-table tr:last-child td{border-bottom:none;}
  .badge-pending{background:#fff8e1;color:#856404;border-radius:20px;padding:2px 10px;font-size:.75rem;font-weight:700;}
  .cta-btn{display:block;text-align:center;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;text-decoration:none;border-radius:10px;padding:13px 28px;font-weight:800;font-size:.95rem;margin:22px 0;}
  .footer{background:#f8faff;padding:18px 32px;text-align:center;font-size:.78rem;color:#94a3b8;border-top:1px solid #e0e7ff;}
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🏛 MSWDO — Municipal Social Welfare</h1>
    <p>Municipal Social Welfare and Development Office</p>
  </div>

  <div class="badge-row">📁 Action Required — Solo Parent Documents Submitted for Review</div>

  <div class="body">
    <p class="greeting">Hello, Admin!</p>
    <p class="intro">
      A Solo Parent applicant has uploaded their required documents and is waiting for your review.
      Please log in to the MSWDO portal to approve or decline each file.
    </p>

    <div class="detail-box">
      <h3>👤 Applicant Details</h3>
      <div class="detail-row">
        <span class="detail-label">Full Name</span>
        <span class="detail-value">{{ $fileMonitoring->application?->full_name ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $fileMonitoring->application?->user?->email ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Municipality</span>
        <span class="detail-value">{{ $fileMonitoring->municipality }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Program</span>
        <span class="detail-value">Solo Parent ID</span>
      </div>
    </div>

    <div class="detail-box">
      <h3>📄 Uploaded Documents</h3>
      @php $uploads = $fileMonitoring->fileUploads->whereNotNull('file_path'); @endphp
      @if($uploads->count())
      <table class="file-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Document</th>
            <th>File</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($uploads as $i => $f)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $f->requirement_name }}</td>
            <td>{{ $f->file_name ?? 'uploaded' }}</td>
            <td><span class="badge-pending">⏳ Pending</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <p style="color:#94a3b8;font-size:.85rem;">No files uploaded yet.</p>
      @endif
    </div>

    <a href="{{ url('/admin/requirements') }}" class="cta-btn">📋 Review Documents Now</a>

    <p style="font-size:.82rem;color:#64748b;line-height:1.6;">
      Please review and approve or decline each document at your earliest convenience.
      The applicant has been notified that their documents are under review.
    </p>
  </div>

  <div class="footer">
    MSWDO — Municipal Social Welfare &amp; Development Office<br>
    This is an automated notification. Do not reply to this email.
  </div>
</div>
</body>
</html>
