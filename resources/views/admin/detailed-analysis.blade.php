<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detailed Analysis &bull; MSWDO {{ $municipality->name }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
:root {
  --navy: #1e2a78;
  --navy-d: #1a2470;
  --navy-l: #243088;
  --gold: #f5b82e;
  --gold-h: #ffc94d;
  --ink: #1b2559;
  --muted: #4d5878;
  --line: #dbe1f1;
  --bg: #f4f6fb;
  --tint: #eef1fb;
  --bad: #d4545c;
  --soft: #eef1f8;
}

* { box-sizing: border-box; }
body {
  margin: 0;
  background: var(--bg);
  color: var(--ink);
  font: 14px/1.5 Inter, system-ui, -apple-system, sans-serif;
  font-variant-numeric: tabular-nums;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
a { color: var(--navy); text-decoration: none; }
button { font: inherit; color: inherit; cursor: pointer; }
h1, h2, h3, p { margin: 0; }
:focus-visible { outline: 3px solid var(--gold); outline-offset: 2px; }

/* -- SHARED CONTAINER -- */
.in {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 32px;
  width: 100%;
}
main.in {
  padding-top: 32px;
  padding-bottom: 96px;
  display: grid;
  gap: 16px;
  flex: 1;
}
@media (max-width: 900px) {
  .in { padding: 0 20px; }
  main.in { padding-top: 24px; padding-bottom: 96px; }
}
@media (max-width: 560px) {
  .in { padding: 0 16px; }
}

/* -- HERO -- */
.hero {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #1a2470 0%, #243088 100%);
  color: #fff;
}
.hero::before, .hero::after {
  content: "";
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, .05);
  pointer-events: none;
}
.hero::before { width: 320px; height: 320px; left: -80px; top: 40px; }
.hero::after { width: 380px; height: 380px; right: -60px; top: -60px; }
.hero .in {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
  padding-top: 52px;
  padding-bottom: 48px;
}
.eyebrow {
  display: inline-block;
  padding: 7px 20px;
  border-radius: 99px;
  background: rgba(245, 184, 46, .16);
  border: 1px solid rgba(245, 184, 46, .45);
  color: var(--gold);
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
}
.hero h1 {
  margin: 14px 0 0;
  font-size: 40px;
  font-weight: 800;
  letter-spacing: -.02em;
  line-height: 1.15;
}
.hero .bar {
  display: block;
  width: 56px;
  height: 4px;
  border-radius: 4px;
  background: var(--gold);
  margin: 14px 0 16px;
}
.hero p {
  font-size: 16px;
  color: #dbe0f7;
  max-width: 640px;
}
.muni {
  border: 1px solid rgba(245, 184, 46, .45);
  background: rgba(245, 184, 46, .14);
  border-radius: 14px;
  padding: 18px 32px;
  text-align: center;
}
.muni b {
  display: block;
  font-size: 28px;
  font-weight: 800;
  color: var(--gold);
  line-height: 1.2;
}
.muni span {
  display: block;
  margin-top: 6px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: #e3bf63;
}
@media (max-width: 700px) {
  .hero .in { padding-top: 32px; padding-bottom: 30px; }
  .hero h1 { font-size: 30px; }
  .hero p { font-size: 14.5px; }
  .muni { padding: 12px 22px; }
  .muni b { font-size: 22px; }
  .muni span { font-size: 11.5px; }
}

/* -- FILTER BAR -- */
.fb {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 14px 16px;
  box-shadow: 0 1px 2px rgba(23, 32, 102, .06), 0 6px 16px -10px rgba(23, 32, 102, .16);
  display: grid;
  gap: 12px;
}
.fg {
  display: flex;
  flex-wrap: wrap;
  gap: 14px 28px;
}
.grp { min-width: 0; }
.fl {
  display: block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .06em;
  color: var(--muted);
  text-transform: uppercase;
  margin-bottom: 6px;
  list-style: none;
}
summary.fl { cursor: pointer; }
summary.fl::-webkit-details-marker { display: none; }
.seg {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}
.seg.y .chip { min-width: 72px; }
.chip {
  min-height: 40px;
  padding: 0 14px;
  border-radius: 10px;
  border: 1px solid var(--line);
  background: #fff;
  font-weight: 600;
  color: var(--navy);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  text-decoration: none;
  transition: all .15s;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}
.chip:hover {
  background: var(--tint);
  border-color: #c5cee8;
}
.chip[aria-pressed=true], .chip.on {
  background: var(--navy);
  color: #fff;
  border-color: var(--navy);
}
.chip.part::after {
  content: "";
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--gold);
}
.chip.pg[aria-pressed=true]::before, .chip.pg.on::before {
  content: "✓";
  font-size: 12px;
}
.act {
  display: flex;
  flex-wrap: wrap;
  gap: 8px 10px;
  align-items: center;
  border-top: 1px solid #eef1f8;
  padding-top: 10px;
  font-size: 12.5px;
  color: var(--muted);
}
.act span b { color: var(--ink); }
.pill {
  background: var(--tint);
  color: var(--navy);
  border-radius: 99px;
  padding: 3px 10px;
  font-weight: 600;
  font-size: 12px;
}
.rs {
  margin-left: 0;
  border: 0;
  background: none;
  color: var(--navy);
  font-weight: 700;
  text-decoration: underline;
  min-height: 36px;
  cursor: pointer;
}
.key {
  font-size: 11.5px;
  color: var(--muted);
  margin-top: 6px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.key::before {
  content: "";
  display: inline-block;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--gold);
}

