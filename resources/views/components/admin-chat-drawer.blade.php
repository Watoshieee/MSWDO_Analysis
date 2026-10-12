    @php
        $applicantsDataMap = [];
        if (isset($recentApplications) && $recentApplications->count() > 0) {
            foreach ($recentApplications as $rapp) {
                if ($rapp->user_id) {
                    $sProg = match ($rapp->program_type) {
                        'PWD_Assistance' => 'PWD',
                        'Solo_Parent' => 'Solo Parent',
                        'AICS_Medical' => 'AICS Medical',
                        'AICS_Burial' => 'AICS Burial',
                        default => ucwords(str_replace('_', ' ', $rapp->program_type))
                    };
                    $applicantsDataMap[(string)$rapp->user_id] = [
                        'name' => $rapp->full_name,
                        'program' => $sProg,
                        'barangay' => $rapp->barangay ?? '',
                        'status' => strtolower($rapp->status ?? 'pending'),
                        'appUrl' => route('admin.requirements') . '?search=' . urlencode($rapp->full_name)
                    ];
                }
            }
        }
    @endphp

<style>
        :root {
            --cx-navy: #1e2a78;
            --cx-navy-d: #141b4d;
            --cx-gold: var(--accent-gold, #f5b82e);
            --cx-gold-h: var(--accent-gold-hover, #ffc94d);
            --cx-tint: #f0f3fa;
            --cx-bg: #f4f6fb;
            --cx-ink: #1b2559;
            --cx-muted: #5b6687;
            --cx-line: #e1e6f2;
            --cx-soft: #eef1f8;
        }

        .cx-hdr {
            position: relative;
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 99px;
            border: 0;
            padding: 0;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            cursor: pointer;
        }
        .cx-hdr:hover { background: rgba(255, 255, 255, .22); }
        .cx-hdr:focus-visible, .cx-rowbtn:focus-visible { outline: 2px solid var(--cx-gold); outline-offset: 2px; }

        .cx-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 16px;
            height: 16px;
            box-sizing: border-box;
            padding: 0 4px;
            border-radius: 99px;
            background: var(--cx-gold);
            color: #172066;
            font-size: 10px;
            font-weight: 800;
            line-height: 16px;
            text-align: center;
            border: 2px solid #1f2b7c;
        }

        .cx-dot {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--cx-gold);
            border: 2px solid #fff;
        }
        .qa button:hover .cx-dot, .qa a:hover .cx-dot { border-color: #1e2a78; }

        .cx-rowbtn {
            position: relative;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #d5dcee;
            background: #fff;
            color: #1e2a78;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
            transition: all 0.15s ease;
        }
        .cx-rowbtn:hover { background: #1e2a78; border-color: #1e2a78; color: #fff; }
        .cx-rowbtn.has-unread::after {
            content: "";
            position: absolute;
            top: -3px;
            right: -3px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--cx-gold);
            border: 2px solid #fff;
        }

        .sr {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
        }

        /* Chat drawer & scrim */
        .cx-scrim {
            position: fixed;
            inset: 0;
            background: rgba(14, 20, 56, .45);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            z-index: 10495;
            opacity: 0;
            pointer-events: none;
            transition: opacity .22s ease;
        }
        .cx-scrim.on { opacity: 1 !important; pointer-events: auto !important; }

        .cx-panel {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(680px, 100vw);
            background: #fff;
            z-index: 10500;
            box-shadow: -12px 0 36px rgba(16, 23, 71, .28);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform .26s cubic-bezier(.2, .8, .2, 1);
        }
        .cx-panel.on { transform: translateX(0) !important; }

        .cx-head {
            flex: none;
            height: 52px;
            background: linear-gradient(135deg, #1a2470, #243088);
            color: #fff;
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 10px;
        }
        .cx-head h2 { margin: 0; font-size: 15px; font-weight: 700; color: #fff; letter-spacing: -0.01em; }
        .cx-head .pill {
            background: rgba(255, 255, 255, .16);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 99px;
            color: #fff;
        }
        .cx-close {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 0;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
            transition: background 0.15s ease;
        }
        .cx-close:hover { background: rgba(255, 255, 255, .24); }
        .cx-close:focus-visible, .cx-back:focus-visible, .cx-send:focus-visible, .cx-q:focus-visible, .cx-tab:focus-visible, .cx-item:focus-visible {
            outline: 2px solid var(--cx-gold);
            outline-offset: 2px;
        }

        .cx-body {
            flex: 1;
            min-height: 0;
            display: grid;
            grid-template-columns: 290px minmax(0, 1fr);
            grid-template-rows: minmax(0, 1fr);
        }

        /* List Pane */
        .cx-list {
            display: flex;
            flex-direction: column;
            min-height: 0;
            border-right: 1px solid var(--cx-line);
            background: #fff;
        }
        .cx-tools {
            flex: none;
            padding: 12px 12px 8px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-bottom: 1px solid var(--cx-soft);
        }
        .cx-search { position: relative; display: block; margin: 0; }
        .cx-search svg {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cx-muted);
            pointer-events: none;
        }
        .cx-search input {
            width: 100%;
            box-sizing: border-box;
            height: 38px;
            border: 1px solid #d5dcee;
            border-radius: 10px;
            background: var(--cx-bg);
            padding: 0 12px 0 34px;
            font: 500 13px 'Inter', system-ui, sans-serif;
            color: var(--cx-ink);
        }
        .cx-search input::placeholder { color: #7b86a8; }
        .cx-search input:focus {
            outline: 2px solid var(--cx-gold);
            outline-offset: 0;
            background: #fff;
            border-color: transparent;
        }

        .cx-tabs { display: flex; gap: 6px; }
        .cx-tab {
            border: 1px solid #d5dcee;
            background: #fff;
            color: var(--cx-navy);
            font: 600 12px 'Inter', system-ui, sans-serif;
            padding: 5px 12px;
            border-radius: 99px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }
        .cx-tab:hover { background: var(--cx-tint); }
        .cx-tab[aria-selected="true"] {
            background: var(--cx-gold);
            border-color: var(--cx-gold);
            color: #172066;
            font-weight: 700;
        }
        .cx-tab small { font-size: 11px; font-weight: 700; opacity: .85; }

        .cx-items {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .cx-item {
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr) auto;
            gap: 10px;
            align-items: start;
            width: 100%;
            box-sizing: border-box;
            text-align: left;
            border: 0;
            border-bottom: 1px solid var(--cx-soft);
            border-left: 3px solid transparent;
            background: #fff;
            padding: 11px 12px 11px 10px;
            cursor: pointer;
            font-family: inherit;
            color: var(--cx-ink);
            transition: background 0.15s ease;
        }
        .cx-item:hover { background: var(--cx-bg); }
        .cx-item[aria-current="true"] {
            background: var(--cx-tint);
            border-left-color: var(--cx-gold);
        }
        .cx-av {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e8ecf9;
            color: var(--cx-navy);
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 800;
            flex: none;
        }
        .cx-who { min-width: 0; }
        .cx-name {
            display: block;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--cx-ink);
        }
        .cx-sub {
            display: block;
            font-size: 11px;
            color: var(--cx-muted);
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cx-prev {
            display: block;
            font-size: 12px;
            color: var(--cx-muted);
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cx-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 5px;
            font-size: 10.5px;
            color: var(--cx-muted);
            white-space: nowrap;
        }
        .cx-count {
            min-width: 18px;
            height: 18px;
            box-sizing: border-box;
            padding: 0 5px;
            border-radius: 99px;
            background: var(--cx-navy);
            color: #fff;
            font-size: 10.5px;
            font-weight: 800;
            line-height: 18px;
            text-align: center;
        }
        .cx-item.unread .cx-name { font-weight: 800; color: #101828; }
        .cx-item.unread .cx-prev { color: var(--cx-ink); font-weight: 600; }
        .cx-item.unread .cx-meta time { color: var(--cx-navy); font-weight: 700; }

        .cx-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 6px;
            padding: 36px 20px;
            color: var(--cx-muted);
            font-size: 12.5px;
        }
        .cx-empty b { color: var(--cx-ink); font-size: 13.5px; }

        /* Thread Pane */
        .cx-thread {
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 0;
            background: var(--cx-bg);
        }
        .cx-thead {
            flex: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #fff;
            border-bottom: 1px solid var(--cx-line);
        }
        .cx-back {
            display: none;
            place-items: center;
            width: 36px;
            height: 36px;
            margin-left: -4px;
            border-radius: 8px;
            border: 0;
            background: transparent;
            color: var(--cx-navy);
            cursor: pointer;
            flex: none;
        }
        .cx-back:hover { background: var(--cx-tint); }
        .cx-tinfo { min-width: 0; flex: 1; }
        .cx-tinfo b {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: var(--cx-ink);
        }
        .cx-tinfo span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--cx-muted);
            margin-top: 2px;
            flex-wrap: wrap;
        }
        .cx-open {
            flex: none;
            background: var(--cx-gold);
            color: #172066 !important;
            font-size: 11.5px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }
        .cx-open:hover { background: var(--cx-gold-h); text-decoration: none; }

        .cx-msgs {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            scroll-behavior: smooth;
        }
        .cx-day {
            align-self: center;
            margin: 8px 0 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--cx-muted);
            background: #e8ecf7;
            border-radius: 99px;
            padding: 2px 10px;
        }
        .cx-msg {
            max-width: min(80%, 480px);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .cx-msg.user { align-self: flex-start; }
        .cx-msg.admin { align-self: flex-end; align-items: flex-end; }
        .cx-bub {
            margin: 0;
            padding: 8px 12px;
            font-size: 13px;
            line-height: 1.45;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }
        .cx-msg.user .cx-bub {
            background: #fff;
            border: 1px solid var(--cx-line);
            color: var(--cx-ink);
            border-radius: 14px 14px 14px 4px;
            box-shadow: 0 1px 2px rgba(23, 32, 102, .05);
        }
        .cx-msg.admin .cx-bub {
            background: linear-gradient(135deg, #1e2a78, #243088);
            color: #fff;
            border-radius: 14px 14px 4px 14px;
        }
        .cx-info {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: var(--cx-muted);
        }
        .cx-info svg { color: #9aa4c8; }
        .cx-info svg.read { color: #2b8a57; }

        .cx-compose {
            flex: none;
            background: #fff;
            border-top: 1px solid var(--cx-line);
            padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0));
        }
        .cx-quick {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 8px;
            margin: 0 -2px;
            scrollbar-width: thin;
        }
        .cx-quick::-webkit-scrollbar { height: 0; }
        .cx-q {
            flex: none;
            border: 1px solid #d5dcee;
            background: var(--cx-tint);
            color: var(--cx-navy);
            font: 600 11px 'Inter', system-ui, sans-serif;
            padding: 4px 10px;
            border-radius: 99px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .cx-q:hover {
            background: var(--cx-navy);
            border-color: var(--cx-navy);
            color: #fff;
        }
        .cx-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        .cx-row textarea {
            flex: 1;
            min-width: 0;
            box-sizing: border-box;
            resize: none;
            max-height: 110px;
            min-height: 38px;
            border: 1px solid #d5dcee;
            border-radius: 10px;
            background: var(--cx-bg);
            padding: 8px 11px;
            font: 500 13px/1.4 'Inter', system-ui, sans-serif;
            color: var(--cx-ink);
        }
        .cx-row textarea::placeholder { color: #7b86a8; }
        .cx-row textarea:focus {
            outline: 2px solid var(--cx-gold);
            outline-offset: 0;
            background: #fff;
            border-color: transparent;
        }
        .cx-send {
            flex: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 0;
            background: var(--cx-gold);
            color: #172066;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cx-send:hover:not(:disabled) { background: var(--cx-gold-h); }
        .cx-send:disabled {
            background: #e8ecf7;
            color: #9aa4c8;
            cursor: not-allowed;
        }
        .cx-hint {
            margin: 5px 2px 0;
            font-size: 10.5px;
            color: #7b86a8;
        }
        .cx-none {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 900px) {
            .cx-panel { width: 100vw; }
            .cx-body { grid-template-columns: 1fr; }
            .cx-list, .cx-thread { grid-column: 1; grid-row: 1; }
            .cx-panel[data-view="list"] .cx-thread { display: none !important; }
            .cx-panel[data-view="thread"] .cx-list { display: none !important; }
            .cx-list { border-right: 0; }
            .cx-back { display: grid; }
            .cx-none { display: none; }

        .cx-thread .st { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 99px; display: inline-flex; align-items: center; }
        .cx-thread .st.pe { background: #fef3c7; color: #92400e; }
        .cx-thread .st.ap { background: #dcfce7; color: #166534; }
        .cx-thread .st.re { background: #fee2e2; color: #991b1b; }

        }
    </style>
    {{-- =====================================================================
         CHAT DRAWER & SCRIM (Slide-over messaging interface)
         ===================================================================== --}}
    <div class="cx-scrim" id="cxScrim" onclick="closeChatDrawer()"></div>
    <section class="cx-panel" id="cxPanel" role="dialog" aria-modal="true" aria-labelledby="cxTitle" data-view="list" aria-hidden="true">
        <header class="cx-head">
            <h2 id="cxTitle">Messages</h2>
            <span class="pill" id="cxHeadPill" style="display:none;">0 unread</span>
            <button class="cx-close" type="button" id="cxClose" onclick="closeChatDrawer()" aria-label="Close messages">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </header>

        <div class="cx-body">
            <!-- Conversation List -->
            <div class="cx-list">
                <div class="cx-tools">
                    <label class="cx-search">
                        <span class="sr">Search applicants</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.5" y2="16.5"></line></svg>
                        <input type="text" id="cxSearch" placeholder="Search applicant or barangay" autocomplete="off" oninput="onChatSearchInput()">
                    </label>
                    <div class="cx-tabs" role="tablist">
                        <button class="cx-tab" id="cxTabAll" role="tab" aria-selected="true" type="button" onclick="setChatFilter('all')">All</button>
                        <button class="cx-tab" id="cxTabUnread" role="tab" aria-selected="false" type="button" onclick="setChatFilter('unread')">Unread <small id="cxTabCount"></small></button>
                    </div>
                </div>
                <ul class="cx-items" id="cxItems" role="list">
                    <!-- Populated dynamically -->
                </ul>
            </div>

            <!-- Thread Pane -->
            <div class="cx-thread">
                <div class="cx-none" id="cxNone">
                    <div class="cx-empty">
                        <div class="ic" style="margin:0 auto 8px;width:44px;height:44px;border-radius:12px;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        </div>
                        <b>No applicant selected</b>
                        Select an applicant from the list or click Message in recent applications.
                    </div>
                </div>

                <div id="cxChat" style="display:none;flex-direction:column;flex:1;min-height:0;min-width:0;">
                    <div class="cx-thead">
                        <button class="cx-back" type="button" onclick="showChatList()" aria-label="Back to applicant list">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <div class="cx-av" id="cxTAv"></div>
                        <div class="cx-tinfo">
                            <b id="cxTName">Applicant Name</b>
                            <span><span id="cxTSub"></span> &bull; <span class="st" id="cxTStatus"></span></span>
                        </div>
                        <a class="cx-open" id="cxOpenApp" href="{{ route('admin.requirements') }}" title="Open applicant record" aria-label="Open application">
                            <span>Open application</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                        </a>
                    </div>

                    <div class="cx-msgs" id="cxMsgs" role="log" aria-live="polite" aria-label="Conversation"></div>

                    <div class="cx-compose">
                        <div class="cx-quick" id="cxQuick" aria-label="Quick replies"></div>
                        <div class="cx-row">
                            <label class="sr" for="cxInput">Write a message</label>
                            <textarea id="cxInput" rows="1" placeholder="Write a message..." maxlength="1000"></textarea>
                            <button class="cx-send" type="button" id="cxSend" onclick="sendChatMessage()" aria-label="Send message" disabled>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </div>
                        <p class="cx-hint">Enter to send, Shift+Enter for a new line.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
/* =====================================================================
   ADMIN-TO-USER LIVE CHAT CONTROLLER
   Unified slide-over drawer integration across all admin pages.
   ===================================================================== */
(function() {
    'use strict';

    if (window.__mswdoAdminChatDrawerLoaded) return;
    window.__mswdoAdminChatDrawerLoaded = true;

    const CURRENT_ADMIN_ID = {{ Auth::id() ?? 0 }};
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const RECENT_APPLICANTS_INFO = {!! json_encode($applicantsDataMap) !!};
    const CHAT_ENDPOINTS = {
        users: "{{ route('admin.chat.users') }}",
        messagesPrefix: "{{ url('/admin/chat/messages') }}",
        send: "{{ route('admin.chat.send') }}",
        unread: "{{ route('admin.chat.unread') }}"
    };

    const QUICK_REPLIES = [
        { label: 'Received', text: 'Thank you. We received your requirements and will review them.' },
        { label: 'Re-upload document', text: 'Please re-upload a clearer photo of your document.' },
        { label: 'Approved', text: 'Your application is approved. Please visit the MSWDO office for the next steps.' },
        { label: 'Need more info', text: 'We need more information. Please reply here so we can continue.' }
    ];

    const APP_STATUS_MAP = {
        pending: ['pe', 'Pending'],
        approved: ['ap', 'Approved'],
        rejected: ['re', 'Rejected']
    };

    let chatConversations = [];
    let activeChatUserId = null;
    let chatFilter = 'all';
    let chatQuery = '';
    let isChatOpen = false;
    let lastChatFocusEl = null;
    let pollIntervalId = null;
    let activeThreadPollId = null;

    function $(id) { return document.getElementById(id); }
    function h(tag, cls, text) {
        const el = document.createElement(tag);
        if (cls) el.className = cls;
        if (text != null) el.textContent = text;
        return el;
    }

    function initials(name) {
        if (!name) return 'U';
        const parts = name.replace(/\./g, ' ').trim().split(/\s+/);
        return ((parts[0] || '')[0] || '').toUpperCase() + ((parts[parts.length - 1] || '')[0] || '').toUpperCase();
    }

    function sameDay(a, b) {
        return new Date(a).toDateString() === new Date(b).toDateString();
    }

    function fmtTime(t) {
        return new Date(t).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
    }

    function fmtDay(t) {
        const DAY_MS = 86400000;
        const now = Date.now();
        if (sameDay(t, now)) return 'Today';
        if (sameDay(t, now - DAY_MS)) return 'Yesterday';
        return new Date(t).toLocaleDateString([], { month: 'short', day: 'numeric' });
    }

    function fmtListTime(t) {
        if (!t) return '';
        return sameDay(t, Date.now()) ? fmtTime(t) : fmtDay(t);
    }

    function checkIcon(read) {
        const ns = 'http://www.w3.org/2000/svg';
        const s = document.createElementNS(ns, 'svg');
        s.setAttribute('width', '15');
        s.setAttribute('height', '12');
        s.setAttribute('viewBox', '0 0 30 24');
        s.setAttribute('fill', 'none');
        s.setAttribute('stroke', 'currentColor');
        s.setAttribute('stroke-width', '3');
        s.setAttribute('stroke-linecap', 'round');
        s.setAttribute('stroke-linejoin', 'round');
        s.setAttribute('aria-hidden', 'true');
        if (read) s.setAttribute('class', 'read');
        const a = document.createElementNS(ns, 'polyline'); a.setAttribute('points', '20 6 9 17 4 12'); s.appendChild(a);
        const b = document.createElementNS(ns, 'polyline'); b.setAttribute('points', '26 6 15 17 12 14'); s.appendChild(b);
        return s;
    }

    function totalUnreadChatCount() {
        return chatConversations.reduce((acc, c) => acc + (c.unread_count || 0), 0);
    }

    function getConvById(userId) {
        return chatConversations.find(c => String(c.id) === String(userId));
    }

    function renderChatBadges() {
        const total = totalUnreadChatCount();
        const hb = $('cxHdrBadge');
        if (hb) {
            hb.textContent = total > 9 ? '9+' : total;
            hb.style.display = total > 0 ? 'flex' : 'none';
        }

        const qaDot = $('cxQaDot');
        if (qaDot) {
            qaDot.style.display = total > 0 ? 'block' : 'none';
        }

        const gb = $('globalUnreadBadge');
        if (gb) {
            gb.textContent = total;
            gb.style.display = total > 0 ? 'flex' : 'none';
        }

        const headPill = $('cxHeadPill');
        if (headPill) {
            headPill.textContent = total + ' unread';
            headPill.style.display = total > 0 ? 'inline-block' : 'none';
        }

        const tabCount = $('cxTabCount');
        if (tabCount) {
            tabCount.textContent = total > 0 ? '(' + total + ')' : '';
        }

        document.querySelectorAll('.cx-rowbtn[data-user-id]').forEach(btn => {
            const uid = btn.getAttribute('data-user-id');
            const c = getConvById(uid);
            btn.classList.toggle('has-unread', !!(c && c.unread_count > 0));
        });
    }

    function renderChatQuickReplies() {
        const quickEl = $('cxQuick');
        if (!quickEl) return;
        quickEl.innerHTML = '';
        QUICK_REPLIES.forEach(q => {
            const btn = h('button', 'cx-q', q.label);
            btn.type = 'button';
            btn.title = q.text;
            btn.addEventListener('click', () => {
                const input = $('cxInput');
                if (input) {
                    input.value = q.text;
                    autoGrowChatInput();
                    input.focus();
                    updateSendBtnState();
                }
            });
            quickEl.appendChild(btn);
        });
    }

    function autoGrowChatInput() {
        const input = $('cxInput');
        if (!input) return;
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 110) + 'px';
    }

    function updateSendBtnState() {
        const sendBtn = $('cxSend');
        const input = $('cxInput');
        if (sendBtn && input) {
            sendBtn.disabled = !input.value.trim();
        }
    }

    function visibleChatConversations() {
        const q = chatQuery.trim().toLowerCase();
        return chatConversations.filter(c => {
            if (chatFilter === 'unread' && (!c.unread_count || c.unread_count <= 0)) return false;
            if (!q) return true;
            const fullText = (c.name + ' ' + (c.program || '') + ' ' + (c.barangay || '')).toLowerCase();
            return fullText.includes(q);
        });
    }

    function renderChatList() {
        const itemsEl = $('cxItems');
        if (!itemsEl) return;
        itemsEl.innerHTML = '';

        const list = visibleChatConversations();
        if (!list.length) {
            const li = h('li', 'cx-empty');
            const title = chatQuery ? 'No applicants match "' + chatQuery + '"' : (chatFilter === 'unread' ? 'All caught up' : 'No applicants found');
            const desc = chatQuery ? 'Try searching another name or barangay.' : (chatFilter === 'unread' ? 'No unread messages from applicants.' : 'No users registered for this municipality.');
            li.appendChild(h('b', null, title));
            li.appendChild(document.createTextNode(desc));
            itemsEl.appendChild(li);
            return;
        }

        list.forEach(c => {
            const li = h('li');
            const btn = h('button', 'cx-item' + (c.unread_count > 0 ? ' unread' : ''));
            btn.type = 'button';
            btn.setAttribute('data-id', c.id);
            if (String(c.id) === String(activeChatUserId)) {
                btn.setAttribute('aria-current', 'true');
            }
            btn.setAttribute('aria-label', c.name + ', ' + c.program + (c.unread_count > 0 ? ', ' + c.unread_count + ' unread' : ''));

            btn.appendChild(h('span', 'cx-av', initials(c.name)));

            const who = h('span', 'cx-who');
            who.appendChild(h('span', 'cx-name', c.name));
            who.appendChild(h('span', 'cx-sub', (c.program || 'Applicant') + (c.barangay ? ' • ' + c.barangay : '')));
            who.appendChild(h('span', 'cx-prev', c.last_message_preview || 'No messages yet'));
            btn.appendChild(who);

            const meta = h('span', 'cx-meta');
            const timeStr = fmtListTime(c.latest_message_at);
            if (timeStr) {
                meta.appendChild(h('time', null, timeStr));
            }
            if (c.unread_count > 0) {
                meta.appendChild(h('span', 'cx-count', String(c.unread_count)));
            }
            btn.appendChild(meta);

            btn.addEventListener('click', () => selectChatUser(c.id, true));
            li.appendChild(btn);
            itemsEl.appendChild(li);
        });
    }

    function renderChatThread(preserveScroll) {
        const noneEl = $('cxNone');
        const chatEl = $('cxChat');
        const msgsEl = $('cxMsgs');
        const c = getConvById(activeChatUserId);

        if (!c) {
            if (chatEl) chatEl.style.display = 'none';
            if (noneEl) noneEl.style.display = 'flex';
            return;
        }

        if (noneEl) noneEl.style.display = 'none';
        if (chatEl) chatEl.style.display = 'flex';

        const tAv = $('cxTAv'); if (tAv) tAv.textContent = initials(c.name);
        const tName = $('cxTName'); if (tName) tName.textContent = c.name;
        const tSub = $('cxTSub'); if (tSub) tSub.textContent = (c.program || 'Applicant') + (c.barangay ? ' • ' + c.barangay : '');

        const statusKey = (c.status || 'pending').toLowerCase();
        const stTuple = APP_STATUS_MAP[statusKey] || APP_STATUS_MAP.pending;
        const stEl = $('cxTStatus');
        if (stEl) {
            stEl.className = 'st ' + stTuple[0];
            stEl.textContent = stTuple[1];
        }

        const openAppBtn = $('cxOpenApp');
        if (openAppBtn) {
            openAppBtn.href = c.appUrl || ("{{ route('admin.requirements') }}?search=" + encodeURIComponent(c.name));
        }

        if (!msgsEl) return;
        msgsEl.innerHTML = '';
        if (!c.messages || !c.messages.length) {
            const emptyThread = h('div', 'cx-empty');
            emptyThread.appendChild(h('b', null, 'Start the conversation'));
            emptyThread.appendChild(document.createTextNode('Send a message or use quick replies below.'));
            msgsEl.appendChild(emptyThread);
            return;
        }

        let prevTime = null;
        c.messages.forEach(m => {
            if (!prevTime || !sameDay(prevTime, m.t)) {
                msgsEl.appendChild(h('div', 'cx-day', fmtDay(m.t)));
            }
            const msgNode = h('div', 'cx-msg ' + m.from);
            msgNode.appendChild(h('p', 'cx-bub', m.text));

            const info = h('div', 'cx-info');
            info.appendChild(h('time', null, fmtTime(m.t)));
            if (m.from === 'admin') {
                info.appendChild(checkIcon(m.read));
                info.title = m.read ? 'Seen by applicant' : 'Sent';
            }
            msgNode.appendChild(info);

            msgsEl.appendChild(msgNode);
            prevTime = m.t;
        });

        if (!preserveScroll) {
            msgsEl.scrollTop = msgsEl.scrollHeight;
        }
    }

    async function loadChatUsers() {
        try {
            const res = await fetch(CHAT_ENDPOINTS.users);
            if (!res.ok) return;
            const users = await res.json();

            chatConversations = users.map(u => {
                const appInfo = RECENT_APPLICANTS_INFO[String(u.id)] || {};
                const existing = getConvById(u.id);
                return {
                    id: u.id,
                    name: u.full_name || 'Applicant',
                    program: appInfo.program || 'Applicant',
                    barangay: appInfo.barangay || (u.municipality || ''),
                    status: appInfo.status || 'pending',
                    appUrl: appInfo.appUrl || ("{{ route('admin.requirements') }}?search=" + encodeURIComponent(u.full_name || '')),
                    unread_count: parseInt(u.unread_count || 0, 10),
                    latest_message_at: u.latest_message_at ? new Date(u.latest_message_at).getTime() : 0,
                    last_message_preview: existing ? existing.last_message_preview : '',
                    messages: existing ? existing.messages : []
                };
            });

            renderChatBadges();
            renderChatList();
        } catch (e) {
            console.error('Failed to load chat users:', e);
        }
    }

    async function loadChatMessagesForUser(userId, preserveScroll) {
        if (!userId) return;
        try {
            const res = await fetch(CHAT_ENDPOINTS.messagesPrefix + '/' + userId);
            if (!res.ok) return;
            const msgs = await res.json();

            let conv = getConvById(userId);
            if (!conv) {
                await loadChatUsers();
                conv = getConvById(userId);
            }

            if (conv) {
                conv.messages = msgs.map(m => ({
                    id: m.id,
                    from: (m.sender_id == CURRENT_ADMIN_ID ? 'admin' : 'user'),
                    text: m.message,
                    t: new Date(m.created_at).getTime(),
                    read: !!m.is_read
                }));

                if (conv.messages.length > 0) {
                    const lastM = conv.messages[conv.messages.length - 1];
                    conv.last_message_preview = (lastM.from === 'admin' ? 'You: ' : '') + lastM.text;
                    conv.latest_message_at = lastM.t;
                }

                conv.unread_count = 0;
            }

            renderChatBadges();
            renderChatList();
            if (String(activeChatUserId) === String(userId)) {
                renderChatThread(preserveScroll);
            }
        } catch (e) {
            console.error('Failed to load messages:', e);
        }
    }

    function selectChatUser(userId, focusInput) {
        activeChatUserId = userId;
        const panel = $('cxPanel');
        if (panel) {
            panel.setAttribute('data-view', 'thread');
        }

        renderChatList();
        renderChatThread(false);
        loadChatMessagesForUser(userId, false);

        if (activeThreadPollId) clearInterval(activeThreadPollId);
        activeThreadPollId = setInterval(() => {
            if (isChatOpen && activeChatUserId) {
                loadChatMessagesForUser(activeChatUserId, true);
            }
        }, 4000);

        const input = $('cxInput');
        if (input) {
            input.value = '';
            autoGrowChatInput();
            updateSendBtnState();
            if (focusInput && window.innerWidth > 900) {
                input.focus();
            }
        }
    }

    function showChatList() {
        const panel = $('cxPanel');
        if (panel) {
            panel.setAttribute('data-view', 'list');
        }
        activeChatUserId = null;
        if (activeThreadPollId) clearInterval(activeThreadPollId);
        renderChatList();
        renderChatThread();
    }

    async function sendChatMessage() {
        const input = $('cxInput');
        const sendBtn = $('cxSend');
        const text = input ? input.value.trim() : '';

        if (!text || !activeChatUserId) return;

        sendBtn.disabled = true;
        const conv = getConvById(activeChatUserId);

        const optimisticMsg = {
            from: 'admin',
            text: text,
            t: Date.now(),
            read: false
        };

        if (conv) {
            conv.messages = conv.messages || [];
            conv.messages.push(optimisticMsg);
            conv.last_message_preview = 'You: ' + text;
            conv.latest_message_at = optimisticMsg.t;
            renderChatThread(false);
            renderChatList();
        }

        input.value = '';
        autoGrowChatInput();
        updateSendBtnState();

        try {
            const res = await fetch(CHAT_ENDPOINTS.send, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    receiver_id: activeChatUserId,
                    message: text
                })
            });

            if (!res.ok) throw new Error('Send failed');
            const data = await res.json();
            loadChatMessagesForUser(activeChatUserId, true);
        } catch (e) {
            console.error('Failed to send chat message:', e);
            alert('Failed to send message. Please try again.');
        } finally {
            if (input) input.focus();
        }
    }

    function onChatSearchInput() {
        const input = $('cxSearch');
        chatQuery = input ? input.value : '';
        renderChatList();
    }

    function setChatFilter(filter) {
        chatFilter = filter;
        const tabAll = $('cxTabAll');
        const tabUnread = $('cxTabUnread');
        if (tabAll) tabAll.setAttribute('aria-selected', filter === 'all');
        if (tabUnread) tabUnread.setAttribute('aria-selected', filter === 'unread');
        renderChatList();
    }

    function openChatDrawer(targetUserId) {
        if (typeof closeNavDrawer === 'function') {
            try { closeNavDrawer(false); } catch(e) {}
        }

        const panel = $('cxPanel');
        const scrim = $('cxScrim');
        lastChatFocusEl = document.activeElement;
        isChatOpen = true;

        if (panel) {
            panel.classList.add('on');
            panel.setAttribute('aria-hidden', 'false');
        }
        if (scrim) {
            scrim.classList.add('on');
        }
        document.body.style.overflow = 'hidden';

        loadChatUsers().then(() => {
            if (targetUserId) {
                selectChatUser(targetUserId, true);
            } else if (!activeChatUserId && window.innerWidth > 900 && chatConversations.length > 0) {
                const first = chatConversations.slice().sort((a, b) => (b.unread_count || 0) - (a.unread_count || 0) || (b.latest_message_at || 0) - (a.latest_message_at || 0))[0];
                if (first) selectChatUser(first.id, false);
            }
        }).catch(err => {
            console.error('Failed to load chat users:', err);
        });
    }

    function closeChatDrawer() {
        const panel = $('cxPanel');
        const scrim = $('cxScrim');
        isChatOpen = false;

        if (activeThreadPollId) clearInterval(activeThreadPollId);

        if (panel) {
            panel.classList.remove('on');
            panel.setAttribute('aria-hidden', 'true');
        }
        if (scrim) {
            scrim.classList.remove('on');
        }
        document.body.style.overflow = '';

        if (lastChatFocusEl && typeof lastChatFocusEl.focus === 'function') {
            lastChatFocusEl.focus();
        }
    }

    function openChatWithApplicant(userId, name, program, barangay, status, appUrl) {
        if (!userId) {
            openChatDrawer();
            return;
        }

        let conv = getConvById(userId);
        if (!conv) {
            conv = {
                id: userId,
                name: name || 'Applicant',
                program: program || 'Applicant',
                barangay: barangay || '',
                status: status || 'pending',
                appUrl: appUrl || ("{{ route('admin.requirements') }}?search=" + encodeURIComponent(name || '')),
                unread_count: 0,
                latest_message_at: Date.now(),
                last_message_preview: '',
                messages: []
            };
            chatConversations.unshift(conv);
        } else {
            if (program) conv.program = program;
            if (barangay) conv.barangay = barangay;
            if (status) conv.status = status;
            if (appUrl) conv.appUrl = appUrl;
        }

        openChatDrawer(userId);
    }

    // Attach all globals directly to window so they are globally callable anywhere
    window.openChatDrawer = openChatDrawer;
    window.closeChatDrawer = closeChatDrawer;
    window.openChatModal = openChatDrawer;
    window.closeChatModal = closeChatDrawer;
    window.openChatWithApplicant = openChatWithApplicant;
    window.selectUser = function(uid, uname) { openChatWithApplicant(uid, uname); };
    window.onChatSearchInput = onChatSearchInput;
    window.setChatFilter = setChatFilter;
    window.sendChatMessage = sendChatMessage;
    window.showChatList = showChatList;

    function initChatDrawerDom() {
        renderChatQuickReplies();
        loadChatUsers();

        const input = $('cxInput');
        if (input) {
            input.addEventListener('input', () => {
                autoGrowChatInput();
                updateSendBtnState();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendChatMessage();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isChatOpen) {
                closeChatDrawer();
            }
        });

        if (pollIntervalId) clearInterval(pollIntervalId);
        pollIntervalId = setInterval(() => {
            fetch(CHAT_ENDPOINTS.unread)
                .then(r => r.json())
                .then(data => {
                    const count = data && typeof data.count === 'number' ? data.count : 0;
                    const hb = $('cxHdrBadge');
                    if (hb) {
                        hb.textContent = count > 9 ? '9+' : count;
                        hb.style.display = count > 0 ? 'flex' : 'none';
                    }
                    const qaDot = $('cxQaDot');
                    if (qaDot) {
                        qaDot.style.display = count > 0 ? 'block' : 'none';
                    }
                    const gb = $('globalUnreadBadge');
                    if (gb) {
                        gb.textContent = count;
                        gb.style.display = count > 0 ? 'flex' : 'none';
                    }
                })
                .catch(() => {});
        }, 10000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatDrawerDom);
    } else {
        initChatDrawerDom();
    }
})();
</script>
