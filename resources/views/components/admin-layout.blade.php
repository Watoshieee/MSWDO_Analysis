{{-- Shared Admin layout shell: same 1280px / 32px bounds as the navbar. --}}
<style>
:root {
    --admin-shell-max: 1280px;
    --admin-gutter: 32px;
    --admin-control-h: 40px;
}
@media (max-width: 1199px) {
    :root { --admin-gutter: 16px; }
}

.hd-in,
.banner-inner,
.wrap,
.in,
main.in {
    max-width: var(--admin-shell-max);
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--admin-gutter);
    padding-right: var(--admin-gutter);
    box-sizing: border-box;
}

body > .container,
.main-content > .container,
div[style*="flex:1"] > .container {
    max-width: var(--admin-shell-max) !important;
    width: 100%;
    margin-left: auto !important;
    margin-right: auto !important;
    padding-left: var(--admin-gutter) !important;
    padding-right: var(--admin-gutter) !important;
    box-sizing: border-box;
}

.wrap {
    padding-top: 16px;
    padding-bottom: 28px;
}
main.in {
    padding-top: 16px;
    padding-bottom: 40px;
}
body .container.mt-4 {
    margin-top: 16px !important;
}
body .container.mb-5 {
    margin-bottom: 28px !important;
}

.banner-inner {
    padding-top: 18px;
    padding-bottom: 20px;
}

#appFilterForm .form-control,
#appFilterForm .form-select,
#appFilterForm .btn-action,
.admin-filter-grid .form-control,
.admin-filter-grid .form-select,
.admin-filter-grid .btn-action,
.filter-bar .search-input,
.filter-bar .form-control,
.filter-bar .form-select,
.form-control:not(textarea),
.form-select,
.btn-action,
.search-input {
    height: var(--admin-control-h);
    min-height: var(--admin-control-h);
    box-sizing: border-box;
}

.admin-filter-grid {
    display: grid;
    gap: 10px;
    align-items: center;
}
.admin-filter-grid-5 {
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) minmax(0, 1fr) auto auto;
}
.admin-filter-grid-6 {
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr) auto auto;
}

@media (max-width: 900px) {
    .admin-filter-grid-5,
    .admin-filter-grid-6 {
        grid-template-columns: 1fr 1fr;
    }
    .admin-filter-grid .btn-action {
        width: 100%;
        justify-content: center;
    }
}
@media (max-width: 560px) {
    .admin-filter-grid-5,
    .admin-filter-grid-6 {
        grid-template-columns: 1fr;
    }
}

.table-responsive,
.table-card > div[style*="overflow"],
.panel-card .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

html {
    overflow-x: clip;
}
</style>
