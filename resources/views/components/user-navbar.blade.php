{{--
MSWDO Unified User Navigation Bar, Quick Actions Dock & Chat Drawer
Includes: Header Navbar, Quick Action Floating Dock, and Two-Channel Chat Drawer (AI + Staff)
Included with: @include('components.user-navbar', ['active' => 'programs'])
--}}

@php
    $currentActive = $active ?? null;
    if (!$currentActive) {
        if (request()->routeIs('user.dashboard') || request()->is('user/dashboard') || request()->is('dashboard')) {
            $currentActive = 'dashboard';
        } elseif (request()->routeIs('user.programs') || request()->is('user/programs')) {
            $currentActive = 'programs';
        } elseif (request()->routeIs('user.profile') || request()->is('user/profile')) {
            $currentActive = 'profile';
        } elseif (request()->routeIs('user.my-requirements') || request()->is('user/my-requirements')) {
            $currentActive = 'my-requirements';
        } elseif (request()->routeIs('user.announcements') || request()->is('user/announcements')) {
            $currentActive = 'announcements';
        } elseif (request()->routeIs('user.download-app') || request()->is('user/download-app')) {
            $currentActive = 'download-app';
        } elseif (request()->is('analysis*')) {
            $currentActive = 'analysis';
        }
    }

    $user = Auth::user();
    $municipality = $user->municipality ?? 'Majayjay';
    $municipalityLabel = ucfirst($municipality);
    $fullName = $user ? ($user->full_name ?? $user->name ?? 'Member') : 'Member';
    $nameParts = explode(' ', trim($fullName));
    $firstName = $nameParts[0] ?? 'Member';

    // Initials for avatar
    $initials = '';
    if (count($nameParts) >= 2) {
        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
    } else {
        $initials = strtoupper(substr($firstName, 0, 2));
    }

    $unreadNotifications = $notificationCount ?? 0;
    $unreadAnnouncements = $newAnnouncementCount ?? 0;
@endphp

