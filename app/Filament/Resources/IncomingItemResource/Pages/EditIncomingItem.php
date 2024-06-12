<?php

namespace App\Filament\Resources\IncomingItemResource\Pages;

use App\Models\Site;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\IncomingItemResource;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class EditIncomingItem extends EditRecord
{
    protected static string $resource = IncomingItemResource::class;
    protected static ?string $title = 'Edit Barang Masuk';

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $site = Site::find($data['site_id']);

        $data['witel_id'] = $site->datel->witel->id;
        $data['from_id'] = $site->datel->id;
        $data['site_id'] = $site->id;

        return $data;
    }

    protected function beforeSave(): void
    {
        try {
            $witel = $this->record->witel;
            $serial_number = $this->record->serial_number;
            $part_number = $this->record->part_number;
            $message = <<<TEXT
                    --------- Edit Barang Masuk ---------
                    Serial Number:
                    $serial_number

                    Part Number:
                    $part_number
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

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
