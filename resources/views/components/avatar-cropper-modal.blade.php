{{-- Interactive Avatar Cropper & Position Adjuster Modal --}}
<div id="modal-cropper-avatar" class="modal-backdrop">
    <div class="modal-card" style="max-width: 660px;">
        <div class="modal-header" style="background: linear-gradient(135deg, #0b233e 0%, #1e3a8a 100%);">
            <div class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M6 2v14a2 2 0 0 0 2 2h14"></path>
                    <path d="M18 22V8a2 2 0 0 0-2-2H2"></path>
                </svg>
                Sesuaikan & Potong Foto Profil
            </div>
            <button type="button" class="modal-close-btn" id="btn-close-cropper">&times;</button>
        </div>

        <div class="modal-body" style="padding: 18px 22px;">
            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:6px; padding:10px 14px; margin-bottom:14px; font-size:12px; color:#1e40af; display:flex; align-items:flex-start; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span><strong>Petunjuk:</strong> Geser (drag) gambar untuk menyesuaikan posisi tengah wajah. Gunakan slider zoom atau tombol +/- untuk mengatur ukuran foto agar pas di dalam lingkaran profil dan tidak terpotong.</span>
            </div>

            <div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">
                {{-- Left: Interactive Cropping Canvas --}}
                <div style="flex:1; min-width:320px;">
                    <div style="position:relative; width:340px; height:340px; margin:0 auto; background:#0f172a; border-radius:10px; overflow:hidden; box-shadow:inset 0 0 10px rgba(0,0,0,0.5); cursor:grab;" id="crop-canvas-wrapper">
                        <canvas id="crop-canvas" width="340" height="340" style="display:block; width:100%; height:100%;"></canvas>
                        
                        {{-- Circular Viewport Overlay Indicator --}}
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:240px; height:240px; border-radius:50%; border:2px dashed #ffffff; box-shadow:0 0 0 9999px rgba(15, 23, 42, 0.65); pointer-events:none;">
                            <div style="position:absolute; top:50%; left:0; width:100%; height:1px; background:rgba(255,255,255,0.25);"></div>
                            <div style="position:absolute; top:0; left:50%; width:1px; height:100%; background:rgba(255,255,255,0.25);"></div>
                        </div>
                    </div>

                    {{-- Zoom & Rotate Controls --}}
                    <div style="margin-top:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px;">
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px; font-size:12px; color:#475569; font-weight:600;">
                            <span>Perbesar / Perkecil (Zoom)</span>
                            <span id="zoom-level-text" style="color:#2563eb;">100%</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <button type="button" id="btn-zoom-out" style="width:28px; height:28px; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; cursor:pointer; font-weight:bold; color:#334155; display:flex; align-items:center; justify-content:center;">-</button>
                            <input type="range" id="crop-zoom-range" min="0.2" max="3.5" step="0.02" value="1" style="flex:1; cursor:pointer; accent-color:#2563eb;">
                            <button type="button" id="btn-zoom-in" style="width:28px; height:28px; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; cursor:pointer; font-weight:bold; color:#334155; display:flex; align-items:center; justify-content:center;">+</button>
                            
                            <button type="button" id="btn-rotate-crop" title="Putar 90 Derajat" style="padding:4px 10px; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; cursor:pointer; font-size:12px; color:#334155; display:flex; align-items:center; gap:4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                <span>90°</span>
                            </button>

                            <button type="button" id="btn-reset-crop" title="Reset Posisi & Zoom" style="padding:4px 10px; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; cursor:pointer; font-size:12px; color:#64748b;">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right: Live Circle Previews --}}
                <div style="width:220px; display:flex; flex-direction:column; gap:14px;">
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px; text-align:center;">
                        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:12px;">Hasil Tampilan Profil</div>
                        
                        {{-- Large Profile Preview (Header size) --}}
                        <div style="width:105px; height:105px; border-radius:50%; margin:0 auto; padding:3px; background:linear-gradient(135deg, #1d4ed8, #60a5fa); box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                            <canvas id="crop-preview-large" width="100" height="100" style="width:100%; height:100%; border-radius:50%; display:block; object-fit:cover; background:#ffffff;"></canvas>
                        </div>
                        <div style="font-size:11px; color:#64748b; margin-top:8px; font-weight:500;">Header Profil Karyawan</div>

                        <div style="margin:14px 0 10px; border-top:1px dashed #cbd5e1;"></div>

                        {{-- Small Table Preview --}}
                        <div style="display:flex; align-items:center; justify-content:center; gap:8px;">
                            <canvas id="crop-preview-small" width="40" height="40" style="width:36px; height:36px; border-radius:50%; border:2px solid #2563eb; display:block; object-fit:cover; background:#ffffff;"></canvas>
                            <div style="text-align:left;">
                                <div style="font-size:11px; font-weight:700; color:#0f172a;">Ukuran Tabel</div>
                                <div style="font-size:10px; color:#64748b;">Daftar Direktori</div>
                            </div>
                        </div>
                    </div>

                    <div style="padding:10px 12px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; font-size:11.5px; color:#166534; display:flex; align-items:center; gap:6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Gambar akan disimpan dalam rasio lingkaran proporsional.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
            <button type="button" class="btn btn-outline" id="btn-cancel-crop">Batal / Ganti File</button>
            <button type="button" class="btn btn-primary" id="btn-apply-crop" style="background:#0b233e; padding:9px 20px; font-weight:600; display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Terapkan & Gunakan Foto Ini
            </button>
        </div>
    </div>
</div>
