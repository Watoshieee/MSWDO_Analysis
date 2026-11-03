{{-- Colors loaded: P={{ $adminPrimaryColor ?? 'DEFAULT' }} S={{ $adminSecondaryColor ?? 'DEFAULT' }} A={{ $adminAccentColor ?? 'DEFAULT' }} --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    :root {
        --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }} !important;
        --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }} !important;
        --accent-red: {{ $adminAccentColor ?? '#C41E24' }} !important;
        --primary-gradient: linear-gradient(135deg, {{ $adminPrimaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%) !important;
        --secondary-gradient: linear-gradient(135deg, {{ $adminSecondaryColor ?? '#FDB913' }} 0%, #E5A500 100%) !important;
    }
</style>
