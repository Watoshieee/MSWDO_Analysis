<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - MSWDO Application System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-light: #2563eb;
            --primary-bg: #eff6ff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --card-bg: #ffffff;
            --bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            line-height: 1.65;
            padding: 24px 16px;
        }

        .container {
            max-width: 860px;
            margin: 0 auto;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: #ffffff;
            padding: 36px 32px;
            text-align: center;
        }

        .header-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            padding: 4px 14px;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .header h1 {
            font-size: 1.85rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .header p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .content {
            padding: 36px 32px;
        }

        .meta-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            padding-bottom: 20px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .meta-tag {
            background: var(--primary-bg);
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .section {
            margin-bottom: 28px;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section p {
            font-size: 0.95rem;
            color: #334155;
            margin-bottom: 8px;
        }

        .section p:last-child {
            margin-bottom: 0;
        }

        .section ul {
            list-style-type: disc;
            padding-left: 24px;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .section li {
            font-size: 0.95rem;
            color: #334155;
            margin-bottom: 4px;
        }

        .contact-box {
            background: #f1f5f9;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px 20px;
            margin-top: 12px;
        }

        .contact-box p {
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .contact-box p:last-child {
            margin-bottom: 0;
        }

        .contact-email {
            color: var(--primary-light);
            font-weight: 600;
            text-decoration: none;
        }

        .contact-email:hover {
            text-decoration: underline;
        }

        .footer-action {
            text-align: center;
            padding: 24px 32px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary);
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.15s ease;
        }

        .btn-back:hover {
            background: var(--primary-light);
        }

        @media (max-width: 640px) {
            body {
                padding: 12px 8px;
            }
            .header {
                padding: 28px 20px;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .content {
                padding: 24px 18px;
            }
            .meta-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <span class="header-badge">Official Policy</span>
        <h1>Privacy Policy</h1>
        <p>Municipal Social Welfare and Development Office (MSWDO) Application System</p>
    </div>

    <div class="content">
        <div class="meta-bar">
            <span><strong>Governing Law:</strong> Data Privacy Act of 2012 (Republic Act No. 10173)</span>
            <span class="meta-tag">Public Document</span>
        </div>

        <div class="section">
            <h2 class="section-title">1. Introduction</h2>
            <p>This Privacy Policy explains how your personal data is collected, used, and protected in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173) and other applicable Philippine laws.</p>
            <p>By accessing or using the MSWDO Application System and Beneficiary Portal, you acknowledge that you have read and understood this Privacy Policy.</p>
        </div>

        <div class="section">
            <h2 class="section-title">2. Information We Collect</h2>
            <p>We collect only the information necessary to evaluate, process, and verify social welfare and assistance applications:</p>
            <ul>
                <li><strong>Personal Information:</strong> Full name, residential address, contact number, email address, date of birth, and civil status.</li>
                <li><strong>Application Details:</strong> Program applied for (e.g., Solo Parent, PWD, AICS, Senior Citizen), household details, and socio-economic background information.</li>
                <li><strong>Uploaded Documents:</strong> Digital copies of supporting requirements (government IDs, medical certificates, barangay certificates, affidavits, and proof of income/residence in PDF or image format).</li>
                <li><strong>System Usage Data:</strong> Login timestamps, device type, application submission logs, and account status updates for verification and security audit purposes.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">3. Purpose of Data Collection</h2>
            <p>Your personal information is collected and processed exclusively for legitimate public service and administrative purposes:</p>
            <ul>
                <li>To assess eligibility and process social welfare and assistance applications.</li>
                <li>To verify applicant identity and validate submitted documentary requirements.</li>
                <li>To communicate official updates, scheduled appointments, and application status results.</li>
                <li>To maintain accurate beneficiary registries and prevent duplicate or fraudulent claims.</li>
                <li>To improve system performance, data security, and public service delivery.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">4. Data Sharing & Disclosure</h2>
            <p>Your personal data is treated with strict confidentiality:</p>
            <ul>
                <li>Information is accessible only to authorized MSWDO personnel directly involved in application evaluation and program administration.</li>
                <li>Information may be shared with authorized Philippine government agencies only when required by law or valid governmental regulation.</li>
                <li><strong>We do not sell, rent, trade, or share your personal data with any third parties for commercial or marketing purposes.</strong></li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">5. Data Protection & Security</h2>
            <p>We implement appropriate technical, organizational, and physical security measures to safeguard your personal data from unauthorized access, alteration, disclosure, or loss, including:</p>
            <ul>
                <li>Secure authentication and encrypted password storage.</li>
                <li>Role-based access controls limiting data access to authorized personnel only.</li>
                <li>Encrypted data transmission over HTTPS/TLS protocols.</li>
                <li>Periodic security reviews and server-side data protection practices.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">6. Data Retention</h2>
            <p>Your personal data and uploaded documents will be retained only for as long as necessary to:</p>
            <ul>
                <li>Fulfill the specific welfare assistance or service requested.</li>
                <li>Maintain official government records in compliance with applicable public auditing and administrative retention standards.</li>
                <li>Resolve disputes or meet statutory requirements.</li>
            </ul>
            <p>Once the retention period expires, records are securely archived or disposed of in accordance with the National Archives of the Philippines guidelines and the Data Privacy Act.</p>
        </div>

        <div class="section">
            <h2 class="section-title">7. Your Rights (Under Philippine Law)</h2>
            <p>Under the Data Privacy Act of 2012, as a data subject, you have the right to:</p>
            <ul>
                <li><strong>Be informed:</strong> Know how your personal information is collected, handled, and processed.</li>
                <li><strong>Access:</strong> Request reasonable access to your personal data held by the MSWDO.</li>
                <li><strong>Correction:</strong> Request the correction or updating of inaccurate, outdated, or incomplete data.</li>
                <li><strong>Erasure or Blocking:</strong> Request the suspension, withdrawal, or deletion of personal data, subject to legal and governmental retention limitations.</li>
                <li><strong>Damages:</strong> Be indemnified for any damages sustained due to inaccurate, false, unlawfully obtained, or unauthorized use of personal data.</li>
                <li><strong>File a Complaint:</strong> Lodge a complaint with the National Privacy Commission if you believe your privacy rights have been violated.</li>
            </ul>
        </div>

        <div class="section">
            <h2 class="section-title">8. Contact Information</h2>
            <p>If you have questions, feedback, or concerns regarding this Privacy Policy or the handling of your personal data, you may contact our office:</p>
            <div class="contact-box">
                <p><strong>MSWDO Office</strong></p>
                <p>Email: <a href="mailto:mswdo.mswdo.org@gmail.com" class="contact-email">mswdo.mswdo.org@gmail.com</a></p>
            </div>
            <p style="margin-top: 12px; font-size: 0.9rem; color: var(--text-muted);">You may also reach the <strong>National Privacy Commission (NPC)</strong> for inquiries or complaints regarding privacy rights under Republic Act No. 10173.</p>
        </div>

        <div class="section">
            <h2 class="section-title">9. Updates to Privacy Policy</h2>
            <p>The MSWDO reserves the right to update or modify this Privacy Policy as needed to reflect changes in regulatory requirements, administrative procedures, or system improvements.</p>
            <p>Any updates will be posted on this page with an updated revision date. Continued use of the MSWDO Application System constitutes acceptance of any modifications.</p>
        </div>
    </div>

    <div class="footer-action">
        <a href="/" class="btn-back">
            &larr; Return to Home
        </a>
    </div>
</div>

</body>
</html>