/* -- ACTION BUTTONS (VIEW REPORT & EXPORT) -- */
.exg {
  position: relative;
  margin-left: auto;
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.ex-wrap { position: relative; }
.vr-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  min-height: 40px;
  padding: 0 14px;
  border-radius: 10px;
  border: 1px solid var(--line);
  background: #fff;
  color: var(--navy);
  font-size: 13px;
  font-weight: 800;
  transition: background .15s, border-color .15s;
  cursor: pointer;
}
.vr-btn:hover:not(:disabled) {
  background: var(--tint);
  border-color: #c5cee8;
}
.ex-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  min-height: 40px;
  padding: 0 14px;
  border-radius: 10px;
  border: 1px solid var(--gold);
  background: var(--gold);
  color: var(--navy-d);
  font-size: 13px;
  font-weight: 800;
  transition: background .15s;
  cursor: pointer;
}
.ex-btn:hover:not(:disabled) { background: var(--gold-h); }
.ex-btn svg:last-child { transition: transform .15s; }
.ex-btn[aria-expanded=true] svg:last-child { transform: rotate(180deg); }
.ex-menu {
  position: absolute;
  right: 0;
  top: calc(100% + 8px);
  z-index: 40;
  width: 300px;
  max-width: calc(100vw - 32px);
  background: #fff;
  color: var(--ink);
  border: 1px solid #e1e6f2;
  border-radius: 12px;
  box-shadow: 0 12px 28px rgba(20, 27, 77, .25);
  padding: 6px;
}
.ex-menu[hidden] { display: none; }
.ex-hd {
  padding: 8px 10px 8px;
  margin-bottom: 4px;
  border-bottom: 1px solid #eef1f8;
  font-size: 11.5px;
  color: var(--muted);
  line-height: 1.4;
}
.ex-hd b {
  display: block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--ink);
  margin-bottom: 2px;
}
.ex-it {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  min-height: 52px;
  padding: 6px 10px;
  border: 0;
  border-radius: 8px;
  background: none;
  text-align: left;
  text-decoration: none;
  color: var(--ink);
  transition: background .15s;
}
.ex-it:hover { background: var(--bg); color: var(--ink); }
.ex-ic {
  flex: none;
  display: grid;
  place-items: center;
  width: 34px;
  height: 34px;
  border-radius: 9px;
  background: var(--tint);
  color: var(--navy);
}
.ex-it b { display: block; font-size: 13px; font-weight: 700; }
.ex-it small { display: block; font-size: 11.5px; color: var(--muted); }

@media (max-width: 560px) {
  .exg { width: 100%; flex-wrap: nowrap; }
  .exg > * { flex: 1; }
  .vr-btn, .ex-btn { width: 100%; justify-content: center; min-height: 44px; }
  .ex-menu { left: 0; right: 0; width: auto; }
}

/* -- KPI CARDS -- */
.kp {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}
.k {
  flex: 1 1 170px;
  position: relative;
  overflow: hidden;
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 16px 14px 14px;
  min-width: 0;
}
.k::before {
  content: "";
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  height: 3px;
  background: var(--navy);
}
.k > span {
  display: block;
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: .05em;
  text-transform: uppercase;
  color: var(--muted);
}
.k b {
  display: block;
  font-size: 26px;
  line-height: 1.2;
  font-weight: 800;
  color: var(--navy);
  margin: 4px 0;
  overflow-wrap: anywhere;
}
.k b.na {
  font-size: 15px;
  color: #7a84a8;
  font-weight: 600;
  padding: 6px 0;
}
.k small {
  font-size: 12px;
  color: var(--muted);
}
.k .up {
  display: inline-block;
  background: #e3f6ea;
  color: #14663a;
  border-radius: 99px;
  padding: 1px 8px;
  font-weight: 600;
}
@media (max-width: 560px) {
  .kp { gap: 10px; }
  .k { flex-basis: calc(50% - 5px); }
  .k b { font-size: 22px; }
}

/* -- SECTION CARDS -- */
.cd {
  background: #fff;
  border: 1px solid var(--line);
  border-radius: 14px;
  box-shadow: 0 1px 2px rgba(23, 32, 102, .06), 0 6px 16px -10px rgba(23, 32, 102, .16);
  min-width: 0;
  overflow: hidden;
}
.ch {
  padding: 16px 18px 12px;
  border-bottom: 1px solid #eef1f8;
}
.ch h2 {
  font-size: 17px;
  font-weight: 800;
  color: var(--ink);
}
.ch h2::after {
  content: "";
  display: block;
  width: 28px;
  height: 3px;
  border-radius: 3px;
  background: var(--gold);
  margin-top: 6px;
}
.ch p {
  color: var(--muted);
  font-size: 12.5px;
  margin-top: 6px;
}
.bd { padding: 16px 18px; }
.bd h3 { font-size: 13px; font-weight: 700; margin: 0 0 8px; color: var(--ink); }

/* -- TWO-COL LAYOUTS -- */
.two { display: grid; gap: 16px; }
.two > .cd { display: flex; flex-direction: column; }
.two > .cd > .bd { flex: 1; }
@media (min-width: 1000px) {
  .two { grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); }
  .two.eq { grid-template-columns: 1fr 1fr; }
}

