<!-- Floating Settings Button -->
<button class="floating-btn floating-btn-settings" onclick="openSettingsModal()" data-tooltip="Customize Colors">
    ⚙️
</button>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:24px;">
                <h5 class="modal-title" style="font-weight:800;font-size:1.2rem;">⚙️ Customize Colors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body" style="padding:28px;">
                <div class="row">
                    <div class="col-md-6">
                        <h6 style="color:var(--primary-blue);font-weight:700;margin-bottom:20px;">Color Customization</h6>
                        
                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-weight:700;color:var(--primary-blue);margin-bottom:8px;font-size:0.85rem;">Primary Color (Navbar, Headers)</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" id="modalPrimaryColor" value="{{ $adminPrimaryColor ?? '#2C3E8F' }}" style="width:60px;height:50px;border:2px solid #e2e8f0;border-radius:12px;cursor:pointer;">
                                <input type="text" id="modalPrimaryColorText" value="{{ $adminPrimaryColor ?? '#2C3E8F' }}" readonly style="flex:1;border:2px solid #e2e8f0;border-radius:12px;padding:12px 16px;font-family:'Courier New',monospace;font-weight:600;font-size:0.9rem;background:#f8fafc;color:#1e293b;">
                            </div>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-weight:700;color:var(--primary-blue);margin-bottom:8px;font-size:0.85rem;">Secondary Color (Buttons, Highlights)</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" id="modalSecondaryColor" value="{{ $adminSecondaryColor ?? '#FDB913' }}" style="width:60px;height:50px;border:2px solid #e2e8f0;border-radius:12px;cursor:pointer;">
                                <input type="text" id="modalSecondaryColorText" value="{{ $adminSecondaryColor ?? '#FDB913' }}" readonly style="flex:1;border:2px solid #e2e8f0;border-radius:12px;padding:12px 16px;font-family:'Courier New',monospace;font-weight:600;font-size:0.9rem;background:#f8fafc;color:#1e293b;">
                            </div>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-weight:700;color:var(--primary-blue);margin-bottom:8px;font-size:0.85rem;">Accent Color (Alerts, Delete Actions)</label>
                            <div style="display:flex;gap:10px;align-items:center;">
                                <input type="color" id="modalAccentColor" value="{{ $adminAccentColor ?? '#C41E24' }}" style="width:60px;height:50px;border:2px solid #e2e8f0;border-radius:12px;cursor:pointer;">
                                <input type="text" id="modalAccentColorText" value="{{ $adminAccentColor ?? '#C41E24' }}" readonly style="flex:1;border:2px solid #e2e8f0;border-radius:12px;padding:12px 16px;font-family:'Courier New',monospace;font-weight:600;font-size:0.9rem;background:#f8fafc;color:#1e293b;">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 style="color:var(--primary-blue);font-weight:700;margin-bottom:20px;">Live Preview</h6>
                        <div style="background:white;border-radius:14px;padding:24px;border:2px solid #e2e8f0;">
                            <div id="modalPreviewNavbar" style="background:var(--primary-gradient);color:white;padding:16px 20px;border-radius:12px;margin-bottom:16px;font-weight:700;font-size:0.95rem;">
                                Sample Navbar
                            </div>
                            <button id="modalPreviewButton" style="background:var(--secondary-yellow);color:var(--primary-blue);border:none;padding:10px 24px;border-radius:10px;font-weight:700;margin-right:10px;font-size:0.9rem;cursor:default;">
                                Sample Button
                            </button>
                            <button id="modalPreviewAccent" style="background:var(--accent-red);color:white;border:none;padding:10px 24px;border-radius:10px;font-weight:700;font-size:0.9rem;cursor:default;">
                                Delete Action
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;padding:20px 28px;gap:10px;background:#f8fafc;display:flex;">
                <button type="button" onclick="resetModalColors()" style="background:white;border:2px solid #e2e8f0;color:#64748b;border-radius:12px;padding:12px 24px;font-weight:700;font-size:0.9rem;flex:1;cursor:pointer;transition:all 0.2s;">
                    🔄 Reset to Default
                </button>
                <button type="button" onclick="saveModalColors()" style="background:var(--primary-gradient);color:white;border:none;border-radius:12px;padding:12px 24px;font-weight:700;font-size:0.9rem;flex:1;cursor:pointer;transition:all 0.2s;">
                    💾 Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openSettingsModal() {
        // Load current colors from CSS variables or defaults
        const currentPrimary = getComputedStyle(document.documentElement).getPropertyValue('--primary-blue').trim() || '#2C3E8F';
        const currentSecondary = getComputedStyle(document.documentElement).getPropertyValue('--secondary-yellow').trim() || '#FDB913';
        const currentAccent = getComputedStyle(document.documentElement).getPropertyValue('--accent-red').trim() || '#C41E24';

        // Fetch saved colors from server
        fetch('/admin/settings/get', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('modalPrimaryColor').value = data.primary_color || currentPrimary;
            document.getElementById('modalPrimaryColorText').value = data.primary_color || currentPrimary;
            document.getElementById('modalSecondaryColor').value = data.secondary_color || currentSecondary;
            document.getElementById('modalSecondaryColorText').value = data.secondary_color || currentSecondary;
            document.getElementById('modalAccentColor').value = data.accent_color || currentAccent;
            document.getElementById('modalAccentColorText').value = data.accent_color || currentAccent;
            updateModalPreview();
        })
        .catch(() => {
            document.getElementById('modalPrimaryColor').value = currentPrimary;
            document.getElementById('modalPrimaryColorText').value = currentPrimary;
            document.getElementById('modalSecondaryColor').value = currentSecondary;
            document.getElementById('modalSecondaryColorText').value = currentSecondary;
            document.getElementById('modalAccentColor').value = currentAccent;
            document.getElementById('modalAccentColorText').value = currentAccent;
            updateModalPreview();
        });

        const modal = new bootstrap.Modal(document.getElementById('settingsModal'));
        modal.show();
    }

    // Update text inputs and preview when color picker changes
    document.getElementById('modalPrimaryColor').addEventListener('input', function(e) {
        document.getElementById('modalPrimaryColorText').value = e.target.value;
        updateModalPreview();
    });

    document.getElementById('modalSecondaryColor').addEventListener('input', function(e) {
        document.getElementById('modalSecondaryColorText').value = e.target.value;
        updateModalPreview();
    });

    document.getElementById('modalAccentColor').addEventListener('input', function(e) {
        document.getElementById('modalAccentColorText').value = e.target.value;
        updateModalPreview();
    });

    function updateModalPreview() {
        const primary = document.getElementById('modalPrimaryColor').value;
        const secondary = document.getElementById('modalSecondaryColor').value;
        const accent = document.getElementById('modalAccentColor').value;

        document.getElementById('modalPreviewNavbar').style.background = `linear-gradient(135deg, ${primary} 0%, #1A2A5C 100%)`;
        document.getElementById('modalPreviewButton').style.background = secondary;
        document.getElementById('modalPreviewButton').style.color = primary;
        document.getElementById('modalPreviewAccent').style.background = accent;
    }

    function saveModalColors() {
        const data = {
            primary_color: document.getElementById('modalPrimaryColor').value,
            secondary_color: document.getElementById('modalSecondaryColor').value,
            accent_color: document.getElementById('modalAccentColor').value
        };

        fetch('/admin/settings/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                showSettingsToast(result.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showSettingsToast('Error saving settings.', 'danger');
            }
        })
        .catch(() => showSettingsToast('Network error.', 'danger'));
    }

    function resetModalColors() {
        if (!confirm('Reset all colors to default values?')) return;

        fetch('/admin/settings/reset', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                showSettingsToast(result.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
                setTimeout(() => location.reload(), 1500);
            } else {
                showSettingsToast('Error resetting settings.', 'danger');
            }
        })
        .catch(() => showSettingsToast('Network error.', 'danger'));
    }

    function showSettingsToast(message, type = 'success') {
        const colors = { success: '#2C3E8F', danger: '#C41E24', warning: '#E5A500' };
        const t = document.createElement('div');
        t.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:9999;background:${colors[type]};color:white;padding:14px 22px;border-radius:12px;font-weight:600;font-size:.9rem;box-shadow:0 8px 24px rgba(0,0,0,.18);`;
        t.textContent = message;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3500);
    }
</script>
