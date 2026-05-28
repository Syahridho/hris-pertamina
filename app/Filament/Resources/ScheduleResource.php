<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $modelLabel       = 'Jadwal Kerja';
    protected static ?string $pluralModelLabel = 'Jadwal Kerja';
    protected static ?string $navigationLabel  = 'Jadwal Kerja';

    // ─────────────────────────────────────────────────────────────
    //  Konstanta urutan hari (dipakai di form & table)
    // ─────────────────────────────────────────────────────────────

    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    // ─────────────────────────────────────────────────────────────
    //  FORM
    //  Struktur datetimes (array berindex hari):
    //  [
    //    "Senin"  => ["aktif" => true,  "jam_mulai" => "08:00", "jam_selesai" => "17:00"],
    //    "Selasa" => ["aktif" => false, "jam_mulai" => null,    "jam_selesai" => null],
    //    ...
    //  ]
    // ─────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        // Buat schema per-hari (7 baris tetap, tidak bisa tambah/hapus)
        $hariSchema = [];

        foreach (self::HARI as $hari) {
            $hariSchema[] = Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Grid::make(4)
                        ->schema([

                            // Label hari (selalu tampil)
                            Forms\Components\Placeholder::make("hari_label_{$hari}")
                                ->label('')
                                ->content($hari)
                                ->extraAttributes(['style' => 'font-weight:600;font-size:14px;padding-top:6px;']),

                            // Toggle aktif / libur
                            Forms\Components\Toggle::make("datetimes.{$hari}.aktif")
                                ->label('Masuk')
                                ->default(false)
                                ->live()
                                ->inline(false),

                            // Jam Mulai — hanya tampil jika aktif
                            Forms\Components\TimePicker::make("datetimes.{$hari}.jam_mulai")
                                ->label('Jam Mulai')
                                ->seconds(false)
                                ->required(fn (Get $get): bool => (bool) $get("datetimes.{$hari}.aktif"))
                                ->disabled(fn (Get $get): bool => ! $get("datetimes.{$hari}.aktif"))
                                ->dehydrated(fn (Get $get): bool => (bool) $get("datetimes.{$hari}.aktif"))
                                ->live(),

                            // Jam Selesai — hanya tampil jika aktif
                            Forms\Components\TimePicker::make("datetimes.{$hari}.jam_selesai")
                                ->label('Jam Selesai')
                                ->seconds(false)
                                ->required(fn (Get $get): bool => (bool) $get("datetimes.{$hari}.aktif"))
                                ->disabled(fn (Get $get): bool => ! $get("datetimes.{$hari}.aktif"))
                                ->dehydrated(fn (Get $get): bool => (bool) $get("datetimes.{$hari}.aktif"))
                                ->live(),
                        ]),

                    // Preview / status libur di bawah setiap baris
                    Forms\Components\Placeholder::make("preview_{$hari}")
                        ->label('')
                        ->content(function (Get $get) use ($hari): string {
                            if ($get("datetimes.{$hari}.aktif")) {
                                $mulai   = $get("datetimes.{$hari}.jam_mulai")   ?? '—';
                                $selesai = $get("datetimes.{$hari}.jam_selesai") ?? '—';
                                return "✅ Masuk: {$mulai} — {$selesai}";
                            }
                            return '🔴 Libur';
                        }),
                ])
                ->compact();
        }

        return $form
            ->schema([

                // ── Nama Jadwal ──────────────────────────────────
                Forms\Components\TextInput::make('name')
                    ->label('Nama Type Attendance')
                    ->placeholder('Contoh: Shift Pagi, Shift Malam')
                    ->required()
                    ->maxLength(50)
                    ->columnSpanFull(),

                // ── Deskripsi ────────────────────────────────────
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Deskripsi singkat jadwal ini (opsional)')
                    ->rows(2)
                    ->columnSpanFull(),

                // ── Toleransi Keterlambatan ───────────────────────
                Forms\Components\TextInput::make('datetimes.tolerance_minutes')
                    ->label('Toleransi Keterlambatan (menit)')
                    ->helperText('Batas menit keterlambatan yang masih diizinkan. Contoh: 15')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(120)
                    ->default(15)
                    ->suffix('menit')
                    ->required()
                    ->columnSpanFull(),

                // ── Jadwal per Hari (7 baris tetap) ─────────────
                Forms\Components\Section::make('Jadwal Hari & Jam')
                    ->description('Aktifkan toggle untuk hari kerja. Hari yang tidak diaktifkan dianggap Libur.')
                    ->schema($hariSchema)
                    ->columnSpanFull(),

            ])
            ->columns(1);
    }

    // ─────────────────────────────────────────────────────────────
    //  TABLE
    // ─────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Jadwal')
                    ->searchable()
                    ->sortable(),

                // Tampilkan hari aktif saja
                Tables\Columns\TextColumn::make('hari_kerja')
                    ->label('Hari Kerja')
                    ->getStateUsing(function ($record): string {
                        $state = $record->datetimes;

                        if (! is_array($state) || empty($state)) {
                            return '—';
                        }

                        // Format baru: keyed by hari (skip key 'tolerance_minutes')
                        if (! array_is_list($state)) {
                            $skipKeys = ['tolerance_minutes', 'days', 'clock_in', 'clock_out'];
                            $aktif = collect($state)
                                ->reject(fn ($v, $k) => in_array($k, $skipKeys))
                                ->filter(fn ($v) => is_array($v) && ! empty($v['aktif']))
                                ->keys();

                            return $aktif->isEmpty() ? 'Libur semua' : $aktif->implode(', ');
                        }

                        // Format lama: list [{"hari":"Senin",...}]
                        $days = $state['days'] ?? [];
                        if (! empty($days)) {
                            return collect($days)->map(fn ($d) => ucfirst($d))->implode(', ');
                        }

                        return collect($state)->pluck('hari')->filter()->implode(', ') ?: '—';
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('toleransi')
                    ->label('Toleransi')
                    ->getStateUsing(function ($record): string {
                        $state = $record->datetimes;
                        if (! is_array($state)) return '—';

                        // Format baru
                        $menit = $state['tolerance_minutes'] ?? null;

                        // Format lama
                        if ($menit === null && isset($state['tolerance_minutes'])) {
                            $menit = $state['tolerance_minutes'];
                        }

                        return $menit !== null ? "{$menit} menit" : '—';
                    })
                    ->sortable(false),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  PAGES
    // ─────────────────────────────────────────────────────────────

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit'   => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