/* -- TREND ROWS -- */
.lg {
  display: flex;
  flex-wrap: wrap;
  gap: 6px 16px;
  font-size: 12.5px;
  color: var(--muted);
  margin-bottom: 8px;
}
.sw {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 3px;
  margin-right: 6px;
}
.tr {
  display: grid;
  grid-template-columns: 48px minmax(0, 1fr);
  gap: 10px;
  padding: 6px;
  border-radius: 10px;
  align-items: center;
}
.tr.on { background: #fff6dc; }
.tr > strong { font-weight: 800; color: var(--navy); }
.br {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 64px;
  gap: 8px;
  align-items: center;
  margin: 4px 0;
}
.br div {
  height: 14px;
  background: var(--tint);
  border-radius: 7px;
  overflow: hidden;
}
.br i {
  display: block;
  height: 100%;
  border-radius: 7px;
  min-width: 2px;
}
.br em {
  font-style: normal;
  font-weight: 700;
  font-size: 12.5px;
  text-align: right;
  color: var(--ink);
}
.na {
  color: #7a84a8;
  font-style: italic;
  font-size: 12.5px;
}

/* -- PROGRESS BARS & ITEMS -- */
.pr {
  display: grid;
  grid-template-columns: minmax(0, 1fr) auto;
  gap: 4px 12px;
  padding: 8px 0;
  border-top: 1px solid #eef1f8;
}
.pr:first-of-type { border-top: none; }
.pr .nm { font-weight: 600; overflow-wrap: anywhere; }
.pr .v { font-weight: 800; color: var(--navy); }
.tk {
  grid-column: 1 / -1;
  height: 8px;
  border-radius: 8px;
  background: #e4e9f7;
  overflow: hidden;
  display: flex;
}
.tk i { display: block; height: 100%; min-width: 2px; }

/* -- TABLES -- */
.tw {
  overflow-x: auto;
  border: 1px solid var(--line);
  border-radius: 10px;
  margin-top: 6px;
}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
th, td {
  padding: 9px 12px;
  text-align: right;
  border-top: 1px solid #eef1f8;
  white-space: nowrap;
}
thead th {
  background: var(--bg);
  font-size: 12px;
  color: var(--muted);
  border-top: 0;
  font-weight: 700;
}
th:first-child, td:first-child {
  text-align: left;
  position: sticky;
  left: 0;
  background: #fff;
  white-space: normal;
  min-width: 140px;
  overflow-wrap: anywhere;
}
thead th:first-child { background: var(--bg); }
.sel { background: #fff6dc !important; font-weight: 700; }
.fn { font-size: 12px; color: var(--muted); margin-top: 8px; }

@media (max-width: 560px) {
  .stk thead { display: none; }
  .stk, .stk tbody { display: block; }
  .stk tr {
    display: block;
    border: 1px solid var(--line);
    border-radius: 10px;
    margin: 8px;
    padding: 8px 12px;
  }
  .stk td, .stk th {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    border: 0;
    padding: 3px 0;
    position: static;
    min-width: 0;
    white-space: normal;
  }
  .stk td::before {
    content: attr(data-l);
    color: var(--muted);
    font-weight: 600;
  }
  .stk th:first-child {
    font-size: 14px;
    color: var(--navy);
    padding-bottom: 6px;
  }
  .tw { border: 0; }
}

/* -- GENDER RING & BARANGAY SCROLL -- */
.dn {
  display: grid;
  justify-items: center;
  gap: 16px;
  padding: 6px 0;
}
.ring {
  width: min(210px, 62%);
  aspect-ratio: 1;
  border-radius: 50%;
  display: grid;
  place-items: center;
}
.ring > div {
  width: 64%;
  aspect-ratio: 1;
  border-radius: 50%;
  background: #fff;
  display: grid;
  place-content: center;
  text-align: center;
}
.ring b {
  font-size: 24px;
  line-height: 1.1;
  color: var(--navy);
  font-weight: 800;
  display: block;
}
.ring small {
  color: var(--muted);
  font-size: 12px;
  display: block;
}
.bl {
  max-height: 340px;
  overflow: auto;
  padding-right: 6px;
}
.ms {
  text-align: center;
  padding: 26px 12px;
  display: grid;
  justify-items: center;
  gap: 6px;
}
.ms b { font-size: 15px; color: var(--ink); }
.ms p { color: var(--muted); max-width: 42ch; }
.ms-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 6px;
  padding: 8px 16px;
  border-radius: 8px;
  background: var(--navy);
  color: #fff;
  font-size: 12.5px;
  font-weight: 700;
  text-decoration: none;
}
.ms-btn:hover { background: var(--navy-d); color: #fff; }

/* -- REPORT PREVIEW MODAL -- */
.md-scrim {
  position: fixed;
  inset: 0;
  z-index: 80;
  display: grid;
  place-items: center;
  padding: 24px;
  background: rgba(20, 27, 77, .55);
  animation: mdf .18s ease;
}
.md-scrim[hidden] { display: none; }
@keyframes mdf { from { opacity: 0; } }
.md {
  display: flex;
  flex-direction: column;
  width: min(960px, 100%);
  max-height: min(86vh, 820px);
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 24px 60px rgba(20, 27, 77, .4);
  animation: mdu .22s cubic-bezier(.2, .8, .2, 1);
}
@keyframes mdu { from { opacity: 0; transform: translateY(14px); } }
.md-hd {
  position: relative;
  overflow: hidden;
  flex: none;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  padding: 20px 22px 18px;
  background: linear-gradient(135deg, #1a2470, #243088);
  color: #fff;
}
.md-hd::after {
  content: "";
  position: absolute;
  width: 260px;
  height: 260px;
  right: -60px;
  top: -110px;
  border-radius: 50%;
  background: rgba(255, 255, 255, .05);
  pointer-events: none;
}
.md-hd > * { position: relative; z-index: 1; }
.md-eb {
  display: inline-block;
  padding: 3px 12px;
  border-radius: 99px;
  background: rgba(245, 184, 46, .16);
  border: 1px solid rgba(245, 184, 46, .45);
  color: var(--gold);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
}
.md-hd h2 {
  margin: 10px 0 0;
  font-size: 22px;
  font-weight: 800;
  letter-spacing: -.01em;
  line-height: 1.2;
}
.md-bar {
  display: block;
  width: 40px;
  height: 3px;
  border-radius: 3px;
  background: var(--gold);
  margin: 8px 0 8px;
}
.md-hd p { font-size: 13px; color: #dbe0f7; }
.md-x {
  flex: none;
  display: grid;
  place-items: center;
  width: 38px;
  height: 38px;
  border: 0;
  border-radius: 99px;
  background: rgba(255, 255, 255, .12);
  color: #fff;
  padding: 0;
  cursor: pointer;
}
.md-x:hover { background: rgba(255, 255, 255, .22); }
.md-tabs {
  flex: none;
  display: flex;
  gap: 6px;
  overflow-x: auto;
  padding: 12px 18px;
  background: var(--bg);
  border-bottom: 1px solid var(--line);
}
.md-tabs .chip {
  flex: none;
  min-height: 36px;
  padding: 0 12px;
  font-size: 13px;
}
.md-bd {
  flex: 1;
  min-height: 0;
  overflow: auto;
  padding: 16px 18px;
}
.md-cap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  gap: 4px 12px;
  margin-bottom: 10px;
  font-size: 12.5px;
  color: var(--muted);
}
.md-cap b { color: var(--ink); }
.md-ft {
  flex: none;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 10px 16px;
  padding: 12px 18px;
  border-top: 1px solid var(--line);
  background: #fff;
}
.md-ft p { font-size: 12px; color: var(--muted); }
.md-act { display: flex; gap: 8px; margin-left: auto; }
.md-b {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  min-height: 40px;
  padding: 0 14px;
  border-radius: 10px;
  border: 1px solid var(--line);
  background: #fff;
  color: var(--navy);
  font-size: 13px;
  font-weight: 800;
  text-decoration: none;
}
.md-b:hover { background: var(--tint); color: var(--navy); }
.md-b.pri {
  background: var(--gold);
  border-color: var(--gold);
  color: var(--navy-d);
}
.md-b.pri:hover { background: var(--gold-h); }
@media (max-width: 640px) {
  .md-scrim { padding: 0; align-items: end; }
  .md { width: 100%; max-height: 92vh; border-radius: 16px 16px 0 0; }
  .md-hd { padding: 16px 16px 14px; }
  .md-hd h2 { font-size: 19px; }
  .md-bd { padding: 12px; }
  .md-act { width: 100%; }
  .md-b { flex: 1; justify-content: center; min-height: 44px; }
  .md-ft p { display: none; }
}

/* -- FOOTER -- */
.pg-footer {
  background: linear-gradient(90deg, #1a2470, #243088);
  color: rgba(255, 255, 255, .7);
  text-align: center;
  padding: 18px 0;
  font-size: 13px;
  margin-top: auto;
}
.pg-footer strong { color: #fff; }

@media (prefers-reduced-motion: reduce) {
  *, ::before, ::after {
    animation: none !important;
    transition: none !important;
  }
}
</style>
</head>
<body>

@include('components.admin-navbar')

{{-- --- HERO --------------------------------------------------------------- --}}
<div class="hero">
  <div class="in">
    <div>
      <span class="eyebrow">Analysis</span>
      <h1>Detailed Analysis</h1>
      <span class="bar" aria-hidden="true"></span>
      <p>Application statistics, program breakdown, and barangay-level data for {{ $municipality->name }}.</p>
    </div>
    <div class="muni">
      <b>{{ $municipality->name }}</b>
      <span>Municipality</span>
    </div>
  </div>
</div>

<main class="in" id="pgMain">

@php
/* -- Derive all program names ----------------------------------------- */
$allProgNames = collect(array_unique(array_merge(
    $programShareOverview->keys()->all(),
    $applicationsByProgram->keys()->all()
)))->sort()->values();
$progTotal = $allProgNames->count();
$appRate   = $totalApplications > 0 ? round($approvedApplications / $totalApplications * 100, 1) : null;

/* -- Trend data ------------------------------------------------------- */
$trendRows   = $yearlyTrendData->sortKeys();
$allTrendPop = $trendRows->pluck('total_population')->filter()->all();
$allTrendHH  = $trendRows->pluck('total_households')->filter()->all();
$sharedMax   = max(array_merge([1], $allTrendPop, $allTrendHH));
$trendYrList = $trendRows->keys()->sort()->values()->all();

/* -- Multi-year program beneficiary table ----------------------------- */
if (!isset($programsByYear)) {
    $programsByYear = [];
    try {
        $swRows = \App\Models\SocialWelfareProgram::where('municipality', auth()->user()->municipality)->get();
        foreach ($swRows as $row) {
            $pn = $row->program_type ?? null;
            $yr = isset($row->year) ? (int)$row->year : null;
            if (!$pn || !$yr) continue;
            $cnt = $row->beneficiary_count ?? 0;
            $programsByYear[$pn][$yr] = ($programsByYear[$pn][$yr] ?? 0) + (int)$cnt;
        }
    } catch (\Throwable $e) { $programsByYear = []; }
}
ksort($programsByYear);

/* Partial records for years (years missing barangay data) */
$partialYears = [2024, 2020, 2015];
@endphp

  {{-- --- FILTER BAR ------------------------------------------------------- --}}
  <div class="fb" id="filterBar">
    <div class="fg">
      <div class="grp">
        <span class="fl" id="yl">Data year</span>
        <div class="seg y" role="group" aria-labelledby="yl">
          @foreach($availableAnalysisYears as $yr)
          <a href="{{ route('admin.detailed-analysis', ['year' => $yr]) }}"
             class="chip {{ (int)$yr === (int)$analysisYear ? 'on' : '' }} {{ in_array($yr, $partialYears) ? 'part' : '' }}"
             data-y="{{ $yr }}"
             aria-pressed="{{ (int)$yr === (int)$analysisYear ? 'true' : 'false' }}">{{ $yr }}</a>
          @endforeach
        </div>
        <p class="key">Partial records</p>
      </div>

      @if($progTotal > 0)
      <details class="grp" id="pd" open style="flex:1 1 320px">
        <summary class="fl">Programs &bull; <span id="progSelCount">{{ $progTotal }}</span> of {{ $progTotal }} &#9662;</summary>
        <div class="seg pg" id="progChipsBd">
          @foreach($allProgNames as $pn)
          <button type="button" class="chip pg on" data-chip-prog="{{ $pn }}" data-p="{{ $pn }}"
                  onclick="toggleProg(this)" aria-pressed="true">{{ str_replace('_', ' ', $pn) }}</button>
          @endforeach
        </div>
      </details>
      @endif
    </div>

    <div class="act" aria-live="polite">
      <span id="showingText">Showing <b>{{ $municipality->name }}</b> &bull; <b>{{ $analysisYear }}</b> &bull; <span id="progCountLabel">{{ $progTotal }}</span> of {{ $progTotal }} programs</span>
      <span id="filterPills"></span>
      <button type="button" class="rs" id="resetLink" onclick="resetFilters()" style="display:none">Reset filters</button>
      <div class="exg">
        <button type="button" class="vr-btn" id="vrBtn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          View report
        </button>
        <div class="ex-wrap">
          <button type="button" class="ex-btn" id="exBtn" aria-expanded="false" aria-haspopup="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export data
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ex-menu" id="exMenu" hidden>
            <div class="ex-hd"><b>Export analysis data</b>{{ $municipality->name }} &bull; {{ $analysisYear }}</div>
            <a class="ex-it" href="{{ route('admin.detailed-analysis.export.csv', ['year' => $analysisYear]) }}">
              <span class="ex-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
              <span><b>CSV file</b><small>.csv &bull; data &amp; chart data, sectioned</small></span>
            </a>
            <a class="ex-it" href="{{ route('admin.detailed-analysis.export.excel', ['year' => $analysisYear]) }}">
              <span class="ex-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg></span>
              <span><b>Excel workbook</b><small>.xlsx &bull; multiple sheets, with charts</small></span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- --- KPI CARDS -------------------------------------------------------- --}}
  <div class="kp">
    <div class="k">
      <span>Population</span>
      @if($totalPop > 0)
        <b>{{ number_format($totalPop) }}</b>
        @php
          $prevPopYr = $trendRows->filter(fn($r, $y) => $y < $analysisYear && !empty($r->total_population))->keys()->last();
          $prevPop = $prevPopYr ? $trendRows[$prevPopYr]->total_population : null;
          $diffPop = ($prevPop !== null && $totalPop > 0) ? $totalPop - $prevPop : null;
        @endphp
        @if($diffPop !== null)
          <small><span class="up">{{ $diffPop >= 0 ? '+' : '' }}{{ number_format($diffPop) }}</span> vs {{ $prevPopYr }}</small>
        @else
          <small>Residents &bull; {{ $analysisYear }}</small>
        @endif
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>

    <div class="k">
      <span>Households</span>
      @if($totalHouseholds > 0)
        <b>{{ number_format($totalHouseholds) }}</b>
        @php
          $prevHhYr = $trendRows->filter(fn($r, $y) => $y < $analysisYear && !empty($r->total_households))->keys()->last();
          $prevHh = $prevHhYr ? $trendRows[$prevHhYr]->total_households : null;
          $diffHh = ($prevHh !== null && $totalHouseholds > 0) ? $totalHouseholds - $prevHh : null;
        @endphp
        @if($diffHh !== null)
          <small><span class="up">{{ $diffHh >= 0 ? '+' : '' }}{{ number_format($diffHh) }}</span> vs {{ $prevHhYr }}</small>
        @else
          <small>Households &bull; {{ $analysisYear }}</small>
        @endif
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>

    <div class="k">
      <span>Applications</span>
      @if($totalApplications > 0)
        <b id="kpiAppsVal">{{ number_format($totalApplications) }}</b>
        <small id="kpiAppsSub">Applications dated {{ $analysisYear }}</small>
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>

    <div class="k">
      <span>Unique applicants</span>
      @if($uniqueApplicants > 0)
        <b id="kpiUniqVal">{{ number_format($uniqueApplicants) }}</b>
        <small id="kpiUniqSub">Distinct people</small>
      @elseif($totalApplications > 0)
        <b id="kpiUniqVal">{{ number_format($totalApplications) }}</b>
        <small id="kpiUniqSub">Distinct people</small>
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>

    <div class="k">
      <span>Approved cases</span>
      @if($approvedApplications > 0 || $totalApplications > 0)
        <b id="kpiApprVal">{{ number_format($approvedApplications) }}</b>
        <small id="kpiApprSub">@if($appRate !== null)<span class="up">{{ $appRate }}%</span> approval rate @else Completed @endif</small>
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>

    <div class="k">
      <span>Beneficiaries</span>
      @if($totalBeneficiaries > 0)
        <b id="kpiBenefVal">{{ number_format($totalBeneficiaries) }}</b>
        <small id="kpiBenefSub">Program beneficiaries</small>
      @else
        <b class="na">No record</b>
        <small>Not recorded for {{ $analysisYear }}</small>
      @endif
    </div>
  </div>

  {{-- --- TWO-COL: TREND + PROGRAMS ---------------------------------------- --}}
  <div class="two">

    {{-- Population & household trend --}}
    <div class="cd">
      <div class="ch">
        <h2>Population &amp; household trend</h2>
        <p>Historical comparison across years with records</p>
      </div>
      <div class="bd">
        @if($trendRows->isEmpty())
          <div class="ms">
            <b>No trend data</b>
            <p>No historical population records found for {{ $municipality->name }}.</p>
          </div>
        @else
          <div class="lg">
            <span><i class="sw" style="background:var(--navy)"></i>Population</span>
            <span><i class="sw" style="background:var(--gold)"></i>Households</span>
          </div>
          @foreach($trendRows as $y => $row)
            @php
              $pVal = $row->total_population;
              $hVal = $row->total_households;
              $pPct = ($pVal && $sharedMax > 0) ? round($pVal / $sharedMax * 100, 1) : 0;
              $hPct = ($hVal && $sharedMax > 0) ? round($hVal / $sharedMax * 100, 1) : 0;
            @endphp
            <div class="tr {{ (int)$y === (int)$analysisYear ? 'on' : '' }}">
              <strong>{{ $y }}</strong>
              <div>
                @if($pVal)
                  <div class="br" tabindex="0" role="img" aria-label="Population {{ $y }}: {{ number_format($pVal) }}">
                    <div><i style="width:{{ $pPct }}%;background:var(--navy)"></i></div>
                    <em>{{ number_format($pVal) }}</em>
                  </div>
                @else
                  <div class="na">Population: no record</div>
                @endif

                @if($hVal)
                  <div class="br" tabindex="0" role="img" aria-label="Households {{ $y }}: {{ number_format($hVal) }}">
                    <div><i style="width:{{ $hPct }}%;background:var(--gold)"></i></div>
                    <em>{{ number_format($hVal) }}</em>
                  </div>
                @else
                  <div class="na">Households: no record</div>
                @endif
              </div>
            </div>
          @endforeach
          <p class="fn">Years listed come from stored records. Bars share one scale.</p>
        @endif
      </div>
    </div>

    {{-- Social welfare programs --}}
    <div class="cd">
      <div class="ch">
        <h2>Social welfare programs</h2>
        <p>Beneficiaries per program &bull; {{ $analysisYear }}</p>
      </div>
      <div class="bd">
        @if($programShareOverview->isEmpty())
          <div class="ms">
            <b>No program records for {{ $analysisYear }}</b>
            <p>No beneficiary records found for {{ $analysisYear }}.</p>
            <a href="{{ route('admin.data.dashboard') }}" class="ms-btn">Open Data Management</a>
          </div>
        @else
          <h3>Beneficiaries by program &bull; {{ $analysisYear }}</h3>
          @php $maxProg = $programShareOverview->max() ?: 1; @endphp
          @foreach($programShareOverview as $prog => $cnt)
            @php $pct = round($cnt / $maxProg * 100, 2); @endphp
            <div class="pr" data-prog-item="{{ $prog }}">
              <span class="nm">{{ str_replace('_', ' ', $prog) }}</span>
              <span class="v">{{ number_format($cnt) }}</span>
              <div class="tk" role="img" aria-label="{{ str_replace('_', ' ', $prog) }}: {{ number_format($cnt) }}">
                <i style="width:{{ $pct }}%;background:var(--navy)"></i>
              </div>
            </div>
          @endforeach

          @if(!empty($programsByYear))
            <h3 style="margin-top:16px">Beneficiaries across years</h3>
            <div class="tw">
              <table class="stk">
                <thead>
                  <tr>
                    <th>Program</th>
                    @foreach($trendYrList as $yr)
                      <th class="{{ (int)$yr === (int)$analysisYear ? 'sel' : '' }}">{{ $yr }}</th>
                    @endforeach
                  </tr>
                </thead>
                <tbody>
                  @foreach($programsByYear as $prog => $yearData)
                    <tr data-prog-row="{{ $prog }}">
                      <th scope="row">{{ str_replace('_', ' ', $prog) }}</th>
                      @foreach($trendYrList as $yr)
                        <td data-l="{{ $yr }}" class="{{ (int)$yr === (int)$analysisYear ? 'sel' : '' }}">
                          @if(isset($yearData[$yr]) && $yearData[$yr] > 0)
                            {{ number_format($yearData[$yr]) }}
                          @else
                            <span class="na">—</span>
                          @endif
                        </td>
                      @endforeach
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <p class="fn">&ldquo;—&rdquo; means no record for that year. It is not a zero.</p>
          @endif
        @endif
      </div>
    </div>

  </div>

  {{-- --- APPLICATION ANALYSIS ---------------------------------------------- --}}
  <div class="cd" id="appSection">
    <div class="ch">
      <h2>Application analysis &bull; {{ $analysisYear }}</h2>
      <p>Applications, status outcomes and detail by program</p>
    </div>
    <div class="bd">
      @if($totalApplications === 0)
        <div class="ms">
          <b>No applications for {{ $analysisYear }}</b>
          <p>Applications for this year have not been recorded yet. The layout fills in automatically when they are.</p>
        </div>
      @else
        @php
          $pendPct = $totalApplications > 0 ? $pendingApplications  / $totalApplications * 100 : 0;
          $apprPct = $totalApplications > 0 ? $approvedApplications / $totalApplications * 100 : 0;
          $rejPct  = $totalApplications > 0 ? $rejectedApplications / $totalApplications * 100 : 0;
          $hasBenef = $programShareOverview->isNotEmpty();
          $showRej  = $rejectedApplications > 0;
          $showUniq = $uniqueApplicants > 0;
        @endphp

        <h3>Status of {{ number_format($totalApplications) }} applications &bull; {{ $analysisYear }}</h3>
        <div class="tk" style="height:14px" role="img" aria-label="Pending {{ $pendingApplications }}, approved {{ $approvedApplications }}, rejected {{ $rejectedApplications }}">
          @if($pendPct > 0)<i style="width:{{ $pendPct }}%;background:var(--gold)" title="Pending: {{ number_format($pendingApplications) }}"></i>@endif
          @if($apprPct > 0)<i style="width:{{ $apprPct }}%;background:var(--navy-l)" title="Approved: {{ number_format($approvedApplications) }}"></i>@endif
          @if($rejPct  > 0)<i style="width:{{ $rejPct }}%;background:var(--bad)" title="Rejected: {{ number_format($rejectedApplications) }}"></i>@endif
          @if(!$pendPct && !$apprPct && !$rejPct)<i style="width:100%;background:var(--soft)"></i>@endif
        </div>

        <div class="lg" style="margin-top:8px">
          <span><i class="sw" style="background:var(--gold)"></i>Pending {{ number_format($pendingApplications) }} ({{ round($pendPct) }}%)</span>
          <span><i class="sw" style="background:var(--navy-l)"></i>Approved {{ number_format($approvedApplications) }} ({{ round($apprPct) }}%)</span>
          @if($showRej)
          <span><i class="sw" style="background:var(--bad)"></i>Rejected {{ number_format($rejectedApplications) }} ({{ round($rejPct) }}%)</span>
          @endif
        </div>

        <h3 style="margin-top:14px">By program</h3>
        @foreach($applicationsByProgram as $progName => $stats)
          @php
            $pTot = $stats['total'] ?? 0;
            $pPen = $stats['pending'] ?? 0;
            $pApp = $stats['approved'] ?? 0;
            $pRej = $stats['rejected'] ?? 0;
            $ppPct = $pTot > 0 ? $pPen / $pTot * 100 : 0;
            $paPct = $pTot > 0 ? $pApp / $pTot * 100 : 0;
            $prPct = $pTot > 0 ? $pRej / $pTot * 100 : 0;
          @endphp
          <div class="pr" data-app-prog="{{ $progName }}">
            <span class="nm">{{ str_replace('_', ' ', $progName) }}</span>
            <span class="v">{{ number_format($pTot) }}</span>
            <div class="tk" role="img" title="{{ str_replace('_', ' ', $progName) }}: {{ $pPen }} pending, {{ $pApp }} approved, {{ $pRej }} rejected">
              @if($ppPct > 0)<i style="width:{{ $ppPct }}%;background:var(--gold)"></i>@endif
              @if($paPct > 0)<i style="width:{{ $paPct }}%;background:var(--navy-l)"></i>@endif
              @if($prPct > 0)<i style="width:{{ $prPct }}%;background:var(--bad)"></i>@endif
            </div>
          </div>
        @endforeach

        <div class="tw">
          <table class="stk">
            <thead>
              <tr>
                <th>Program</th>
                <th>Applications</th>
                @if($showUniq)<th>Unique applicants</th>@endif
                <th>Pending</th>
                <th>Approved</th>
                @if($showRej)<th>Rejected</th>@endif
                @if($hasBenef)<th class="sel">Beneficiaries</th>@endif
              </tr>
            </thead>
            <tbody>
              @foreach($applicationsByProgram as $progName => $stats)
                @php
                  $pBenef = $programShareOverview->get($progName);
                @endphp
                <tr data-app-row="{{ $progName }}">
                  <th scope="row">{{ str_replace('_', ' ', $progName) }}</th>
                  <td data-l="Applications">{{ number_format($stats['total'] ?? 0) }}</td>
                  @if($showUniq)<td data-l="Unique applicants">@if(isset($stats['unique_count']) && $stats['unique_count'] > 0) {{ number_format($stats['unique_count']) }} @else <span class="na">—</span> @endif</td>@endif
                  <td data-l="Pending">{{ number_format($stats['pending'] ?? 0) }}</td>
                  <td data-l="Approved">{{ number_format($stats['approved'] ?? 0) }}</td>
                  @if($showRej)<td data-l="Rejected">{{ number_format($stats['rejected'] ?? 0) }}</td>@endif
                  @if($hasBenef)<td data-l="Beneficiaries" class="sel">@if($pBenef !== null) {{ number_format($pBenef) }} @else <span class="na">—</span> @endif</td>@endif
                </tr>
              @endforeach
              <tr style="font-weight:700">
                <th scope="row">Total</th>
                <td data-l="Applications"><b>{{ number_format($totalApplications) }}</b></td>
                @if($showUniq)<td data-l="Unique applicants"><b>{{ number_format($uniqueApplicants) }}</b></td>@endif
                <td data-l="Pending"><b>{{ number_format($pendingApplications) }}</b></td>
                <td data-l="Approved"><b>{{ number_format($approvedApplications) }}</b></td>
                @if($showRej)<td data-l="Rejected"><b>{{ number_format($rejectedApplications) }}</b></td>@endif
                @if($hasBenef)<td data-l="Beneficiaries" class="sel"><b>{{ number_format($totalBeneficiaries) }}</b></td>@endif
              </tr>
            </tbody>
          </table>
        </div>
        <p class="fn">Applications, unique applicants, approved cases and beneficiaries are separate measures. Columns appear only when the stored data supports them.</p>
      @endif
    </div>
  </div>

  {{-- --- TWO-COL: GENDER + BARANGAY --------------------------------------- --}}
  <div class="two eq">

    {{-- Gender distribution --}}
    <div class="cd">
      <div class="ch">
        <h2>Gender distribution</h2>
        <p>Male vs Female &bull; {{ $analysisYear }}</p>
      </div>
      <div class="bd">
        @php
          $mCount = $genderData['male'] ?? 0;
          $fCount = $genderData['female'] ?? 0;
          $gTotal = $mCount + $fCount;
          $mPct = $gTotal > 0 ? round($mCount / $gTotal * 100) : 50;
          $fPct = 100 - $mPct;
        @endphp
        @if($gTotal === 0)
          <div class="ms">
            <b>No gender data for {{ $analysisYear }}</b>
            <p>Gender figures have not been recorded for this year.</p>
          </div>
        @else
          <div class="dn">
            <div class="ring" role="img" aria-label="Male {{ number_format($mCount) }}, female {{ number_format($fCount) }}"
                 style="background:conic-gradient(var(--navy) 0 {{ $mPct }}%, var(--gold) {{ $mPct }}% 100%)">
              <div>
                <b>{{ number_format($gTotal) }}</b>
                <small>Residents</small>
              </div>
            </div>
            <div class="lg" style="justify-content:center">
              <span><i class="sw" style="background:var(--navy)"></i>Male {{ number_format($mCount) }} ({{ $mPct }}%)</span>
              <span><i class="sw" style="background:var(--gold)"></i>Female {{ number_format($fCount) }} ({{ $fPct }}%)</span>
            </div>
          </div>
        @endif
      </div>
    </div>

    {{-- Population by barangay --}}
    <div class="cd">
      <div class="ch">
        <h2>Population by barangay &bull; {{ $analysisYear }}</h2>
        <p>Ranked by number of residents</p>
      </div>
      <div class="bd">
        @if($barangayRowsForAnalysis->isEmpty())
          <div class="ms">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.3"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            <b>No barangay records for {{ $analysisYear }}</b>
            <p>Barangay figures appear here once they are recorded.</p>
            <a href="{{ route('admin.data.dashboard') }}" class="ms-btn">Open Data Management</a>
          </div>
        @else
          @php
            $bMax = $barangayRowsForAnalysis->max('total_population') ?: 1;
          @endphp
          <div class="bl">
            @foreach($barangayRowsForAnalysis->sortByDesc('total_population') as $i => $b)
              @php $bPct = round($b->total_population / $bMax * 100, 1); @endphp
              <div class="pr">
                <span class="nm">#{{ $loop->iteration }} {{ $b->name }}</span>
                <span class="v">@if($b->total_population) {{ number_format($b->total_population) }} @else <span class="na">—</span> @endif</span>
                <div class="tk" role="img" aria-label="{{ $b->name }}: {{ number_format($b->total_population) }}">
                  <i style="width:{{ $bPct }}%;background:var(--navy)"></i>
                </div>
              </div>
            @endforeach
          </div>
          <p class="fn">{{ $barangayRowsForAnalysis->count() }} barangays recorded. The list scrolls inside this card.</p>
        @endif
      </div>
    </div>

  </div>

</main>

<footer class="pg-footer">
  <div><strong>MSWDO</strong> — {{ $municipality->name }} &bull; Analysis {{ $analysisYear }}</div>
</footer>

{{-- --- REPORT PREVIEW MODAL ---------------------------------------------- --}}
<div class="md-scrim" id="vrModal" hidden role="dialog" aria-modal="true" aria-labelledby="vrTitle">
  <div class="md">
    <div class="md-hd">
      <div>
        <span class="md-eb">Analysis report</span>
        <h2 id="vrTitle">{{ $municipality->name }} Detailed Analysis</h2>
        <span class="md-bar" aria-hidden="true"></span>
        <p>Summary of {{ $analysisYear }} demographics, welfare programs, and applications</p>
      </div>
      <button type="button" class="md-x" id="vrClose" aria-label="Close report preview">&times;</button>
    </div>

    <div class="md-tabs" id="vrTabs" role="tablist">
      <button class="chip on" data-vr-tab="0" role="tab" aria-selected="true">Summary</button>
      <button class="chip" data-vr-tab="1" role="tab" aria-selected="false">Demographics</button>
      <button class="chip" data-vr-tab="2" role="tab" aria-selected="false">Programs</button>
      <button class="chip" data-vr-tab="3" role="tab" aria-selected="false">Barangays</button>
    </div>

    <div class="md-bd" id="vrBd">
      {{-- Body dynamically populated by vrRenderBody() --}}
    </div>

    <div class="md-ft">
      <p>{{ $municipality->name }} &bull; {{ $analysisYear }} &bull; Internal document</p>
      <div class="md-act">
        <button type="button" class="md-b" onclick="closeVrModal()">Close</button>
        <a class="md-b pri" href="{{ route('admin.detailed-analysis.export.csv', ['year' => $analysisYear]) }}">Export CSV</a>
        <a class="md-b pri" href="{{ route('admin.detailed-analysis.export.excel', ['year' => $analysisYear]) }}">Export Excel</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* -- INTERACTIVE CLIENT-SIDE STATE ------------------------------------- */
const ALL_PROGRAMS = @json($allProgNames);
const TOTAL_PROGS  = ALL_PROGRAMS.length;
const selectedProgs = new Set(ALL_PROGRAMS);

function toggleProg(btn) {
    const prog = btn.dataset.chipProg;
    if (selectedProgs.has(prog)) {
        if (selectedProgs.size === 1) return; // keep at least one
        selectedProgs.delete(prog);
        btn.classList.remove('on');
        btn.setAttribute('aria-pressed', 'false');
    } else {
        selectedProgs.add(prog);
        btn.classList.add('on');
        btn.setAttribute('aria-pressed', 'true');
    }
    updateFilterDisplay();
    filterDomElements();
}

function resetFilters() {
    ALL_PROGRAMS.forEach(p => selectedProgs.add(p));
    document.querySelectorAll('[data-chip-prog]').forEach(btn => {
        btn.classList.add('on');
        btn.setAttribute('aria-pressed', 'true');
    });
    updateFilterDisplay();
    filterDomElements();
}

function updateFilterDisplay() {
    const count = selectedProgs.size;
    const cntEl = document.getElementById('progSelCount');
    if (cntEl) cntEl.textContent = count;
    const lblEl = document.getElementById('progCountLabel');
    if (lblEl) lblEl.textContent = count;

    const pillsEl = document.getElementById('filterPills');
    const resetEl = document.getElementById('resetLink');
    if (count < TOTAL_PROGS) {
        if (pillsEl) pillsEl.innerHTML = `<span class="pill">Programs: ${count} of ${TOTAL_PROGS}</span>`;
        if (resetEl) resetEl.style.display = 'inline-block';
    } else {
        if (pillsEl) pillsEl.innerHTML = '';
        if (resetEl) resetEl.style.display = 'none';
    }
}

function filterDomElements() {
    // 1. Social Welfare Programs bars
    document.querySelectorAll('[data-prog-item]').forEach(el => {
        const p = el.dataset.progItem;
        el.style.display = selectedProgs.has(p) ? '' : 'none';
    });

    // 2. Beneficiaries across years table rows
    document.querySelectorAll('[data-prog-row]').forEach(el => {
        const p = el.dataset.progRow;
        el.style.display = selectedProgs.has(p) ? '' : 'none';
    });

    // 3. Application section program bars
    document.querySelectorAll('[data-app-prog]').forEach(el => {
        const p = el.dataset.appProg;
        el.style.display = selectedProgs.has(p) ? '' : 'none';
    });

    // 4. Application section table rows
    document.querySelectorAll('[data-app-row]').forEach(el => {
        const p = el.dataset.appRow;
        el.style.display = selectedProgs.has(p) ? '' : 'none';
    });
}

/* -- EXPORT DROPDOWN --------------------------------------------------- */
(function() {
    const btn = document.getElementById('exBtn');
    const menu = document.getElementById('exMenu');
    if (!btn || !menu) return;
    btn.addEventListener('click', e => {
        e.stopPropagation();
        const isOpen = !menu.hidden;
        menu.hidden = isOpen;
        btn.setAttribute('aria-expanded', String(!isOpen));
    });
    document.addEventListener('click', e => {
        if (!menu.hidden && !e.target.closest('#exBtn') && !e.target.closest('#exMenu')) {
            menu.hidden = true;
            btn.setAttribute('aria-expanded', 'false');
        }
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !menu.hidden) {
            menu.hidden = true;
            btn.setAttribute('aria-expanded', 'false');
            btn.focus();
        }
    });
})();

/* -- REPORT PREVIEW MODAL ---------------------------------------------- */
let vrCurTab = 0;
const VR_DATA = {
    muni: @json($municipality->name),
    year: {{ (int)$analysisYear }},
    pop: {{ (int)$totalPop }},
    hh: {{ (int)$totalHouseholds }},
    apps: {{ (int)$totalApplications }},
    uniq: {{ (int)$uniqueApplicants }},
    appr: {{ (int)$approvedApplications }},
    benef: {{ (int)$totalBeneficiaries }},
    trend: @json($trendRows->values()->toArray()),
    progs: @json($programShareOverview->toArray()),
    bgys: @json($barangayRowsForAnalysis->sortByDesc('total_population')->values()->toArray())
};

function FMT(n) {
    return n != null ? Number(n).toLocaleString() : '—';
}

function vrContent(tabIdx) {
    const d = VR_DATA;
    if (tabIdx === 0) {
        return `
            <p class="md-cap"><b>Key summary figures</b><span>${d.muni} &bull; ${d.year}</span></p>
            <div class="tw">
              <table>
                <thead><tr><th>Metric</th><th>Value</th><th>Note</th></tr></thead>
                <tbody>
                  <tr><th scope="row">Population</th><td><b>${FMT(d.pop)}</b></td><td>Residents in ${d.year}</td></tr>
                  <tr><th scope="row">Households</th><td><b>${FMT(d.hh)}</b></td><td>Registered households</td></tr>
                  <tr><th scope="row">Applications</th><td><b>${FMT(d.apps)}</b></td><td>Submitted in ${d.year}</td></tr>
                  <tr><th scope="row">Unique applicants</th><td><b>${FMT(d.uniq)}</b></td><td>Distinct residents</td></tr>
                  <tr><th scope="row">Approved cases</th><td><b>${FMT(d.appr)}</b></td><td>Completed cases</td></tr>
                  <tr><th scope="row">Beneficiaries</th><td><b>${FMT(d.benef)}</b></td><td>Across all programs</td></tr>
                </tbody>
              </table>
            </div>`;
    }
    if (tabIdx === 1) {
        if (!d.trend || !d.trend.length) return '<div class="ms"><b>No demographic history</b><p>No historical records recorded yet.</p></div>';
        return `
            <p class="md-cap"><b>Historical population &amp; households</b><span>${d.muni}</span></p>
            <div class="tw">
              <table>
                <thead><tr><th>Year</th><th>Population</th><th>Households</th></tr></thead>
                <tbody>
                  ${d.trend.map(t => `<tr class="${t.year == d.year ? 'sel' : ''}"><th scope="row">${t.year}</th><td>${FMT(t.total_population)}</td><td>${FMT(t.total_households)}</td></tr>`).join('')}
                </tbody>
              </table>
            </div>`;
    }
    if (tabIdx === 2) {
        const pKeys = Object.keys(d.progs || {});
        if (!pKeys.length) return '<div class="ms"><b>No program records</b><p>No welfare programs recorded for this year.</p></div>';
        return `
            <p class="md-cap"><b>Welfare programs breakdown</b><span>${d.year}</span></p>
            <div class="tw">
              <table>
                <thead><tr><th>Program</th><th>Beneficiaries</th></tr></thead>
                <tbody>
                  ${pKeys.map(k => `<tr><th scope="row">${k.replace(/_/g, ' ')}</th><td>${FMT(d.progs[k])}</td></tr>`).join('')}
                  <tr style="font-weight:700"><th scope="row">Total</th><td><b>${FMT(d.benef)}</b></td></tr>
                </tbody>
              </table>
            </div>`;
    }
    if (tabIdx === 3) {
        const bgys = d.bgys;
        if (!bgys || !bgys.length) return '<div class="ms"><b>No barangay data</b><p>No barangay records for ' + d.year + '.</p></div>';
        return `
            <p class="md-cap"><b>Population by barangay</b><span>${d.year}</span></p>
            <div class="tw">
              <table>
                <thead><tr><th>Barangay</th><th>Population</th></tr></thead>
                <tbody>
                  ${bgys.map((b, i) => `<tr><th scope="row">#${i + 1} ${b.name}</th><td>${b.total_population ? FMT(b.total_population) : '—'}</td></tr>`).join('')}
                </tbody>
              </table>
            </div>`;
    }
    return '';
}

function vrRenderTabs() {
    const tabs = document.getElementById('vrTabs');
    if (!tabs) return;
    const labels = ['Summary', 'Demographics', 'Programs', 'Barangays'];
    tabs.innerHTML = labels.map((l, i) => `<button class="chip ${i === vrCurTab ? 'on' : ''}" data-vr-tab="${i}" role="tab" aria-selected="${i === vrCurTab}">${l}</button>`).join('');
}

function vrRenderBody() {
    const bd = document.getElementById('vrBd');
    if (!bd) return;
    bd.innerHTML = vrContent(vrCurTab);
    bd.scrollTop = 0;
}

function openVrModal() {
    vrCurTab = 0;
    const modal = document.getElementById('vrModal');
    if (!modal) return;
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    vrRenderTabs();
    vrRenderBody();
    document.getElementById('vrClose')?.focus();
}

function closeVrModal() {
    const modal = document.getElementById('vrModal');
    if (!modal) return;
    modal.hidden = true;
    document.body.style.overflow = '';
    document.getElementById('vrBtn')?.focus();
}

document.getElementById('vrBtn')?.addEventListener('click', openVrModal);
document.getElementById('vrClose')?.addEventListener('click', closeVrModal);
document.getElementById('vrModal')?.addEventListener('click', e => {
    if (e.target === e.currentTarget) closeVrModal();
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !document.getElementById('vrModal')?.hidden) {
        closeVrModal();
    }
});
document.getElementById('vrTabs')?.addEventListener('click', e => {
    const btn = e.target.closest('[data-vr-tab]');
    if (!btn) return;
    vrCurTab = Number(btn.dataset.vrTab);
    vrRenderTabs();
    vrRenderBody();
    btn.focus();
});
</script>
</body>
</html>