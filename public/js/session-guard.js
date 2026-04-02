/**
 * session-guard.js
 * ─────────────────────────────────────────────────────────────────────────
 * • Pings /session/ping every 45 s while the tab is visible (keeps the
 *   Laravel session alive while the user is actively using the app).
 * • When the tab is hidden/closed, stops pinging and records the timestamp.
 * • On next page load / tab restore, if ≥ 2 min have passed, auto-logout.
 * ─────────────────────────────────────────────────────────────────────────
 */
(function () {
    'use strict';

    const TIMEOUT_MS  = 2 * 60 * 1000;   // 2 minutes
    const PING_EVERY  = 45 * 1000;        // heartbeat interval
    const STORAGE_KEY = '_sg_hidden';
    const PING_URL    = '/session/ping';
    const LOGOUT_URL  = '/logout';

    /* ── CSRF helper ── */
    const csrf = () => {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    };

    /* ── Submit logout form ── */
    function doLogout() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = LOGOUT_URL;
        f.style.display = 'none';
        const t = document.createElement('input');
        t.type  = 'hidden';
        t.name  = '_token';
        t.value = csrf();
        f.appendChild(t);
        document.body.appendChild(f);
        f.submit();
    }

    /* ── Heartbeat ping ── */
    function ping() {
        fetch(PING_URL, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).catch(() => {});
    }

    /* ── Ping timer ── */
    let pingTimer = null;

    function startPing() {
        if (!pingTimer) {
            ping();
            pingTimer = setInterval(ping, PING_EVERY);
        }
    }

    function stopPing() {
        clearInterval(pingTimer);
        pingTimer = null;
    }

    /* ── Check if session expired while tab was away ── */
    function checkExpiry() {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return false;
        if (Date.now() - parseInt(raw, 10) >= TIMEOUT_MS) {
            localStorage.removeItem(STORAGE_KEY);
            doLogout();
            return true;
        }
        return false;
    }

    /* ── Boot ── */
    if (!csrf()) return;       // not an authenticated page
    if (checkExpiry()) return; // tab was away too long — logging out

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            localStorage.setItem(STORAGE_KEY, String(Date.now()));
            stopPing();
        } else {
            if (!checkExpiry()) {
                localStorage.removeItem(STORAGE_KEY);
                startPing();
            }
        }
    });

    if (!document.hidden) startPing();

})();
