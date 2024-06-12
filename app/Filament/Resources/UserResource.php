<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\User;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Grid::make(2)->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama User')
                                ->required(),
                            TextInput::make('username')
                                ->label('Username')
                                ->unique(ignorable: fn($record) => $record)
                                ->required(),
                            Select::make('is_admin')
                                ->label('Role')
                                ->columnSpan(1)
                                ->required()
                                ->options([
                                    0 => 'Teknisi',
                                    1 => 'Admin'
                                ])
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('password')
                                ->label('Password')
                                ->columnSpan(1)
                                ->password()
                                ->same('password_confirmation')
                                ->minLength(8)
                                ->required()
                                ->dehydrated(fn ($state) => filled($state))
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                            TextInput::make('password_confirmation')
                                ->label('Konfirmasi Password')
                                ->columnSpan(1)
                                ->password()
                                ->minLength(8)
                                ->required()
                                ->dehydrated(false),
                        ])

                    ])

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->sortable()->searchable(),
                TextColumn::make('username')->label('Username')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalButton('Hapus')
                    ->modalHeading('Hapus User')
                    ->modalSubheading('Apakah Anda yakin ingin melakukan ini?')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return User::where('id', '!=', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
