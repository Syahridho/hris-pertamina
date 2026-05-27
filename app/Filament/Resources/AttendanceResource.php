<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $modelLabel = 'Kehadiran';
    protected static ?string $pluralModelLabel = 'Kehadiran';
    protected static ?string $navigationLabel = 'Kehadiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('manpower_id')
                    ->relationship('manpower', 'username')
                    ->label('Tenaga Kerja (Manpower)')
                    ->required(),
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'username')
                    ->label('Supervisor'),
                Forms\Components\DateTimePicker::make('clock_in')
                    ->label('Clock In'),
                Forms\Components\DateTimePicker::make('clock_out')
                    ->label('Clock Out'),
                Forms\Components\DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required()
                    ->maxLength(20)
                    ->default('present'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('manpower.username')
                    ->label('Tenaga Kerja (Manpower)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supervisor.username')
                    ->label('Supervisor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_in')
                    ->label('Clock In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_out')
                    ->label('Clock Out')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
