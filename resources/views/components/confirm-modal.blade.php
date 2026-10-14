{{--
    Global Confirm Modal Component
    Include once in your layout (admin-navbar, user-navbar).
    JS API:
      uiConfirm({ title, message, confirmText, cancelText, confirmClass })
        → returns Promise<boolean>
      confirmForm(formEl, { title, message, confirmText, cancelText })
        → intercepts form submit, shows modal, submits on confirm
--}}

{{-- Modal HTML --}}
<div id="globalConfirmModal"
     style="display:none;position:fixed;inset:0;z-index:999999;align-items:center;justify-content:center;padding:16px;background:rgba(15,23,42,.55);"
     role="dialog" aria-modal="true" aria-labelledby="gcmTitle">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:480px;box-shadow:0 24px 60px rgba(15,23,42,.28);overflow:hidden;animation:gcmIn .22s cubic-bezier(.22,1,.36,1);">
        {{-- Header --}}
        <div id="gcmHeader" style="background:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);color:#fff;padding:18px 22px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
            <span id="gcmTitle" style="font-size:1.05rem;font-weight:800;letter-spacing:-.01em;"></span>
            <button id="gcmClose" type="button" aria-label="Close"
                    style="background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;cursor:pointer;flex-shrink:0;line-height:1;transition:background .15s;">
                &times;
            </button>
        </div>
        {{-- Body --}}
        <div style="padding:22px 24px 10px;">
            <p id="gcmMessage" style="margin:0;font-size:.93rem;color:#334155;line-height:1.65;"></p>
        </div>
        {{-- Footer --}}
        <div style="padding:12px 24px 20px;display:flex;justify-content:flex-end;gap:10px;">
            <button id="gcmCancel" type="button"
                    style="background:#f1f5f9;color:#475569;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:background .15s;">
                Cancel
            </button>
            <button id="gcmConfirm" type="button"
                    style="background:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);color:#fff;border:none;border-radius:10px;padding:9px 22px;font-weight:700;font-size:.88rem;cursor:pointer;box-shadow:0 3px 10px rgba(44,62,143,.3);transition:opacity .15s;">
                Confirm
            </button>
        </div>
    </div>
</div>

<style>
@keyframes gcmIn {
    from { opacity:0; transform:scale(.94) translateY(-10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
#gcmClose:hover { background:rgba(255,255,255,.28) !important; }
#gcmCancel:hover { background:#e2e8f0 !important; }
#gcmConfirm:hover { opacity:.88; }
#gcmConfirm:disabled { opacity:.55; cursor:not-allowed; }
</style>

<script>
(function () {
    let _resolve = null;
    let _submitting = false;

    const modal   = document.getElementById('globalConfirmModal');
    const title   = document.getElementById('gcmTitle');
    const message = document.getElementById('gcmMessage');
    const btnConfirm = document.getElementById('gcmConfirm');
    const btnCancel  = document.getElementById('gcmCancel');
    const btnClose   = document.getElementById('gcmClose');

    function showModal() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        btnConfirm.disabled = false;
        btnConfirm.textContent = btnConfirm.dataset.label || 'Confirm';
        requestAnimationFrame(() => btnConfirm.focus());
    }

    function hideModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        _submitting = false;
    }

    function settle(val) {
        if (_resolve) { _resolve(val); _resolve = null; }
        hideModal();
    }

    btnConfirm.addEventListener('click', function () {
        if (_submitting) return;
        _submitting = true;
        btnConfirm.disabled = true;
        settle(true);
    });
    btnCancel.addEventListener('click', () => settle(false));
    btnClose.addEventListener('click',  () => settle(false));

    // Close on backdrop click
    modal.addEventListener('click', function (e) {
        if (e.target === modal) settle(false);
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') settle(false);
    });

    /**
     * Show a custom confirm dialog.
     * @param {Object|string} opts  - options object or plain message string
     * @returns {Promise<boolean>}
     */
    window.uiConfirm = function (opts) {
        if (typeof opts === 'string') opts = { message: opts };
        opts = Object.assign({
            title:        'Confirm Action',
            message:      'Are you sure you want to proceed?',
            confirmText:  'Confirm',
            cancelText:   'Cancel',
            confirmClass: 'default', // 'danger' | 'warning' | 'default'
        }, opts);

        title.textContent   = opts.title;
        message.textContent = opts.message;
        btnConfirm.dataset.label  = opts.confirmText;
        btnConfirm.textContent    = opts.confirmText;
        btnCancel.textContent     = opts.cancelText;

        // Colour the confirm button based on action type
        if (opts.confirmClass === 'danger') {
            btnConfirm.style.background = 'linear-gradient(135deg,#dc2626 0%,#991b1b 100%)';
            btnConfirm.style.boxShadow  = '0 3px 10px rgba(220,38,38,.3)';
        } else if (opts.confirmClass === 'warning') {
            btnConfirm.style.background = 'linear-gradient(135deg,#d97706 0%,#92400e 100%)';
            btnConfirm.style.boxShadow  = '0 3px 10px rgba(217,119,6,.3)';
        } else {
            btnConfirm.style.background = 'linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%)';
            btnConfirm.style.boxShadow  = '0 3px 10px rgba(44,62,143,.3)';
        }

        return new Promise(function (resolve) {
            _resolve = resolve;
            showModal();
        });
    };

    /**
     * Attach a confirm dialog to a form element.
     * The form will only submit after the user confirms.
     * @param {HTMLFormElement} form
     * @param {Object} opts  - same as uiConfirm opts
     */
    window.confirmForm = function (form, opts) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const ok = await window.uiConfirm(opts);
            if (ok) {
                // Disable submit to prevent double-submit
                const btn = form.querySelector('[type="submit"]');
                if (btn) btn.disabled = true;
                form.submit();
            }
        });
    };

    /**
     * Quick helper — call from onclick to confirm then submit the closest form.
     * Usage: onclick="confirmAndSubmit(this, {...opts})"
     */
    window.confirmAndSubmit = async function (el, opts) {
        const ok = await window.uiConfirm(opts);
        if (ok) {
            const form = el.closest ? el.closest('form') : el.parentElement;
            if (form) {
                const btn = form.querySelector('[type="submit"]');
                if (btn) btn.disabled = true;
                form.submit();
            }
        }
    };
})();
</script>
