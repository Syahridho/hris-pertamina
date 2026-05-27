<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorResource\Pages;
use App\Filament\Resources\SupervisorResource\RelationManagers;
use App\Models\Supervisor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupervisorResource extends Resource
{
    protected static ?string $model = Supervisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $modelLabel = 'Supervisor';
    protected static ?string $pluralModelLabel = 'Supervisor';
    protected static ?string $navigationLabel = 'Supervisor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Proyek')
                    ->required(),
                Forms\Components\Select::make('placement_id')
                    ->relationship('placement', 'name')
                    ->label('Lokasi Kerja')
                    ->required(),
                Forms\Components\TextInput::make('username')
                    ->label('Nama Pengguna')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->tel()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->maxLength(20)
                    ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('placement.name')
                    ->label('Lokasi Kerja')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Nama Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
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
            'index' => Pages\ListSupervisors::route('/'),
            'create' => Pages\CreateSupervisor::route('/create'),
            'edit' => Pages\EditSupervisor::route('/{record}/edit'),
        ];
    }
}
