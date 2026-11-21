<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tampilan paginasi polos (tanpa Tailwind/Bootstrap)
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');

        // @rupiah($angka) -> Rp 1.500.000
        Blade::directive('rupiah', fn ($v) => "<?php echo 'Rp ' . number_format((int) ($v), 0, ',', '.'); ?>");
    }
}
