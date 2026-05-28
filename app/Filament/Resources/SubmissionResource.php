<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Pengajuan Izin';
    protected static ?string $pluralModelLabel = 'Pengajuan Izin';
    protected static ?string $navigationLabel = 'Pengajuan Izin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('manpower_id')
                    ->relationship('manpower', 'username')
                    ->label('Tenaga Kerja (Manpower)')
                    ->disabled(),
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'username')
                    ->label('Supervisor Penyetuju')
                    ->disabled(),
                Forms\Components\TextInput::make('name')
                    ->label('Judul Pengajuan')
                    ->disabled(),
                Forms\Components\TextInput::make('type')
                    ->label('Jenis')
                    ->disabled(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->disabled(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->disabled(),
                Forms\Components\TextInput::make('total_days')
                    ->label('Total Hari')
                    ->disabled(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->disabled(),
                Forms\Components\TextInput::make('status')
                    ->label('Status'),
                Forms\Components\Textarea::make('reason')
                    ->label('Alasan Penolakan (Jika Ditolak)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manpower.username')
                    ->label('Tenaga Kerja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Judul Pengajuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_days')
                    ->label('Durasi (Hari)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}
