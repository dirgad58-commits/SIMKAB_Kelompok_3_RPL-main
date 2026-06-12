<!-- ==========================================================================
     TAB 3: APPRAISAL / PENILAIAN KINERJA
     ========================================================================== -->
<section id="panel-kinerja" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="kinerja-search" class="search-input" placeholder="Cari evaluasi kinerja...">
        </div>
        <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
        <button class="btn btn-primary" id="btn-tambah-kinerja">
            <i class="fa-solid fa-star"></i> Input Kinerja (KPI)
        </button>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-kinerja">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Periode</th>
                        <th>Disiplin</th>
                        <th>Kerja Sama</th>
                        <th>Inisiatif</th>
                        <th>Target</th>
                        <th>Skor Akhir</th>
                        <th>Predikat</th>
                        <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Terisi via JS -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ==========================================================================
     MODALS UNTUK KINERJA
     ========================================================================== -->

<!-- 2. Modal Input KPI / Kinerja -->
<div class="modal-overlay" id="modal-kinerja">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-star"></i> Input Penilaian Kinerja Karyawan</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-kinerja">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="kinerja-karyawan">Pilih Karyawan</label>
                        <select id="kinerja-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="kinerja-periode">Periode Evaluasi</label>
                        <select id="kinerja-periode" class="form-control" required>
                            <option value="Q1 2026">Kuartal I 2026</option>
                            <option value="Q2 2026">Kuartal II 2026</option>
                            <option value="Q3 2026">Kuartal III 2026</option>
                            <option value="Q4 2026">Kuartal IV 2026</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kinerja-kedisiplinan">Skor Kedisiplinan (0 - 100)</label>
                        <input type="number" id="kinerja-kedisiplinan" class="form-control" min="0" max="100" placeholder="Skor 0-100" required>
                    </div>
                    <div class="form-group">
                        <label for="kinerja-kerjasama">Skor Kerja Sama (0 - 100)</label>
                        <input type="number" id="kinerja-kerjasama" class="form-control" min="0" max="100" placeholder="Skor 0-100" required>
                    </div>
                    <div class="form-group">
                        <label for="kinerja-inisiatif">Skor Inisiatif (0 - 100)</label>
                        <input type="number" id="kinerja-inisiatif" class="form-control" min="0" max="100" placeholder="Skor 0-100" required>
                    </div>
                    <div class="form-group">
                        <label for="kinerja-target">Pencapaian Target Kerja (0 - 100)</label>
                        <input type="number" id="kinerja-target" class="form-control" min="0" max="100" placeholder="Skor 0-100" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="kinerja-catatan">Catatan HR / Atasan Langsung</label>
                        <textarea id="kinerja-catatan" class="form-control" placeholder="Berikan deskripsi detail kelebihan atau hal-hal yang perlu ditingkatkan karyawan..." style="height: 100px; resize: none;"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-kinerja">Submit Penilaian</button>
        </div>
    </div>
</div>

