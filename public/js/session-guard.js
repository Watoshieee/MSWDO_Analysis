/**
 * session-guard.js
 * ─────────────────────────────────────────────────────────────────────────
 * • Pings /session/ping every 45 s while the tab is visible (keeps the
 *   Laravel session alive while the user is actively using the app)
 * • Auto-logout feature DISABLED
 * ─────────────────────────────────────────────────────────────────────────
 */
(function () {
    'use strict';

    const PING_EVERY  = 45 * 1000;        // heartbeat interval
    const PING_URL    = '/session/ping';

    /* ── CSRF helper ── */
    const csrf = () => {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    };

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

    /* ── Boot ── */
    if (!csrf()) return;       // not an authenticated page

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopPing();
        } else {
            startPing();
        }
    });

    if (!document.hidden) startPing();

})();
