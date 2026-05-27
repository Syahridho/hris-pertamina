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
</script>
@endpush
