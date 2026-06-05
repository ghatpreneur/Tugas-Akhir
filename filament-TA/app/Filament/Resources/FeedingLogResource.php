<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use App\Filament\Resources\FeedingLogResource\Pages;
use App\Filament\Resources\FeedingLogResource\RelationManagers;
use App\Models\FeedingLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedingLogResource extends Resource
{
    protected static ?string $model = FeedingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->options([
                        'KOSONG' => 'KOSONG',
                        'BERHASIL ISI' => 'BERHASIL ISI',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'KOSONG' => 'danger',
                        'BERHASIL ISI' => 'success',
                        default => 'gray'
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('waktu')
                    ->dateTime('d-m-Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedingLogs::route('/'),
            'create' => Pages\CreateFeedingLog::route('/create'),
            'edit' => Pages\EditFeedingLog::route('/{record}/edit'),
        ];
    }
}
