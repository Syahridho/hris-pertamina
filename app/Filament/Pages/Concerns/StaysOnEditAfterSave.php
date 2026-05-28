<?php

namespace App\Filament\Pages\Concerns;

/**
 * Mixin untuk EditRecord — setelah save tetap di halaman edit (tidak redirect ke list).
 */
trait StaysOnEditAfterSave
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
