<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlacementResource\Pages;
use App\Filament\Resources\PlacementResource\RelationManagers;
use App\Models\Placement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlacementResource extends Resource
{
    protected static ?string $model = Placement::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $modelLabel = 'Lokasi Kerja';
    protected static ?string $pluralModelLabel = 'Lokasi Kerja';
    protected static ?string $navigationLabel = 'Lokasi Kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('schedule_id')
                    ->relationship('schedule', 'name')
                    ->label('Jadwal')
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Proyek')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lokasi')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('coordinate')
                    ->label('Koordinat')
                    ->maxLength(225)
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('selectLocation')
                            ->icon('heroicon-m-map')
                            ->label('Pilih Lokasi')
                            ->modalHeading('Pilih Titik Lokasi Penempatan')
                            ->modalContent(view('filament.components.map-picker'))
                            ->modalSubmitAction(false) // updates Livewire directly in real-time
                    ),
                Forms\Components\TextInput::make('radius')
                    ->label('Radius Batas (Meter)')
                    ->required()
                    ->numeric()
                    ->default(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.name')
                    ->label('Jadwal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('coordinate')
                    ->label('Koordinat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('radius')
                    ->label('Radius (Meter)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPlacements::route('/'),
            'create' => Pages\CreatePlacement::route('/create'),
            'edit' => Pages\EditPlacement::route('/{record}/edit'),
        ];
    }
}
