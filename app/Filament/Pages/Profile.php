<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use JeffGreco13\FilamentBreezy\Traits\HasBreezyTwoFactor;

class Profile extends Page
{
    use HasBreezyTwoFactor;

    protected static string $view = "filament.pages.profile";

    public $user;
    public $userData;
    // Password
    public $new_password;
    public $new_password_confirmation;

    public function boot()
    {
        // user column
        $this->loginColumn = config('filament-breezy.fallback_login_field');
    }

    public function mount()
    {
        $this->user = Filament::auth()->user();
        $this->updateProfileForm->fill($this->user->toArray());
    }

    protected function getForms(): array
    {
        return array_merge(parent::getForms(), [
            "updateProfileForm" => $this->makeForm()
                ->model(config('filament-breezy.user_model'))
                ->schema($this->getUpdateProfileFormSchema())
                ->statePath('userData'),

            "updatePasswordForm" => $this->makeForm()->schema(
                $this->getUpdatePasswordFormSchema()
            )
        ]);
    }

    protected function getUpdateProfileFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->label('Name'),
            TextInput::make('username')
                ->required()
                ->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                ->label('username'),
        ];
    }

    public function updateProfile()
    {
        $this->user->update($this->updateProfileForm->getState());
        $this->notify("success", __('filament-breezy::default.profile.personal_info.notify'));
    }

    protected function getUpdatePasswordFormSchema(): array
    {
        return [
            TextInput::make("new_password")
                ->label(__('filament-breezy::default.fields.new_password'))
                ->password()
                ->rules(app(FilamentBreezy::class)->getPasswordRules())
                ->required(),
            TextInput::make("new_password_confirmation")
                ->label(__('filament-breezy::default.fields.new_password_confirmation'))
                ->password()
                ->same("new_password")
                ->required(),
        ];
    }

    public function updatePassword()
    {
        $state = $this->updatePasswordForm->getState();
        $this->user->update([
            "password" => Hash::make($state["new_password"]),
        ]);
        session()->forget("password_hash_web");
        Filament::auth()->login($this->user);
        $this->notify("success", __('filament-breezy::default.profile.password.notify'));
        $this->reset(["new_password", "new_password_confirmation"]);
    }

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => __('filament-breezy::default.profile.profile'),
        ];
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-breezy.profile_page_icon', 'heroicon-o-document-text');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-breezy::default.profile.account');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-breezy::default.profile.profile');
    }

    protected function getTitle(): string
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return config('filament-breezy.show_profile_page_in_navbar');
    }
}
