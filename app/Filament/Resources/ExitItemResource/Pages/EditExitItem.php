<?php

namespace App\Filament\Resources\ExitItemResource\Pages;

use App\Filament\Resources\ExitItemResource;
use App\Models\IncomingItem;
use App\Models\Site;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

class EditExitItem extends EditRecord
{
    protected static string $resource = ExitItemResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $site = Site::find($data['site_id']);
        $data['witel_id'] = $site->datel->witel->id;
        $data['to_id'] = $site->datel->id;
        $data['site_id'] = $site->id;

        $incoming_item = IncomingItem::find($data['incoming_item_id']);
        $data['part_number'] = $incoming_item->part_number;
        $data['witel'] = $incoming_item->site->datel->witel->name;
        $data['datel'] = $incoming_item->site->datel->name;
        $data['site'] = $incoming_item->site->site_id;
        $data['nuisance_ticket_incoming_items'] = $incoming_item->nuisance_ticket;
        $data['description_incoming_items'] = $incoming_item->description;
        $data['date_entry'] = $incoming_item->date_entry;
        $data['type_part'] = $incoming_item->type_part->name;
        $data['item_part'] = $incoming_item->item_part->name;
        $data['status_part'] = $incoming_item->status_part->name;
        $data['status_exdismentie'] = $incoming_item->status_exdismentie->name;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function beforeSave(): void
    {
        try {
            $serial_number = $this->record->incoming_item->serial_number;
            $part_number = $this->record->incoming_item->part_number;
            $team = auth()->user()->name;

            $message = <<<TEXT
                    --------- Edit Barang Keluar ---------
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

            $this->halt();
        }
    }
}
