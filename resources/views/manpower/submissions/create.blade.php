@extends('layouts.manpower')
@section('title', 'Buat Pengajuan')
@section('page-title', 'Pengajuan Baru')

@section('content')
<style>
    .form-card { background: var(--navy-card); border: 1px solid var(--border); border-radius: 16px; padding: 20px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-muted); margin-bottom: 8px; }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 14px;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; }
    .form-textarea { resize: vertical; min-height: 100px; }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px var(--red-glow);
        background: rgba(255,255,255,0.08);
    }
    .form-input.is-error, .form-select.is-error, .form-textarea.is-error { border-color: #ef4444; }
    .error-msg { font-size: 12px; color: #f87171; margin-top: 6px; }
    .type-selector { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .type-option { display: none; }
    .type-label {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        padding: 14px 8px;
        border: 2px solid var(--border);
        border-radius: 12px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        transition: border-color 0.2s, color 0.2s, background 0.2s;
        text-align: center;
    }
    .type-label span:first-child { font-size: 24px; }
    .type-option:checked + .type-label { border-color: var(--red); color: var(--text); background: rgba(232,25,44,0.08); }
    .file-upload-area {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .file-upload-area:hover { border-color: var(--red); }
    .file-upload-area input { display: none; }
    .file-upload-icon { font-size: 32px; margin-bottom: 8px; }
    .file-upload-text { font-size: 13px; color: var(--text-muted); }
    .file-name { font-size: 13px; color: var(--red); margin-top: 8px; font-weight: 500; }
    .btn-submit {
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--red), var(--red-dark));
        color: white; border: none; border-radius: 12px;
        font-size: 15px; font-weight: 700; font-family: inherit;
        cursor: pointer; box-shadow: 0 8px 24px var(--red-glow);
        transition: transform 0.15s;
    }
    .btn-submit:active { transform: scale(0.98); }
    
    /* Calendar styles */
    .calendar-container {
        width: 100%;
        max-width: 100%;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        text-align: center;
    }
    .calendar-header-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }
    .day-name {
        font-size: 11px;
        font-weight: 600;
        color: var(--slate);
        padding: 6px 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .day-cell {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s;
        color: var(--text);
        position: relative;
    }
    .day-cell:hover:not(.day-empty):not(.day-selected) {
        background-color: var(--navy-mid);
    }
    .day-empty {
        cursor: default;
        color: #cbd5e1;
    }
    .day-selected {
        background-color: var(--red) !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    .day-in-range {
        background-color: rgba(232,25,44,0.06) !important;
        color: var(--red) !important;
        border-radius: 0;
    }
    .day-range-start {
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
    }
    .day-range-end {
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    .day-disabled {
        color: #cbd5e1;
        cursor: not-allowed;
    }
    .date-preview-box {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 12px;
    }
    .preview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }
    .preview-label {
        color: var(--text-muted);
        font-weight: 500;
    }
    .preview-value {
        font-weight: 700;
        color: var(--text);
    }
</style>

<div class="form-card">
    <form method="POST" action="{{ route('manpower.submissions.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Jenis Pengajuan --}}
        <div class="form-group">
            <label class="form-label">Jenis Pengajuan *</label>
            <div class="type-selector">
                <div>
                    <input type="radio" name="type" id="type-cuti" value="cuti" class="type-option" {{ old('type') === 'cuti' ? 'checked' : '' }}>
                    <label for="type-cuti" class="type-label">
                        <span style="color: var(--blue); margin-bottom: 4px; display: inline-block;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                        <span>Cuti</span>
                    </label>
                </div>
                <div>
                    <input type="radio" name="type" id="type-sakit" value="sakit" class="type-option" {{ old('type') === 'sakit' ? 'checked' : '' }}>
                    <label for="type-sakit" class="type-label">
                        <span style="color: var(--red); margin-bottom: 4px; display: inline-block;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        </span>
                        <span>Sakit</span>
                    </label>
                </div>
                <div>
                    <input type="radio" name="type" id="type-izin" value="izin" class="type-option" {{ old('type') === 'izin' ? 'checked' : '' }}>
                    <label for="type-izin" class="type-label">
                        <span style="color: var(--text-muted); margin-bottom: 4px; display: inline-block;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/>
                            </svg>
                        </span>
                        <span>Izin</span>
                    </label>
                </div>
            </div>
            @error('type')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        {{-- Pilih Tanggal / Rentang Waktu --}}
        <div class="form-group">
            <label class="form-label">Pilih Tanggal / Rentang Waktu *</label>
            <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date') }}">

            <div class="calendar-container">
                <!-- Header -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <button type="button" id="prevMonthBtn" style="background: none; border: 1px solid var(--border); border-radius: 6px; padding: 4px; cursor: pointer; display: flex; align-items: center; color: var(--text-muted);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <span id="currentMonthYear" class="calendar-header-title">Mei 2026</span>
                    <button type="button" id="nextMonthBtn" style="background: none; border: 1px solid var(--border); border-radius: 6px; padding: 4px; cursor: pointer; display: flex; align-items: center; color: var(--text-muted);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                <!-- Grid -->
                <div class="calendar-grid">
                    <div class="day-name">Min</div>
                    <div class="day-name">Sen</div>
                    <div class="day-name">Sel</div>
                    <div class="day-name">Rab</div>
                    <div class="day-name">Kam</div>
                    <div class="day-name">Jum</div>
                    <div class="day-name">Sab</div>
                </div>
                <div id="calendarDays" class="calendar-grid" style="margin-top: 4px;">
                    <!-- Cells generated via JS -->
                </div>
            </div>

            <!-- Preview Box -->
            <div id="datePreviewBox" class="date-preview-box" style="display: none;">
                <div class="preview-row">
                    <span class="preview-label">Tanggal Mulai:</span>
                    <span id="previewStartDate" class="preview-value">-</span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Tanggal Selesai:</span>
                    <span id="previewEndDate" class="preview-value">-</span>
                </div>
                <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                <div class="preview-row" style="font-size: 14px;">
                    <span class="preview-label" style="font-weight: 700; color: var(--text);">Total Hari Izin:</span>
                    <span id="previewTotalDays" style="background: rgba(232, 25, 44, 0.08); color: var(--red); padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 12px;">-</span>
                </div>
            </div>
            @error('start_date')<div class="error-msg">{{ $message }}</div>@enderror
            @error('end_date')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        {{-- Nama Pengajuan --}}
        <div class="form-group">
            <label class="form-label" for="name">Judul Pengajuan *</label>
            <input type="text" id="name" name="name" class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                placeholder="cth: Cuti Lebaran 3 Hari" value="{{ old('name') }}">
            @error('name')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        {{-- Keterangan --}}
        <div class="form-group">
            <label class="form-label" for="description">Keterangan *</label>
            <textarea id="description" name="description" class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                placeholder="Jelaskan alasan pengajuan Anda secara singkat...">{{ old('description') }}</textarea>
            @error('description')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        {{-- Upload File --}}
        <div class="form-group">
            <label class="form-label">Lampiran Dokumen</label>
            <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="file" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this)">
                <div class="file-upload-icon" style="color: var(--text-muted); display: flex; justify-content: center; margin-bottom: 8px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;">
                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                    </svg>
                </div>
                <div class="file-upload-text">Klik untuk upload (PDF/JPG/PNG, maks. 5MB)</div>
                <div class="file-name" id="fileName" style="display:none"></div>
            </div>
            @error('file')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-submit">Kirim Pengajuan</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function showFileName(input) {
    const el = document.getElementById('fileName');
    if (input.files.length > 0) {
        el.textContent = 'File terpilih: ' + input.files[0].name;
        el.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    let currentDate = new Date();
    
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    
    function parseLocalDate(dateStr) {
        if (!dateStr) return null;
        const parts = dateStr.split('-');
        if (parts.length !== 3) return null;
        return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
    }
    
    let startDate = parseLocalDate(startInput.value);
    let endDate = parseLocalDate(endInput.value);

    // If there is an old value, make sure the calendar initializes in that month
    if (startDate) {
        currentDate = new Date(startDate.getFullYear(), startDate.getMonth(), 1);
    }

    const calendarDays = document.getElementById('calendarDays');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const prevMonthBtn = document.getElementById('prevMonthBtn');
    const nextMonthBtn = document.getElementById('nextMonthBtn');
    
    const previewBox = document.getElementById('datePreviewBox');
    const previewStartDate = document.getElementById('previewStartDate');
    const previewEndDate = document.getElementById('previewEndDate');
    const previewTotalDays = document.getElementById('previewTotalDays');

    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Set header
        currentMonthYear.textContent = `${monthNames[month]} ${year}`;

        // Clear days
        calendarDays.innerHTML = '';

        // First day of the month
        const firstDayIndex = new Date(year, month, 1).getDay();

        // Total days in the month
        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        
        // Total days in previous month
        const prevMonthTotalDays = new Date(year, month, 0).getDate();

        // Add empty cells for previous month
        for (let i = firstDayIndex; i > 0; i--) {
            const day = prevMonthTotalDays - i + 1;
            const cell = document.createElement('div');
            cell.className = 'day-cell day-empty text-slate-300';
            cell.textContent = day;
            calendarDays.appendChild(cell);
        }

        // Add days of current month
        for (let i = 1; i <= totalDaysInMonth; i++) {
            const cellDate = new Date(year, month, i);
            const cell = document.createElement('div');
            cell.className = 'day-cell';
            cell.textContent = i;
            
            // Check if selected or in range
            const cellTime = cellDate.setHours(0,0,0,0);
            const startTime = startDate ? new Date(startDate).setHours(0,0,0,0) : null;
            const endTime = endDate ? new Date(endDate).setHours(0,0,0,0) : null;

            if (startTime && cellTime === startTime) {
                cell.classList.add('day-selected');
                if (endTime) cell.classList.add('day-range-start');
            } else if (endTime && cellTime === endTime) {
                cell.classList.add('day-selected');
                cell.classList.add('day-range-end');
            } else if (startTime && endTime && cellTime > startTime && cellTime < endTime) {
                cell.classList.add('day-in-range');
            }

            // Click event
            cell.addEventListener('click', () => {
                handleDateClick(new Date(year, month, i));
            });

            // Hover event (for range preview)
            cell.addEventListener('mouseenter', () => {
                if (startDate && !endDate) {
                    highlightRangeOnHover(new Date(year, month, i));
                }
            });

            calendarDays.appendChild(cell);
        }
        
        updatePreview();
    }

    function handleDateClick(date) {
        if (!startDate || (startDate && endDate)) {
            startDate = date;
            endDate = null;
        } else if (startDate && !endDate) {
            if (date < startDate) {
                startDate = date;
            } else {
                endDate = date;
            }
        }
        
        // Update hidden inputs
        startInput.value = startDate ? formatDateString(startDate) : '';
        endInput.value = endDate ? formatDateString(endDate) : (startDate ? formatDateString(startDate) : '');
        
        renderCalendar();
    }

    function highlightRangeOnHover(hoveredDate) {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const cells = calendarDays.querySelectorAll('.day-cell:not(.day-empty)');
        
        const startTime = new Date(startDate).setHours(0,0,0,0);
        const hoverTime = new Date(hoveredDate).setHours(0,0,0,0);
        
        if (hoverTime < startTime) {
            // Hovering before start date, do normal render
            return;
        }
        
        cells.forEach((cell) => {
            const dayNum = parseInt(cell.textContent);
            const cellDate = new Date(year, month, dayNum);
            const cellTime = cellDate.setHours(0,0,0,0);
            
            // Reset temporary range classes
            cell.classList.remove('day-in-range', 'day-range-start', 'day-range-end');
            
            // Re-apply range styling based on current hover position
            if (cellTime === startTime) {
                if (hoverTime >= startTime) cell.classList.add('day-range-start');
            }
            
            if (cellTime > startTime && cellTime < hoverTime) {
                cell.classList.add('day-in-range');
            } else if (cellTime === hoverTime && hoverTime > startTime) {
                cell.classList.add('day-selected');
                cell.classList.add('day-range-end');
            } else if (cellTime === hoverTime && hoverTime === startTime) {
                // Hovering start date
            } else {
                if (cellTime !== startTime) {
                    cell.classList.remove('day-selected');
                }
            }
        });
    }

    function updatePreview() {
        if (startDate) {
            previewBox.style.display = 'block';
            const formattedStart = formatIndoDate(startDate);
            previewStartDate.textContent = formattedStart;
            
            if (endDate) {
                const formattedEnd = formatIndoDate(endDate);
                previewEndDate.textContent = formattedEnd;
                
                const timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                previewTotalDays.textContent = `${diffDays} Hari`;
            } else {
                previewEndDate.textContent = formattedStart;
                previewTotalDays.textContent = '1 Hari';
            }
        } else {
            previewBox.style.display = 'none';
        }
    }

    function formatDateString(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function formatIndoDate(date) {
        const d = date.getDate();
        const m = monthNames[date.getMonth()];
        const y = date.getFullYear();
        return `${d} ${m} ${y}`;
    }

    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    calendarDays.addEventListener('mouseleave', () => {
        if (startDate && !endDate) {
            renderCalendar();
        }
    });

    renderCalendar();
});
</script>
@endpush
