<?php

namespace App\Filament\Resources\IncomingItemResource\Pages;

use App\Filament\Resources\IncomingItemResource;
use App\Models\IncomingItem;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class CreateIncomingItem extends CreateRecord
{
    protected static string $resource = IncomingItemResource::class;
    protected static ?string $title = 'Tambah Barang Masuk';
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $serial_number = $data['serial_number'];
            $part_number = $data['part_number'];
            
            
            $message = <<<TEXT
                    --------- Tambah Barang Masuk ---------

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
        }

        return IncomingItem::create($data);
    }

    protected function afterCreate(): void
    {

    }
}
