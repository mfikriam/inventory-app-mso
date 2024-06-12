<?php

namespace App\Filament\Resources\ExitItemResource\Pages;

use App\Filament\Resources\ExitItemResource;
use App\Models\ExitItem;
use App\Models\IncomingItem;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class CreateExitItem extends CreateRecord
{
    protected static string $resource = ExitItemResource::class;
    protected static ?string $title = 'Tambah Barang Keluar';
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->is_admin != 1) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $incoming_item = IncomingItem::find($data['incoming_item_id']);
            $serial_number = $incoming_item->serial_number;
            $part_number = $incoming_item->part_number;
            $team = auth()->user()->name;
            $message = <<<TEXT
                    --------- Tambah Barang Keluar ---------
                    Serial Number:
                    $serial_number

                    Part Number:
                    $part_number

                    Team:
                    $team
                    ------------------------------------------
                   TEXT;
            Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID', '-1001542563732'),
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
        } catch (TelegramResponseException $e) {
            $errorData = $e->getResponseData();

            if ($errorData['ok'] === false) {
                Telegram::sendMessage([
                    'chat_id' => env('TELEGRAM_CHAT_ID', '-1001542563732'),
                    'text' => 'There was an error for a user. ' . $errorData['error_code'] . ' ' . $errorData['description'],
                ]);
            }
        }

        return ExitItem::create($data);
    }
}
