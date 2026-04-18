@php
    $viteManifestExists = file_exists(public_path('build/manifest.json'));
@endphp

@if ($viteManifestExists)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        display: ['Space Grotesk', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            background-image:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.16), transparent 32%),
                radial-gradient(circle at top right, rgba(45, 212, 191, 0.12), transparent 28%),
                linear-gradient(180deg, #0c0a09 0%, #1c1917 100%);
        }

        .shell { max-width: 1280px; margin: 0 auto; padding: 0 1rem; }
        .panel { border: 1px solid rgba(255,255,255,.10); background: rgba(255,255,255,.05); border-radius: 1.5rem; box-shadow: 0 25px 50px rgba(0,0,0,.2); backdrop-filter: blur(8px); }
        .panel-soft { border: 1px solid rgba(255,255,255,.10); background: rgba(28,25,23,.72); border-radius: 1rem; }
        .field { width: 100%; margin-top: .5rem; border-radius: 1rem; border: 1px solid rgba(255,255,255,.1); background: rgba(12,10,9,.85); color: #f5f5f4; padding: .85rem 1rem; font-size: .95rem; }
        .field::placeholder { color: #78716c; }
        .label { font-size: .875rem; font-weight: 600; color: #e7e5e4; }
        .btn-primary, .btn-secondary, .btn-danger { display: inline-flex; align-items: center; justify-content: center; border-radius: 1rem; padding: .75rem 1rem; font-size: .9rem; font-weight: 700; text-decoration: none; }
        .btn-primary { background: #478768; color: #f8fafc; border: 1px solid #478768; }
        .btn-secondary { background: rgba(255,255,255,.04); color: #f5f5f4; border: 1px solid rgba(255,255,255,.12); }
        .btn-danger { background: rgba(244,63,94,.12); color: #fecdd3; border: 1px solid rgba(251,113,133,.28); }
        .btn-primary:hover { background: #3b7358; }
        .btn-secondary:hover { background: rgba(255,255,255,.08); }
        .btn-danger:hover { background: rgba(244,63,94,.18); }
        .stat-card { border: 1px solid rgba(255,255,255,.10); border-radius: 1.5rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(255,255,255,.10), rgba(255,255,255,.04)); }
        .nav-link { display: inline-flex; align-items: center; height: 100%; padding: 0 .25rem; border-bottom: 2px solid transparent; font-size: .95rem; font-weight: 500; color: #a8a29e; text-decoration: none; }
        .nav-link:hover { color: #fafaf9; border-color: rgba(71,135,104,.45); }
        .nav-link-active { color: #fafaf9; border-color: #478768; }
        .responsive-nav-link { display: block; width: 100%; padding: .75rem 1rem; border-left: 4px solid transparent; color: #d6d3d1; text-decoration: none; }
        .responsive-nav-link-active { background: rgba(71,135,104,.15); border-left-color: #478768; color: #f5f5f4; }
    </style>
@endif
