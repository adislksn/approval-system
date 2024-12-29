<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AccessPanelProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    Barryvdh\Snappy\ServiceProvider::class,
];