<!-- SVG sprite for icons --><svg width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden;pointer-events:none;" aria-hidden="true"><defs>
<symbol id="i-menu" viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></symbol>
<symbol id="i-close" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></symbol>
<symbol id="i-chat" viewBox="0 0 24 24"><path d="M21 11.5a8.4 8.4 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.4 8.4 0 01-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.4 8.4 0 013.8-.9h.5a8.5 8.5 0 018 8z"/></symbol>
<symbol id="i-bell" viewBox="0 0 24 24"><path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></symbol>
<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></symbol>
<symbol id="i-file" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M8 13h8M8 17h8"/></symbol>
<symbol id="i-upload" viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8zM14 3v5h5M12 17v-6M9.5 13.5L12 11l2.5 2.5"/></symbol>
<symbol id="i-news" viewBox="0 0 24 24"><path d="M3 11v2a1 1 0 001 1h2l5 4V6L6 10H4a1 1 0 00-1 1zM15.5 8.5a5 5 0 010 7"/></symbol>
<symbol id="i-chart" viewBox="0 0 24 24"><path d="M12 20V10M18 20V4M6 20v-4"/></symbol>
<symbol id="i-phone" viewBox="0 0 24 24"><rect x="7" y="2" width="10" height="20" rx="2.5"/><path d="M11 18h2"/></symbol>
<symbol id="i-user" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></symbol>
<symbol id="i-out" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></symbol>
<symbol id="i-spark" viewBox="0 0 24 24"><path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8zM19 16l.8 2.2L22 19l-2.2.8L19 22l-.8-2.2L16 19l2.2-.8z"/></symbol>
<symbol id="i-send" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></symbol>
<symbol id="i-clip" viewBox="0 0 24 24"><path d="M21 12.5l-8.5 8.5a5.5 5.5 0 01-7.8-7.8L13.5 4.5a3.7 3.7 0 015.2 5.2l-8.5 8.5a1.8 1.8 0 01-2.6-2.6L15 7"/></symbol>
<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></symbol>
<symbol id="i-ok" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l3 3 5-6"/></symbol>
<symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
<symbol id="i-no" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></symbol>
<symbol id="i-layers" viewBox="0 0 24 24"><path d="M4 7l8-4 8 4-8 4zM4 12l8 4 8-4M4 17l8 4 8-4"/></symbol>
<symbol id="i-pwd" viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="M12 8v6l-3 6M12 14l4 6M8 10h8"/></symbol>
<symbol id="i-family" viewBox="0 0 24 24"><circle cx="9" cy="7" r="3"/><path d="M3 21v-3a5 5 0 0110 0v3M17 11a2.5 2.5 0 100-5M21 21v-2.5a4 4 0 00-3-3.9"/></symbol>
<symbol id="i-home" viewBox="0 0 24 24"><path d="M3 11l9-8 9 8M5 10v10h14V10M12 15v-3M10.5 13.5h3"/></symbol>
<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
<symbol id="i-down" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></symbol>
<symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v5h1"/></symbol>
</defs></svg>
<style>
/* Shared header, dock & chat drawer styles */
:root{--navy:#1e2a78;--navy-d:#1a2470;--gold:#f5b82e;--gold-h:#ffc94d;--ink:#1b2559;--muted:#4d5878;--bg:#f4f6fb;--line:#e1e6f2;--soft:#eef1f8;--tint:#eef1fb}
.i{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex:none}
.i-sm{width:17px;height:17px}.i-lg{width:32px;height:32px}
#chatBtn, #mswdo-bot-toggle { display: none !important; }

.dock a svg, .dock button svg, .cx-panel svg {
  width: 20px;
  height: 20px;
  fill: none;
  stroke: currentColor;
  stroke-width: 2;
  stroke-linecap: round;
  stroke-linejoin: round;
  pointer-events: none;
  flex: none;
}
.dock a svg.i-sm, .dock button svg.i-sm, .cx-panel svg.i-sm { width: 17px; height: 17px; }
.dock a svg.i-lg, .dock button svg.i-lg, .cx-panel svg.i-lg { width: 32px; height: 32px; }

/* nav: redesigned header + navigation */
html,body{margin:0;padding:0}
.hd-extras{position:absolute!important;left:0;top:0;width:0!important;height:0!important;margin:0!important;padding:0!important;overflow:visible;border:0;pointer-events:none}
.hd-extras .cx-panel.on,.hd-extras .cx-scrim.on,.hd-extras #globalConfirmModal,.hd-extras .dock{pointer-events:auto}
.hd{position:sticky;top:0;z-index:60;margin:0;padding:0;flex:none;align-self:stretch;background:linear-gradient(90deg,#1a2470,#243088);color:#fff;border-bottom:1px solid rgba(255,255,255,.1);transition:box-shadow .2s}
.hd.stuck{box-shadow:0 6px 18px rgba(20,27,77,.28)}
.hd-in{max-width:1280px;margin:0 auto;padding:0 32px;height:60px;box-sizing:border-box;display:flex;align-items:center;gap:8px}
.hd a:focus-visible,.hd button:focus-visible{outline:2px solid #f5b82e;outline-offset:2px}
.hd-brand{display:flex;align-items:center;gap:10px;flex:none;color:#fff;font-size:16px;font-weight:800;white-space:nowrap}
.hd-brand:hover{text-decoration:none;color:#fff}
.hd-logo{width:32px;height:32px;border-radius:8px;background:#fff;display:grid;place-items:center;flex:none}
.hd-menu{display:none;place-items:center;width:40px;height:40px;margin-left:-6px;border-radius:10px;border:0;padding:0;background:transparent;color:#fff;cursor:pointer}
.hd-menu:hover{background:rgba(255,255,255,.14)}
.nv{display:flex;align-items:center;min-width:0}
.nv-head,.nv-foot,.nv-scrim{display:none}
.nv-list{display:flex;gap:2px;list-style:none;margin:0 0 0 20px;padding:0}
.nv-list a{display:flex;align-items:center;gap:8px;height:40px;padding:0 12px;border-radius:10px;color:#e6eaff;font-size:13px;font-weight:600;white-space:nowrap;text-decoration:none}
.nv-list a:hover{background:rgba(255,255,255,.12);color:#fff;text-decoration:none}
.nv-list a[aria-current="page"],.nv-list a[aria-current="page"]:hover{background:#f5b82e;color:#172066;font-weight:700}
.nv-count{min-width:20px;height:20px;box-sizing:border-box;padding:0 6px;border-radius:99px;background:rgba(255,255,255,.18);color:#fff;font-size:11px;font-weight:700;line-height:20px;text-align:center}
.hd-act{margin-left:auto;display:flex;align-items:center;gap:8px}
.hd-ic,.hd .cx-hdr{position:relative;display:grid;place-items:center;width:38px;height:38px;border-radius:99px;border:0;padding:0;background:rgba(255,255,255,.12);color:#fff;cursor:pointer;text-decoration:none}
.hd-ic:hover,.hd .cx-hdr:hover{background:rgba(255,255,255,.22);color:#fff;text-decoration:none}
.hd-bd{position:absolute;top:-3px;right:-3px;min-width:16px;height:16px;box-sizing:border-box;padding:0 4px;border-radius:99px;background:#d93a3a;color:#fff;font-size:10px;font-weight:700;line-height:16px;text-align:center;border:2px solid #1f2b7c}
.cx-badge{position:absolute;top:-3px;right:-3px;min-width:16px;height:16px;padding:0 4px;border-radius:99px;background:#d93a3a;color:#fff;font-size:10px;font-weight:700;line-height:12px;text-align:center;border:2px solid #1f2b7c}
.pm{position:relative}
.pm-btn{display:flex;align-items:center;gap:6px;height:38px;padding:3px 9px 3px 3px;border-radius:99px;border:0;background:rgba(255,255,255,.12);color:#fff;cursor:pointer}
.pm-btn:hover{background:rgba(255,255,255,.22)}
.pm-btn svg{transition:transform .15s}
.pm-btn[aria-expanded="true"] svg{transform:rotate(180deg)}
.pm-av{display:grid;place-items:center;width:32px;height:32px;border-radius:50%;background:#f5b82e;color:#172066;font-size:12px;font-weight:800;flex:none}
.pm-menu{position:absolute;right:0;top:calc(100% + 10px);width:248px;background:#fff;color:#1b2559;border:1px solid #e1e6f2;border-radius:12px;box-shadow:0 12px 28px rgba(20,27,77,.25);padding:6px;z-index:150}
.pm-menu[hidden]{display:none}
.pm-who{display:flex;align-items:center;gap:10px;padding:10px;margin-bottom:4px;border-bottom:1px solid #eef1f8}
.pm-who b{display:block;font-size:13px;color:#1b2559}
.pm-who>div>span{display:block;font-size:11.5px;color:#4d5878;margin-top:1px}
.pm-menu a,.nv-foot a{display:flex;align-items:center;gap:10px;height:42px;padding:0 10px;border-radius:8px;color:#1b2559;font-size:13px;font-weight:600;text-decoration:none}
.pm-menu a:hover,.nv-foot a:hover{background:#f4f6fb;color:#1b2559;text-decoration:none}
.pm-menu a.out,.nv-foot a.out{color:#8f1d1d}

@media (max-width:1199px){
.hd-in{padding:0 16px}
.hd-menu{display:grid}
.hd-pm{display:none}
.hd-ic,.hd .cx-hdr{width:40px;height:40px}
.nv{position:fixed;top:0;bottom:0;left:0;z-index:120;width:min(320px,86vw);flex-direction:column;align-items:stretch;background:#fff;color:#1b2559;overflow-y:auto;overscroll-behavior:contain;box-shadow:12px 0 32px rgba(20,27,77,.25);transform:translateX(-100%);visibility:hidden;transition:transform .26s cubic-bezier(.2,.8,.2,1),visibility 0s linear .26s}
.nv.on{transform:none;visibility:visible;transition:transform .26s cubic-bezier(.2,.8,.2,1)}
.nv-head{display:flex;align-items:center;justify-content:space-between;flex:none;min-height:60px;box-sizing:border-box;padding:env(safe-area-inset-top,0px) 10px 0 16px;background:linear-gradient(135deg,#1a2470,#243088);color:#fff}
.nv-head .hd-menu{display:grid;margin:0}
.nv-list{flex:1;flex-direction:column;margin:0;padding:10px}
.nv-list a{height:48px;font-size:14px;color:#1b2559}
.nv-list a:hover{background:#f4f6fb;color:#1b2559}
.nv-count{margin-left:auto;background:#eef1fb;color:#1e2a78}
.nv-foot{display:block;flex:none;padding:8px 10px calc(10px + env(safe-area-inset-bottom,0px));border-top:1px solid #eef1f8}
.nv-scrim{display:block;position:fixed;inset:0;z-index:110;background:rgba(20,27,77,.48);opacity:0;visibility:hidden;transition:opacity .2s ease,visibility 0s linear .2s}
.nv-scrim.on{opacity:1;visibility:visible;transition:opacity .2s ease}
}
@media (max-width:520px){.hd-city{display:none}}

/* quick-action dock */
.dock{position:fixed;right:16px;top:50%;transform:translateY(-50%);z-index:40;display:flex;flex-direction:column;gap:8px;padding:8px;border-radius:99px;background:#fff;box-shadow:0 14px 40px rgba(20,34,79,.25);border:1px solid var(--line)}
.dock a,.dock button{position:relative;width:48px;height:48px;border-radius:50%;display:grid;place-items:center;border:0;background:var(--tint);color:var(--navy);cursor:pointer;transition:transform .2s,background .2s,color .2s;text-decoration:none}
.dock a:hover,.dock a:focus-visible,.dock button:hover,.dock button:focus-visible{background:var(--navy);color:var(--gold);transform:scale(1.12)}
.dock .ask{background:var(--gold);color:#172066;animation:pulseDock 2s infinite}
.lbl{display:none}
.tip{position:absolute;right:calc(100% + 14px);top:50%;transform:translateY(-50%) translateX(10px);opacity:0;pointer-events:none;background:var(--navy-d);color:#fff;border-radius:12px;padding:10px 14px;white-space:nowrap;text-align:left;transition:opacity .18s,transform .18s;box-shadow:0 8px 24px rgba(0,0,0,.25);z-index:50}
.tip b{display:block;font-size:14px}.tip small{display:block;font-size:12px;color:#c9d4f0;margin-top:2px;font-weight:500}
.dock *:hover>.tip,.dock *:focus-visible>.tip{opacity:1;transform:translateY(-50%)}
@keyframes pulseDock{0%{box-shadow:0 0 0 0 rgba(245,184,46,.6)}100%{box-shadow:0 0 0 16px rgba(245,184,46,0)}}

@media (max-width:1100px){
.dock{top:auto;bottom:0;left:0;right:0;transform:none;flex-direction:row;justify-content:space-around;gap:0;border-radius:20px 20px 0 0;padding:6px 6px calc(6px + env(safe-area-inset-bottom,0));border-width:1px 0 0;z-index:90}
.dock a,.dock button{flex:1;width:auto;height:56px;border-radius:12px;background:transparent;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px}
.dock .ask{background:var(--gold);animation:none;margin-left:4px}
.lbl{display:block;font-size:11px;font-weight:700}
.tip{display:none}
body{padding-bottom:76px !important}
}

/* chat panel: AI assistant + MSWDO office */
.cx-scrim{position:fixed;inset:0;background:rgba(20,27,77,.48);opacity:0;visibility:hidden;transition:opacity .2s ease,visibility 0s linear .2s;z-index:900}
.cx-scrim.on{opacity:1;visibility:visible;transition:opacity .2s ease}
.cx-panel{position:fixed;top:0;right:0;bottom:0;width:min(440px,100vw);height:100vh;height:100dvh;display:flex;flex-direction:column;background:#fff;box-shadow:-12px 0 32px rgba(20,27,77,.25);transform:translateX(100%);visibility:hidden;transition:transform .26s cubic-bezier(.2,.8,.2,1),visibility 0s linear .26s;z-index:1000}
.cx-panel.on{transform:none;visibility:visible;transition:transform .26s cubic-bezier(.2,.8,.2,1)}
.cx-head{flex:none;display:flex;align-items:center;gap:10px;min-height:56px;padding:env(safe-area-inset-top,0) 12px 0 18px;background:linear-gradient(135deg,#1a2470,#243088);color:#fff}
.cx-head h2{margin:0;font-size:16px;font-weight:800;flex:1;color:#fff}
.cx-close{display:grid;place-items:center;width:40px;height:40px;border-radius:99px;border:0;background:rgba(255,255,255,.14);color:#fff;cursor:pointer}
.cx-close:hover{background:rgba(255,255,255,.24)}
.cx-tabs{flex:none;display:flex;gap:6px;padding:10px 12px;border-bottom:1px solid var(--line)}
.cx-tab{flex:1;display:flex;justify-content:center;align-items:center;gap:8px;min-height:44px;border:1px solid #d5dcee;background:#fff;color:var(--navy);border-radius:12px;font:600 13px 'Inter',system-ui,sans-serif;cursor:pointer}
.cx-tab[aria-selected="true"]{background:var(--gold);border-color:var(--gold);color:#172066;font-weight:700}
.cx-count{min-width:20px;height:20px;padding:0 6px;border-radius:99px;background:var(--navy);color:#fff;font-size:11px;font-weight:700;line-height:20px;text-align:center}
.cx-who{flex:none;display:flex;align-items:center;gap:12px;padding:10px 14px;border-bottom:1px solid var(--line)}
.cx-av{width:40px;height:40px;border-radius:50%;display:grid;place-items:center;flex:none;background:var(--gold);color:#172066}
.cx-who b{display:block;font-size:14px;color:#1b2559}.cx-who span{display:block;font-size:12px;color:var(--muted);margin-top:1px}
.cx-ctx{flex:none;display:flex;align-items:center;gap:8px;padding:8px 14px;background:var(--tint);font-size:12.5px;color:var(--navy);font-weight:600}
.cx-msgs{flex:1;min-height:0;overflow-y:auto;overscroll-behavior:contain;padding:16px;display:flex;flex-direction:column;gap:8px;background:var(--bg);scroll-behavior:smooth}
.cx-day{align-self:center;font-size:11px;font-weight:600;color:var(--muted);background:#e8ecf7;border-radius:99px;padding:3px 10px}
.cx-msg{max-width:84%;display:flex;flex-direction:column;gap:3px}
.cx-msg.them{align-self:flex-start}.cx-msg.me{align-self:flex-end;align-items:flex-end}
.cx-bub{margin:0;padding:10px 14px;font-size:14px;line-height:1.45;white-space:pre-wrap;overflow-wrap:anywhere}
.cx-msg.them .cx-bub{background:#fff;border:1px solid var(--line);border-radius:14px 14px 14px 4px;color:#1b2559}
.cx-msg.me .cx-bub{background:linear-gradient(135deg,#1e2a78,#243088);color:#fff;border-radius:14px 14px 4px 14px}
.cx-info{font-size:10.5px;color:var(--muted)}
.cx-note{align-self:center;text-align:center;font-size:11.5px;color:var(--muted);max-width:90%}
.cx-hand{align-self:stretch;background:#fff;border:1px dashed #b9c3e3;border-radius:14px;padding:14px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:13px;font-weight:600;color:#1b2559}
.cx-hand button{flex:none;min-height:40px;padding:0 14px;border-radius:10px;border:0;background:var(--navy);color:#fff;font-weight:700;font-size:13px;cursor:pointer}
.cx-typing{align-self:flex-start;display:flex;gap:4px;background:#fff;border:1px solid var(--line);border-radius:14px 14px 14px 4px;padding:12px 14px}
.cx-typing i{width:6px;height:6px;border-radius:50%;background:#8b96b5;animation:cxBounce 1.2s infinite ease-in-out}
.cx-typing i:nth-child(2){animation-delay:.18s}.cx-typing i:nth-child(3){animation-delay:.36s}
@keyframes cxBounce{0%,80%,100%{transform:scale(0)}40%{transform:scale(1)}}
.cx-compose{flex:none;background:#fff;border-top:1px solid var(--line);padding:10px 12px calc(10px + env(safe-area-inset-bottom,0))}
.cx-quick{display:flex;gap:6px;overflow-x:auto;padding-bottom:8px;scrollbar-width:none}
.cx-quick::-webkit-scrollbar{display:none}
.cx-q{flex:none;font:600 12px 'Inter',system-ui,sans-serif;background:var(--tint);color:var(--navy);border:1px solid #d5dcee;border-radius:99px;padding:6px 12px;cursor:pointer;white-space:nowrap}
.cx-q:hover{background:#e3e9f8}
.cx-row{display:flex;align-items:flex-end;gap:8px}
.cx-btn{width:40px;height:40px;border-radius:10px;border:0;display:grid;place-items:center;cursor:pointer;flex:none}
.cx-att{background:transparent;color:var(--muted)}.cx-att:hover{background:var(--tint);color:var(--navy)}
#cxInput{flex:1;min-height:40px;max-height:120px;padding:10px 12px;border:1px solid #c9d2e5;border-radius:12px;font:500 14px/1.4 'Inter',system-ui,sans-serif;color:var(--ink);resize:none;background:#fff}
#cxInput:focus{outline:2px solid var(--gold);outline-offset:0;border-color:var(--gold)}
.cx-send{background:var(--navy);color:#fff}.cx-send:hover{background:#151c57}
.cx-send:disabled{background:#c9d2e5;cursor:not-allowed}
.cx-hint{margin:6px 0 0;font-size:11px;color:var(--muted);text-align:center}
</style>

<!-- nav: redesigned header + navigation (exact match across user portal) -->
<header class="hd" id="hd">
<div class="hd-in">
<button class="hd-menu" id="nvToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="nv"><svg class="i" aria-hidden="true"><use href="#i-menu"/></svg></button>
<a class="hd-brand" href="{{ route('user.dashboard') }}" aria-label="MSWDO {{ $municipalityLabel }}, go to dashboard"><span class="hd-logo"><svg width="18" height="18" viewBox="0 0 24 24" fill="#d93a3a" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg></span><span>MSWDO<span class="hd-city"> &middot; {{ $municipalityLabel }}</span></span></a>
<nav class="nv" id="nv" aria-label="Main">
<div class="nv-head"><span class="hd-brand"><span class="hd-logo"><svg width="18" height="18" viewBox="0 0 24 24" fill="#d93a3a" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg></span><span>MSWDO<span class="hd-city"> &middot; {{ $municipalityLabel }}</span></span></span><button class="hd-menu" id="nvClose" type="button" aria-label="Close menu"><svg class="i" aria-hidden="true"><use href="#i-close"/></svg></button></div>
<ul class="nv-list">
<li><a href="{{ route('user.dashboard') }}" {!! $currentActive === 'dashboard' ? 'aria-current="page"' : '' !!}><svg class="i i-sm" aria-hidden="true"><use href="#i-grid"/></svg><span>Dashboard</span></a></li>
<li><a href="{{ route('user.programs') }}" {!! $currentActive === 'programs' ? 'aria-current="page"' : '' !!}><svg class="i i-sm" aria-hidden="true"><use href="#i-layers"/></svg><span>Programs</span></a></li>
<li><a href="{{ route('user.my-requirements') }}" {!! $currentActive === 'my-requirements' ? 'aria-current="page"' : '' !!}><svg class="i i-sm" aria-hidden="true"><use href="#i-upload"/></svg><span>My Requirements</span></a></li>
<li><a href="{{ route('user.announcements') }}" {!! $currentActive === 'announcements' ? 'aria-current="page"' : '' !!}><svg class="i i-sm" aria-hidden="true"><use href="#i-news"/></svg><span>Announcements</span></a></li>

</ul>
<div class="nv-foot"><div class="pm-who"><span class="pm-av">{{ $initials }}</span><div><b>{{ $fullName }}</b><span>Member &middot; {{ $municipalityLabel }}</span></div></div><a href="{{ route('user.profile') }}"><svg class="i i-sm" aria-hidden="true"><use href="#i-user"/></svg>My Profile</a><a class="out" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();"><svg class="i i-sm" aria-hidden="true"><use href="#i-out"/></svg>Log out</a></div>
</nav>
<div class="hd-act">
<button class="cx-hdr" type="button" data-open-chat data-chat-tab="staff" aria-label="Open messages" title="Messages" aria-haspopup="dialog"><svg class="i i-sm" aria-hidden="true"><use href="#i-chat"/></svg><span class="cx-badge" id="hdBadge" style="{{ ($unreadAnnouncements ?? 0) > 0 ? '' : 'display:none' }}">{{ $unreadAnnouncements ?? 1 }}</span></button>
<button class="hd-ic" type="button" data-bs-toggle="modal" data-bs-target="#announcementsModal" aria-label="Notifications" title="Notifications"><svg class="i i-sm" aria-hidden="true"><use href="#i-bell"/></svg>@if(($unreadNotifications ?? 0) > 0)<span class="hd-bd">{{ $unreadNotifications }}</span>@endif</button>
<div class="pm hd-pm" id="pm">
<button class="pm-btn" id="pmBtn" type="button" aria-label="Account menu" aria-expanded="false" aria-controls="pmMenu"><span class="pm-av">{{ $initials }}</span><svg class="i i-sm" aria-hidden="true"><use href="#i-down"/></svg></button>
<div class="pm-menu" id="pmMenu" hidden><div class="pm-who"><span class="pm-av">{{ $initials }}</span><div><b>{{ $fullName }}</b><span>Member &middot; {{ $municipalityLabel }}</span></div></div><a href="{{ route('user.profile') }}"><svg class="i i-sm" aria-hidden="true"><use href="#i-user"/></svg>My Profile</a><a class="out" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();"><svg class="i i-sm" aria-hidden="true"><use href="#i-out"/></svg>Log out</a></div>
</div>
</div>
<form id="logout-form-nav" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
<div class="nv-scrim" id="nvScrim"></div>
</div>
</header>

<div class="hd-extras">
<!-- chat: two-channel slide-over drawer (AI assistant + MSWDO staff) -->
<div class="cx-scrim" id="cxScrim"></div>
<section class="cx-panel" id="cxPanel" role="dialog" aria-modal="true" aria-labelledby="cxTitle" aria-hidden="true">
<header class="cx-head"><h2 id="cxTitle">Help &amp; Messages</h2><button class="cx-close" id="cxClose" type="button" aria-label="Close messages"><svg class="i" aria-hidden="true"><use href="#i-close"/></svg></button></header>
<div class="cx-tabs" role="tablist" aria-label="Choose who to talk to">
<button class="cx-tab" id="tabAi" type="button" role="tab" aria-selected="true" aria-controls="viewAi"><svg class="i i-sm" aria-hidden="true"><use href="#i-spark"/></svg>AI Assistant</button>
<button class="cx-tab" id="tabStaff" type="button" role="tab" aria-selected="false" aria-controls="viewStaff"><svg class="i i-sm" aria-hidden="true"><use href="#i-chat"/></svg>MSWDO Office <span class="cx-count" id="staffCount">1</span></button>
</div>
<div class="cx-who"><span class="cx-av" id="cxAv"></span><div><b id="cxName"></b><span id="cxSub"></span></div></div>
<div class="cx-msgs" id="viewAi" role="tabpanel" aria-labelledby="tabAi" aria-live="polite"></div>
<div class="cx-msgs" id="viewStaff" role="tabpanel" aria-labelledby="tabStaff" aria-live="polite" hidden></div>
<div class="cx-compose">
<div class="cx-quick" id="cxQuick" aria-label="Suggested questions"></div>
<div class="cx-row">
<button class="cx-btn cx-att" type="button" aria-label="Attach a photo or document"><svg class="i" aria-hidden="true"><use href="#i-clip"/></svg></button>
<label class="sr-only d-none" for="cxInput">Write a message</label>
<textarea id="cxInput" rows="1" maxlength="1000"></textarea>
<button class="cx-btn cx-send" id="cxSend" type="button" aria-label="Send message" disabled><svg class="i" aria-hidden="true"><use href="#i-send"/></svg></button>
</div>
<p class="cx-hint" id="cxHint"></p>
</div>
</section>

<script>
/* nav & account menu scripts */
(function () {
  if (window._userNavInitialized) return;
  window._userNavInitialized = true;

  var $ = function (i) { return document.getElementById(i); };
  var hd = $('hd'), nv = $('nv'), tg = $('nvToggle'), sc = $('nvScrim'), cl = $('nvClose'), pb = $('pmBtn'), pm = $('pmMenu');
  var mq = window.matchMedia('(max-width:1199px)'), back = null;
  function navOpen() { return nv && nv.classList.contains('on'); }
  function openNav() {
    if(!nv || !sc || !tg) return;
    back = document.activeElement;
    nv.classList.add('on'); sc.classList.add('on'); tg.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    var a = nv.querySelector('[aria-current]') || nv.querySelector('a'); if (a) a.focus();
  }
  function closeNav(restore) {
    if(!nv || !sc || !tg) return;
    nv.classList.remove('on'); sc.classList.remove('on'); tg.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    if (restore && back && back.focus) back.focus();
  }
  function closeMenu(restore) { if(pm && pb) { pm.hidden = true; pb.setAttribute('aria-expanded', 'false'); if (restore) pb.focus(); } }
  if(tg) tg.addEventListener('click', function () { navOpen() ? closeNav(true) : openNav(); });
  if(cl) cl.addEventListener('click', function () { closeNav(true); });
  if(sc) sc.addEventListener('click', function () { closeNav(true); });
  if(nv) nv.addEventListener('click', function (e) { if (mq.matches && e.target.closest('a')) closeNav(false); });
  if(pb && pm) {
    pb.addEventListener('click', function () { var o = pm.hidden; pm.hidden = !o; pb.setAttribute('aria-expanded', String(o)); });
    document.addEventListener('click', function (e) { if (!pm.hidden && !e.target.closest('#pm')) closeMenu(false); });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { if (navOpen()) closeNav(true); else if (pm && !pm.hidden) closeMenu(true); return; }
    if (e.key === 'Tab' && navOpen()) {
      var f = nv.querySelectorAll('a[href],button'), first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
  function onMq() { if (!mq.matches && navOpen()) closeNav(false); }
  if (mq.addEventListener) mq.addEventListener('change', onMq); else mq.addListener(onMq);
  function onScroll() { if(hd) hd.classList.toggle('stuck', window.scrollY > 4); }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
})();

/* chat drawer scripts */
(function () {
  if (window._userChatInitialized) return;
  window._userChatInitialized = true;

  var $ = function (i) { return document.getElementById(i); };
  var panel = $('cxPanel'), scrim = $('cxScrim'), input = $('cxInput'), send = $('cxSend'), quick = $('cxQuick');
  if(!panel) return;
  var views = { ai: $('viewAi'), staff: $('viewStaff') }, tabs = { ai: $('tabAi'), staff: $('tabStaff') };
  var userName = '{{ $firstName }}';
  var municipality = '{{ $municipalityLabel }}';
  var cfg = {
    ai: { name: 'MSWDO AI Assistant', sub: 'Quick answers about programs and requirements', ph: 'Ask a question...', hint: 'AI answers can make mistakes. For official decisions, message MSWDO staff.',
      chips: ['How do I apply?', 'What documents do I need?', 'Check my application status', 'Which program fits me?'] },
    staff: { name: 'MSWDO Office (' + municipality + ')', sub: 'Staff reply during office hours: Mon-Fri 8am-5pm', ph: 'Write a message to MSWDO staff...', hint: 'Enter to send, Shift+Enter for a new line.',
      chips: ['I need help with my application', 'Question about my documents', 'What are your office hours?'] }
  };
  var tab = 'ai', unread = 1, opener = null;
  var aiHistory = [];
  var spark = '<svg class="i" aria-hidden="true"><use href="#i-spark"/></svg>', chat = '<svg class="i" aria-hidden="true"><use href="#i-chat"/></svg>';

  function badge() {
    var h = $('hdBadge'), c = $('staffCount'), n = $('hcNew');
    [h, c, n].forEach(function (el) { if(el) el.hidden = unread < 1; });
    if(h) h.textContent = unread;
    if(c) c.textContent = unread;
    if(n) n.textContent = unread + ' new';
  }
  function stamp() { return new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' }); }
  function add(v, who, text) {
    if(!views[v]) return;
    var m = document.createElement('div'), p = document.createElement('p'), t = document.createElement('span');
    m.className = 'cx-msg ' + who; p.className = 'cx-bub'; p.textContent = text; t.className = 'cx-info'; t.textContent = stamp();
    m.append(p, t); views[v].append(m); views[v].scrollTop = views[v].scrollHeight; return m;
  }
  function reply(v, ms, text) {
    if(!views[v]) return;
    var t = document.createElement('div'); t.className = 'cx-typing'; t.setAttribute('aria-label', 'Typing');
    t.append(document.createElement('i'), document.createElement('i'), document.createElement('i'));
    views[v].append(t); views[v].scrollTop = views[v].scrollHeight;
    setTimeout(function () { t.remove(); add(v, 'them', text); }, ms);
  }
  function seed() {
    add('ai', 'them', 'Hi ' + userName + '! I am your MSWDO AI Assistant for ' + municipality + '. Ask me about social welfare programs, requirements, or how to apply.');
    var n = document.createElement('p'); n.className = 'cx-note'; n.textContent = 'AI answers can be wrong. For official decisions, talk to MSWDO staff.'; views.ai.append(n);
    var h = document.createElement('div'); h.className = 'cx-hand';
    h.innerHTML = '<span>Prefer to talk to a person?</span>';
    var b = document.createElement('button'); b.type = 'button'; b.textContent = 'Message staff'; b.addEventListener('click', function () { setTab('staff'); }); h.append(b); views.ai.append(h);
    var d = document.createElement('span'); d.className = 'cx-day'; d.textContent = 'Today'; views.staff.append(d);
    add('staff', 'them', 'Hello ' + userName + '! Welcome to MSWDO ' + municipality + '. How can our office assist you today?');

    // Direct button in staff tab
    var directBtn = document.createElement('div');
    directBtn.style.padding = '10px 0';
    directBtn.innerHTML = '<button type="button" style="width:100%;min-height:44px;font-size:14px;background:#1a2470;color:#fff;border-radius:12px;border:none;font-weight:700;cursor:pointer;">Open Full Staff Messenger</button>';
    directBtn.querySelector('button').addEventListener('click', function() {
      close();
      var m = document.getElementById('chatModal');
      if(m && window.bootstrap) {
        new bootstrap.Modal(m).show();
      }
    });
    views.staff.append(directBtn);
  }
  function setTab(n) {
    tab = n;
    Object.keys(tabs).forEach(function (k) { if(tabs[k]) { tabs[k].setAttribute('aria-selected', String(k === n)); views[k].hidden = k !== n; } });
    var c = cfg[n]; $('cxName').textContent = c.name; $('cxSub').textContent = c.sub; input.placeholder = c.ph; $('cxHint').textContent = c.hint;
    $('cxAv').innerHTML = n === 'ai' ? spark : chat;
    quick.replaceChildren();
    c.chips.forEach(function (t) { var b = document.createElement('button'); b.type = 'button'; b.className = 'cx-q'; b.textContent = t; b.addEventListener('click', function () { submit(t); }); quick.append(b); });
    if (n === 'staff' && unread) { unread = 0; badge(); }
    views[n].scrollTop = views[n].scrollHeight;
  }
  function submit(text) {
    text = text.trim(); if (!text) return;
    var v = tab; add(v, 'me', text); input.value = ''; grow();
    if (v === 'ai') {
      var typingEl = document.createElement('div'); typingEl.className = 'cx-typing'; typingEl.setAttribute('aria-label', 'Typing');
      typingEl.append(document.createElement('i'), document.createElement('i'), document.createElement('i'));
      views.ai.append(typingEl); views.ai.scrollTop = views.ai.scrollHeight;

      aiHistory.push({ role: 'user', text: text });
      var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

      fetch('/chatbot/message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ message: text, history: aiHistory.slice(-6) })
      }).then(function(r) { return r.json(); }).then(function(d) {
        typingEl.remove();
        if(d && d.reply) {
          aiHistory.push({ role: 'model', text: d.reply });
          add('ai', 'them', d.reply);
        } else {
          add('ai', 'them', 'I am here to help you with MSWDO programs (PWD, Solo Parent, AICS) and requirements in ' + municipality + '. You can also check your applications under My Requirements.');
        }
      }).catch(function() {
        typingEl.remove();
        add('ai', 'them', 'You can apply for PWD Assistance, Solo Parent Support, and Assistance in Crisis through this portal. For personalized status, view "My Requirements" or message MSWDO staff.');
      });
    } else {
      reply('staff', 1000, 'Thank you, ' + userName + '. We received your message. An MSWDO staff member assigned to ' + municipality + ' will respond during office hours.');
    }
  }
  function grow() { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 120) + 'px'; send.disabled = !input.value.trim(); }
  function open(n) {
    opener = document.activeElement; setTab(n || tab);
    panel.classList.add('on'); scrim.classList.add('on'); panel.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden';
    (window.matchMedia('(hover:hover)').matches ? input : $('cxClose')).focus();
  }
  function close() {
    panel.classList.remove('on'); scrim.classList.remove('on'); panel.setAttribute('aria-hidden', 'true'); document.body.style.overflow = '';
    if (opener && opener.focus) opener.focus();
  }
  document.addEventListener('click', function (e) { var b = e.target.closest('[data-open-chat]'); if (b) open(b.getAttribute('data-chat-tab')); });
  Object.keys(tabs).forEach(function (k) { if(tabs[k]) tabs[k].addEventListener('click', function () { setTab(k); }); });
  var cxCloseBtn = $('cxClose'); if(cxCloseBtn) cxCloseBtn.addEventListener('click', close);
  if(scrim) scrim.addEventListener('click', close);
  if(send) send.addEventListener('click', function () { submit(input.value); input.focus(); });
  if(input) {
    input.addEventListener('input', grow);
    input.addEventListener('keydown', function (e) { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submit(input.value); } });
  }
  document.addEventListener('keydown', function (e) {
    if (!panel.classList.contains('on')) return;
    if (e.key === 'Escape') { close(); return; }
    if (e.key === 'Tab') {
      var f = Array.prototype.filter.call(panel.querySelectorAll('button,textarea'), function (x) { return !x.disabled && x.offsetParent; });
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    }
  });
  seed(); setTab('ai'); badge();
})();
</script>

{{-- Global Confirm Modal (replaces all browser confirm() dialogs) --}}
@include('components.confirm-modal')
</div>