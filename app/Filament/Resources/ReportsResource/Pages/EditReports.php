<?php

namespace App\Filament\Resources\ReportsResource\Pages;

use App\Filament\Resources\ReportsResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReports extends EditRecord
{
    protected static string $resource = ReportsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = User::find($data['user_id']);
        $data['user_name'] = $user->name;
        $data['email'] = $user->email;
    
        return $data;
    }

    protected function afterSave(): void
    {
        // Get the status of the updated form (assuming 'status' is part of the form)
        $data = $this->record;
        $user = User::find($data['user_id']);

        // Send email based on the status
        // Mail::to($user->email)->send(new PelaporanMail($data));
    }
}
