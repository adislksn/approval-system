<?php

namespace App\Providers\Filament;

use App\Filament\Resources\ReportsResource\Widgets\ReportsOverview;
use App\Models\User;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use DutchCodingCompany\FilamentSocialite\Provider;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MarcoGermani87\FilamentCaptcha\FilamentCaptcha;

class AccessPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('access')
            ->path('access')
            ->login()
            ->registration()
            ->emailVerification()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ReportsOverview::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugin(
                FilamentShieldPlugin::make()
            )
            ->plugin(
                FilamentSocialitePlugin::make()
                    // (required) Add providers corresponding with providers in `config/services.php`. 
                    ->providers([
                        // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
                        Provider::make('google')
                            ->label('Google')
                            ->icon('fab-google')
                            ->color(Color::hex('#2f2a6b'))
                            ->outlined(false)
                            ->stateless(false)
                            ->scopes([
                                'openid',
                                'https://www.googleapis.com/auth/userinfo.profile',
                                'https://www.googleapis.com/auth/userinfo.email',
                            ])
                            ->with(['...']),
                    ])
                    ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        // dd($provider, $oauthUser, $plugin);

                        $authUser = User::UpdateOrCreate(
                            ['email' => $oauthUser->getEmail()],
                            [
                                'name' => $oauthUser->getName(),
                                'email' => $oauthUser->getEmail(),
                                'password' => bcrypt($oauthUser->getId()),
                            ]
                        );
                        Auth::attempt(['email' => $oauthUser->getEmail(), 'password' => $oauthUser->getId()]);
                        return redirect()->route('filament.access.pages.dashboard');
                    })
                    ->resolveUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        // dd($provider, $oauthUser, $plugin);

                        Auth::attempt(['email' => $oauthUser->getEmail(), 'password' => $oauthUser->getId()]);
                        return redirect()->route('filament.access.pages.dashboard');
                    })
                    ->slug('access')
                    ->registration(true)
                    // ->registration(fn (string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
                    ->userModelClass(\App\Models\User::class)
            )
            ->plugin(
                FilamentCaptcha::make()
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
