// MAP-IN Interactive Features & Charts

document.addEventListener('DOMContentLoaded', () => {
    // 1. Live NIK Search Autocomplete in Sidebar
    const searchInput = document.getElementById('sidebar-nik-search');
    const suggestionsBox = document.getElementById('search-suggestions');

    if (searchInput && suggestionsBox) {
        let debounceTimer;

        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            debounceTimer = setTimeout(() => {
                fetch(`/api/karyawan/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            suggestionsBox.innerHTML = data.map(emp => `
                                <div class="search-suggestion-item" data-nik="${emp.nik}">
                                    <img src="${emp.avatar || '/images/avatar-budi.png'}" alt="${emp.name}" class="sugg-avatar">
                                    <div class="sugg-info">
                                        <div class="sugg-nik">NIK: ${emp.nik}</div>
                                        <div class="sugg-name">${emp.name}</div>
                                        <div class="sugg-dept">${emp.position}</div>
                                    </div>
                                </div>
                            `).join('');
                            suggestionsBox.style.display = 'block';

                            // Bind clicks
                            suggestionsBox.querySelectorAll('.search-suggestion-item').forEach(item => {
                                item.addEventListener('click', () => {
                                    const nik = item.getAttribute('data-nik');
                                    // Get current tab from URL if present
                                    const currentUrl = window.location.pathname;
                                    const parts = currentUrl.split('/');
                                    const tab = (parts.length >= 4) ? parts[3] : 'profil-individu';
                                    window.location.href = `/karyawan/${nik}/${tab}`;
                                });
                            });
                        } else {
                            suggestionsBox.innerHTML = `
                                <div style="padding: 10px; font-size: 11.5px; color: #64748b; text-align: center;">
                                    Tidak ditemukan karyawan dengan NIK / Nama "${query}"
                                </div>
                            `;
                            suggestionsBox.style.display = 'block';
                        }
                    })
                    .catch(() => {
                        suggestionsBox.style.display = 'none';
                    });
            }, 250);
        });

        // Close suggestion box when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });

        // Show suggestions on focus if query exists
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim().length > 0) {
                searchInput.dispatchEvent(new Event('input'));
            }
        });
    }

    // 2. Client-side Filter for Training History Table
    const searchTrainingInput = document.getElementById('table-search-training');
    const filterYear = document.getElementById('filter-year');
    const filterCategory = document.getElementById('filter-category');
    const filterType = document.getElementById('filter-type');
    const trainingTable = document.getElementById('training-table-body');
    const trainingCountInfo = document.getElementById('training-table-count-info');

    if (trainingTable && (searchTrainingInput || filterYear || filterCategory || filterType)) {
        const filterRows = () => {
            const query = searchTrainingInput ? searchTrainingInput.value.toLowerCase().trim() : '';
            const yr = filterYear ? filterYear.value : 'all';
            const cat = filterCategory ? filterCategory.value : 'all';
            const type = filterType ? filterType.value : 'all';

            const rows = trainingTable.querySelectorAll('tr:not(.empty-filter-row)');
            let visibleCount = 0;
            let visibleDuration = 0;
            let totalRowsCount = 0;

            rows.forEach(row => {
                // Skip if it's the server-side empty message row
                if (row.querySelector('td[colspan]')) return;
                totalRowsCount++;

                const text = row.innerText.toLowerCase();
                const rowYear = (row.getAttribute('data-year') || '').trim();
                const rowCat = (row.getAttribute('data-category') || '').trim();
                const rowType = (row.getAttribute('data-type') || '').trim();
                const rowDur = parseFloat(row.getAttribute('data-duration') || '0');

                const matchesSearch = !query || text.includes(query);
                const matchesYear = (yr === 'all' || rowYear === yr);
                const matchesCat = (cat === 'all' || rowCat === cat);
                const matchesType = (type === 'all' || rowType === type);

                if (matchesSearch && matchesYear && matchesCat && matchesType) {
                    row.style.display = '';
                    visibleCount++;
                    visibleDuration += isNaN(rowDur) ? 0 : rowDur;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update info text
            if (trainingCountInfo) {
                const formattedDur = (visibleDuration % 1 === 0) ? visibleDuration : visibleDuration.toFixed(1);
                if (yr === 'all' && cat === 'all' && type === 'all' && !query) {
                    trainingCountInfo.textContent = `Total ${visibleCount} pelatihan (${formattedDur} jam)`;
                } else {
                    trainingCountInfo.textContent = `Menampilkan ${visibleCount} dari ${totalRowsCount} pelatihan (${formattedDur} jam)`;
                }
            }

            // Handle empty search / filter state
            let emptyMsgRow = trainingTable.querySelector('.empty-filter-row');
            if (visibleCount === 0 && totalRowsCount > 0) {
                if (!emptyMsgRow) {
                    emptyMsgRow = document.createElement('tr');
                    emptyMsgRow.className = 'empty-filter-row';
                    emptyMsgRow.innerHTML = '<td colspan="9" class="text-center text-muted" style="padding: 24px;">Tidak ada pelatihan yang cocok dengan filter yang dipilih.</td>';
                    trainingTable.appendChild(emptyMsgRow);
                }
                emptyMsgRow.style.display = '';
            } else if (emptyMsgRow) {
                emptyMsgRow.style.display = 'none';
            }
        };

        if (searchTrainingInput) searchTrainingInput.addEventListener('input', filterRows);
        if (filterYear) filterYear.addEventListener('change', filterRows);
        if (filterCategory) filterCategory.addEventListener('change', filterRows);
        if (filterType) filterType.addEventListener('change', filterRows);
    }

    // Global Modal helper functions
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('show');
        }
    };

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('show');
        }
    };

    // 3. Modal Dialog Triggers & Handlers
    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = btn.getAttribute('data-modal-target');
            const modal = document.getElementById(targetId);
            if (modal) {
                // If a modal is already open (nested modal / modal opened from inside another modal),
                // ensure the newly opened modal stacks on top with a higher z-index!
                const openModals = Array.from(document.querySelectorAll('.modal-backdrop.show'));
                if (openModals.length > 0) {
                    let maxZ = 2000;
                    openModals.forEach(m => {
                        const z = parseInt(window.getComputedStyle(m).zIndex, 10);
                        if (!isNaN(z) && z > maxZ) maxZ = z;
                    });
                    modal.style.zIndex = (maxZ + 100);
                }
                modal.classList.add('show');
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-backdrop');
            if (modal) {
                modal.classList.remove('show');
                if (modal.id !== 'modal-kelola-performance') {
                    modal.style.zIndex = '';
                }
            }
        });
    });

    // Close modal when clicking on backdrop outside modal card
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.classList.remove('show');
                if (backdrop.id !== 'modal-kelola-performance') {
                    backdrop.style.zIndex = '';
                }
            }
        });
    });

    // 4. Pre-fill Edit Career History Modal
    const selectEditKarir = document.getElementById('edit-select-karir');
    const formEditKarir = document.getElementById('form-edit-karir');
    if (selectEditKarir && formEditKarir) {
        const updateEditFields = () => {
            const opt = selectEditKarir.selectedOptions[0];
            if (!opt) return;

            const id = opt.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formEditKarir.action = `/karyawan/${currentNik}/career-history/${id}`;

            // Effective date
            const rawDate = opt.getAttribute('data-date') || '';
            setMonthYearPair('edit-karir-month', 'edit-karir-year', 'edit-karir-date', rawDate);

            // Department
            const deptEl = document.getElementById('edit-dept');
            if (deptEl) deptEl.value = opt.getAttribute('data-dept') || '';

            // Position
            const editPosEl = document.getElementById('edit-pos');
            if (editPosEl) {
                const rawPos = (opt.getAttribute('data-pos') || '').trim();
                editPosEl.value = rawPos;
                if (!editPosEl.value && rawPos) {
                    let matched = false;
                    for (let i = 0; i < editPosEl.options.length; i++) {
                        if (editPosEl.options[i].value.toLowerCase() === rawPos.toLowerCase()) {
                            editPosEl.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                    if (!matched) {
                        const optEl = document.createElement('option');
                        optEl.value = rawPos;
                        optEl.textContent = rawPos;
                        optEl.selected = true;
                        editPosEl.appendChild(optEl);
                    }
                }
            }

            // Job Class & Grade
            const jcEl = document.getElementById('edit-karir-jobclass');
            const grEl = document.getElementById('edit-karir-grade');
            let jcVal = (opt.getAttribute('data-jobclass') || '').trim();
            let grVal = (opt.getAttribute('data-grade') || '').trim();

            if (!jcVal || !grVal) {
                const fullGrade = (opt.getAttribute('data-gradefull') || '').trim();
                if (fullGrade.includes('/')) {
                    const parts = fullGrade.split('/');
                    if (!jcVal) jcVal = parts[0].trim();
                    if (!grVal) grVal = parts[1].trim();
                }
            }

            if (jcEl) {
                let matchedJc = false;
                for (let i = 0; i < jcEl.options.length; i++) {
                    const optValNorm = jcEl.options[i].value.replace(/\s+/g, '').toUpperCase();
                    const testValNorm = jcVal.replace(/\s+/g, '').toUpperCase().replace(/^JC/i, 'KJ');
                    if (optValNorm === testValNorm || optValNorm === jcVal.replace(/\s+/g, '').toUpperCase()) {
                        jcEl.selectedIndex = i;
                        matchedJc = true;
                        break;
                    }
                }
                if (!matchedJc && jcVal) {
                    const optEl = document.createElement('option');
                    optEl.value = jcVal;
                    optEl.textContent = jcVal;
                    optEl.selected = true;
                    jcEl.appendChild(optEl);
                }

                if (typeof jcEl._updateGrades === 'function') {
                    jcEl._updateGrades(grVal);
                } else if (grEl) {
                    grEl.value = grVal;
                }
            } else if (grEl) {
                grEl.value = grVal;
            }

            // Change Type & Notes
            const typeEl = document.getElementById('edit-type');
            if (typeEl) typeEl.value = opt.getAttribute('data-type') || 'Kenaikan Pangkat Reguler';
            const notesEl = document.getElementById('edit-notes');
            if (notesEl) notesEl.value = opt.getAttribute('data-notes') || '';
        };

        selectEditKarir.addEventListener('change', updateEditFields);
        updateEditFields();
    }

    // 5. Pre-fill Delete Career History Modal
    const selectDeleteKarir = document.getElementById('delete-select-karir');
    const formDeleteKarir = document.getElementById('form-hapus-karir');
    if (selectDeleteKarir && formDeleteKarir) {
        const updateDeleteAction = () => {
            const id = selectDeleteKarir.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formDeleteKarir.action = `/karyawan/${currentNik}/career-history/${id}`;
        };

        selectDeleteKarir.addEventListener('change', updateDeleteAction);
        updateDeleteAction();
    }

    // 5.1 Training History Date Range Helpers & Listeners
    const idTrainingMonths = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const formatIndonesianDateRange = (startDateStr, endDateStr) => {
        if (!startDateStr) return '';
        const startParts = startDateStr.split('-');
        if (startParts.length < 3) return startDateStr;
        const sYear = parseInt(startParts[0], 10);
        const sMonth = parseInt(startParts[1], 10);
        const sDay = parseInt(startParts[2], 10);

        if (!endDateStr || startDateStr === endDateStr) {
            return `${sDay} ${idTrainingMonths[sMonth]} ${sYear}`;
        }

        const endParts = endDateStr.split('-');
        if (endParts.length < 3) return `${sDay} ${idTrainingMonths[sMonth]} ${sYear}`;
        const eYear = parseInt(endParts[0], 10);
        const eMonth = parseInt(endParts[1], 10);
        const eDay = parseInt(endParts[2], 10);

        if (sYear === eYear && sMonth === eMonth) {
            return `${sDay} – ${eDay} ${idTrainingMonths[sMonth]} ${sYear}`;
        } else if (sYear === eYear) {
            return `${sDay} ${idTrainingMonths[sMonth]} – ${eDay} ${idTrainingMonths[eMonth]} ${sYear}`;
        } else {
            return `${sDay} ${idTrainingMonths[sMonth]} ${sYear} – ${eDay} ${idTrainingMonths[eMonth]} ${eYear}`;
        }
    };

    const setupTrainingDateRange = (startId, endId, previewId, textId, hiddenId) => {
        const startEl = document.getElementById(startId);
        const endEl = document.getElementById(endId);
        const previewEl = document.getElementById(previewId);
        const textEl = document.getElementById(textId);
        const hiddenEl = document.getElementById(hiddenId);

        if (!startEl || !previewEl || !textEl || !hiddenEl) return;

        const updatePreview = () => {
            const startVal = startEl.value;
            const endVal = endEl ? endEl.value : '';
            if (startVal) {
                const formatted = formatIndonesianDateRange(startVal, endVal);
                textEl.textContent = formatted;
                hiddenEl.value = formatted;
                previewEl.style.display = 'block';
            } else {
                textEl.textContent = '';
                hiddenEl.value = '';
                previewEl.style.display = 'none';
            }
        };

        startEl.addEventListener('input', updatePreview);
        startEl.addEventListener('change', updatePreview);
        if (endEl) {
            endEl.addEventListener('input', updatePreview);
            endEl.addEventListener('change', updatePreview);
        }
    };

    setupTrainingDateRange('add-training-start-date', 'add-training-end-date', 'add-training-date-preview', 'add-training-date-preview-text', 'add-training-date');
    setupTrainingDateRange('edit-training-start-date', 'edit-training-end-date', 'edit-training-date-preview', 'edit-training-date-preview-text', 'edit-training-date');

    // 5.2 Pre-fill Edit Training History Modal
    const selectEditPelatihan = document.getElementById('edit-select-pelatihan');
    const formEditPelatihan = document.getElementById('form-edit-pelatihan');
    if (selectEditPelatihan && formEditPelatihan) {
        const updateEditPelatihanFields = () => {
            const opt = selectEditPelatihan.selectedOptions[0];
            if (!opt) return;

            const id = opt.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formEditPelatihan.action = `/karyawan/${currentNik}/training-history/${id}`;

            const nameEl = document.getElementById('edit-training-name');
            if (nameEl) nameEl.value = opt.getAttribute('data-name') || '';

            const startEl = document.getElementById('edit-training-start-date');
            const endEl = document.getElementById('edit-training-end-date');
            const startDate = opt.getAttribute('data-start') || '';
            const endDate = opt.getAttribute('data-end') || '';
            if (startEl) startEl.value = startDate;
            if (endEl) endEl.value = endDate;

            // Trigger date preview update
            const previewEl = document.getElementById('edit-training-date-preview');
            const textEl = document.getElementById('edit-training-date-preview-text');
            const hiddenEl = document.getElementById('edit-training-date');
            const rawFormatted = opt.getAttribute('data-date') || '';
            if (startDate) {
                const formatted = formatIndonesianDateRange(startDate, endDate);
                if (textEl) textEl.textContent = formatted;
                if (hiddenEl) hiddenEl.value = formatted;
                if (previewEl) previewEl.style.display = 'block';
            } else if (rawFormatted) {
                if (textEl) textEl.textContent = rawFormatted;
                if (hiddenEl) hiddenEl.value = rawFormatted;
                if (previewEl) previewEl.style.display = 'block';
            } else {
                if (previewEl) previewEl.style.display = 'none';
            }

            const catEl = document.getElementById('edit-training-category');
            if (catEl) catEl.value = opt.getAttribute('data-cat') || 'Functional';

            const typeEl = document.getElementById('edit-training-type');
            if (typeEl) typeEl.value = opt.getAttribute('data-type') || 'Classroom';

            const orgEl = document.getElementById('edit-training-organizer');
            if (orgEl) orgEl.value = opt.getAttribute('data-org') || '';

            const durEl = document.getElementById('edit-training-duration');
            if (durEl) durEl.value = opt.getAttribute('data-dur') || '0';

            const notesEl = document.getElementById('edit-training-notes');
            if (notesEl) notesEl.value = opt.getAttribute('data-notes') || '';

            // Documentation preview & remove checkbox
            const docPath = opt.getAttribute('data-doc') || '';
            const docCurrentEl = document.getElementById('edit-training-doc-current');
            const removeWrapEl = document.getElementById('edit-training-remove-doc-wrap');
            const removeCheckbox = document.getElementById('edit-training-remove-doc');
            if (removeCheckbox) removeCheckbox.checked = false;

            if (docPath && docCurrentEl) {
                const isImg = opt.getAttribute('data-is-image') === '1';
                const isPdf = opt.getAttribute('data-is-pdf') === '1';
                let iconHtml = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>';
                if (isImg) {
                    iconHtml = `<img src="${docPath}" style="width:20px; height:20px; object-fit:cover; border-radius:3px; border:1px solid #93c5fd; display:inline-block; vertical-align:middle;">`;
                } else if (isPdf) {
                    iconHtml = '<span style="color:#dc2626; font-weight:700;">[PDF]</span>';
                }

                docCurrentEl.innerHTML = `
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                        <span style="display:flex; align-items:center; gap:6px;">
                            ${iconHtml}
                            <span style="font-weight:600; color:#1e293b;">File terlampir saat ini</span>
                        </span>
                        <a href="${docPath}" target="_blank" rel="noopener noreferrer" style="font-weight:700; color:#2563eb; text-decoration:underline;">Lihat File &nearr;</a>
                    </div>
                `;
                docCurrentEl.style.display = 'block';
                if (removeWrapEl) removeWrapEl.style.display = 'flex';
            } else if (docCurrentEl) {
                docCurrentEl.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Belum ada berkas dokumentasi yang diunggah.</span>';
                docCurrentEl.style.display = 'block';
                if (removeWrapEl) removeWrapEl.style.display = 'none';
            }
        };

        selectEditPelatihan.addEventListener('change', updateEditPelatihanFields);
        updateEditPelatihanFields();
    }

    // 5.3 Pre-fill Delete Training History Modal
    const selectDeletePelatihan = document.getElementById('delete-select-pelatihan');
    const formDeletePelatihan = document.getElementById('form-hapus-pelatihan');
    if (selectDeletePelatihan && formDeletePelatihan) {
        const updateDeletePelatihanAction = () => {
            const id = selectDeletePelatihan.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formDeletePelatihan.action = `/karyawan/${currentNik}/training-history/${id}`;
        };

        selectDeletePelatihan.addEventListener('change', updateDeletePelatihanAction);
        updateDeletePelatihanAction();
    }

    // 5.4 Pre-fill Edit Certification Modal
    const selectEditSertifikasi = document.getElementById('edit-select-sertifikasi');
    const formEditSertifikasi = document.getElementById('form-edit-sertifikasi');
    if (selectEditSertifikasi && formEditSertifikasi) {
        const updateEditSertifikasiFields = () => {
            const opt = selectEditSertifikasi.selectedOptions[0];
            if (!opt) return;

            const id = opt.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formEditSertifikasi.action = `/karyawan/${currentNik}/certification/${id}`;

            const nameEl = document.getElementById('edit-cert-name');
            if (nameEl) nameEl.value = opt.getAttribute('data-name') || '';

            const issuerEl = document.getElementById('edit-cert-issuer');
            if (issuerEl) issuerEl.value = opt.getAttribute('data-issuer') || '';

            const obtainedEl = document.getElementById('edit-cert-obtained');
            if (obtainedEl) obtainedEl.value = opt.getAttribute('data-obtained') || '';

            const validEl = document.getElementById('edit-cert-valid');
            if (validEl) validEl.value = opt.getAttribute('data-valid') || '';

            const activeEl = document.getElementById('edit-cert-active');
            if (activeEl) activeEl.checked = (opt.getAttribute('data-active') === '1');

            // Documentation preview & remove checkbox
            const docPath = opt.getAttribute('data-doc') || '';
            const docCurrentEl = document.getElementById('edit-cert-doc-current');
            const removeWrapEl = document.getElementById('edit-cert-remove-doc-wrap');
            const removeCheckbox = document.getElementById('edit-cert-remove-doc');
            const fileInput = document.getElementById('edit-cert-doc-file');
            if (fileInput) fileInput.value = '';
            if (removeCheckbox) removeCheckbox.checked = false;

            if (docPath && docCurrentEl) {
                const isImg = opt.getAttribute('data-is-image') === '1';
                const isPdf = opt.getAttribute('data-is-pdf') === '1';
                let iconHtml = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>';
                if (isImg) {
                    iconHtml = `<img src="${docPath}" style="width:20px; height:20px; object-fit:cover; border-radius:3px; border:1px solid #93c5fd; display:inline-block; vertical-align:middle;">`;
                } else if (isPdf) {
                    iconHtml = '<span style="color:#dc2626; font-weight:700;">[PDF]</span>';
                }

                docCurrentEl.innerHTML = `
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                        <span style="display:flex; align-items:center; gap:6px;">
                            ${iconHtml}
                            <span style="font-weight:600; color:#1e293b;">Dokumen saat ini</span>
                        </span>
                        <a href="${docPath}" target="_blank" rel="noopener noreferrer" style="font-weight:700; color:#2563eb; text-decoration:underline;">Lihat Dokumen &nearr;</a>
                    </div>
                `;
                docCurrentEl.style.display = 'block';
                if (removeWrapEl) removeWrapEl.style.display = 'flex';
            } else if (docCurrentEl) {
                docCurrentEl.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Belum ada dokumen sertifikat yang diunggah.</span>';
                docCurrentEl.style.display = 'block';
                if (removeWrapEl) removeWrapEl.style.display = 'none';
            }
        };

        selectEditSertifikasi.addEventListener('change', updateEditSertifikasiFields);
        updateEditSertifikasiFields();
    }

    // 5.5 Pre-fill Delete Certification Modal
    const selectDeleteSertifikasi = document.getElementById('delete-select-sertifikasi');
    const formDeleteSertifikasi = document.getElementById('form-hapus-sertifikasi');
    if (selectDeleteSertifikasi && formDeleteSertifikasi) {
        const updateDeleteSertifikasiAction = () => {
            const id = selectDeleteSertifikasi.value;
            const currentNik = window.location.pathname.split('/')[2] || '012345';
            formDeleteSertifikasi.action = `/karyawan/${currentNik}/certification/${id}`;
        };

        selectDeleteSertifikasi.addEventListener('change', updateDeleteSertifikasiAction);
        updateDeleteSertifikasiAction();
    }

    // 6. Sub-tab Switcher for ICP (D1 vs D2)
    const subtabs = document.querySelectorAll('[data-subtab]');
    const subtabContents = document.querySelectorAll('[data-subtab-content]');
    if (subtabs.length > 0 && subtabContents.length > 0) {
        subtabs.forEach(tabBtn => {
            tabBtn.addEventListener('click', () => {
                const target = tabBtn.getAttribute('data-subtab');

                subtabs.forEach(t => {
                    t.style.borderBottom = '3px solid transparent';
                    t.style.color = '#64748b';
                });
                tabBtn.style.borderBottom = '3px solid #0056b3';
                tabBtn.style.color = '#0b2545';

                subtabContents.forEach(c => {
                    if (c.getAttribute('data-subtab-content') === target || target === 'all') {
                        c.style.display = '';
                    } else {
                        c.style.display = 'none';
                    }
                });
            });
        });
    }

    // 7. Toggle Tampilkan Lebih Banyak (Pelatihan)
    const btnShowMore = document.getElementById('btn-show-more-trainings');
    if (btnShowMore && trainingTable) {
        btnShowMore.addEventListener('click', () => {
            alert('Semua riwayat pelatihan telah dimuat.');
        });
    }

    // 8. Topbar User Dropdown Menu
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userDropdownMenu = document.getElementById('user-dropdown-menu');

    if (userMenuBtn && userDropdownMenu) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!userMenuBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('show');
            }
        });
    }

    // 9. Super Admin: Edit Karyawan Handlers
    document.querySelectorAll('.btn-edit-karyawan').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const nik = btn.getAttribute('data-nik');
            const name = btn.getAttribute('data-name') || '';
            const position = btn.getAttribute('data-position') || '';
            const department = btn.getAttribute('data-department') || '';
            const section = btn.getAttribute('data-section') || '';
            const education = btn.getAttribute('data-education') || '';
            const age = btn.getAttribute('data-age') || '';
            const tenure = btn.getAttribute('data-tenure') || '';
            const jobclass = btn.getAttribute('data-jobclass') || '';
            const grade = btn.getAttribute('data-grade') || '';
            const gradeSince = btn.getAttribute('data-grade-since') || '';
            const positionSince = btn.getAttribute('data-position-since') || '';

            const form = document.getElementById('form-edit-karyawan');
            if (form) {
                form.action = `/karyawan/${nik}`;
            }

            const nikDisplay = document.getElementById('edit-emp-nik-display');
            if (nikDisplay) nikDisplay.value = nik;

            const nameInput = document.getElementById('edit-emp-name');
            if (nameInput) nameInput.value = name;

            const posInput = document.getElementById('edit-emp-position');
            if (posInput) {
                const rawPos = (position || '').trim();
                posInput.value = rawPos;
                if (!posInput.value && rawPos) {
                    let matched = false;
                    for (let i = 0; i < posInput.options.length; i++) {
                        if (posInput.options[i].value.toLowerCase() === rawPos.toLowerCase()) {
                            posInput.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                    if (!matched) {
                        const optEl = document.createElement('option');
                        optEl.value = rawPos;
                        optEl.textContent = rawPos;
                        optEl.selected = true;
                        posInput.appendChild(optEl);
                    }
                }
            }

            const deptInput = document.getElementById('edit-emp-department');
            if (deptInput) deptInput.value = department;

            const secInput = document.getElementById('edit-emp-section');
            if (secInput) secInput.value = section;

            const eduInput = document.getElementById('edit-emp-education');
            if (eduInput) eduInput.value = education;

            const ageInput = document.getElementById('edit-emp-age');
            if (ageInput) ageInput.value = age;

            const tenInput = document.getElementById('edit-emp-tenure');
            if (tenInput) tenInput.value = tenure;

            const jcInput = document.getElementById('edit-emp-jobclass');
            const gradeInput = document.getElementById('edit-emp-grade');
            if (jcInput) {
                const rawJc = (jobclass || '').trim();
                jcInput.value = rawJc;
                if (!jcInput.value && rawJc) {
                    let matched = false;
                    for (let i = 0; i < jcInput.options.length; i++) {
                        const optVal = jcInput.options[i].value.replace(/\s+/g, '').toUpperCase();
                        const checkVal = rawJc.replace(/\s+/g, '').toUpperCase();
                        if (optVal === checkVal || optVal === checkVal.replace('JC', 'KJ')) {
                            jcInput.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                    if (!matched) {
                        const optEl = document.createElement('option');
                        optEl.value = rawJc;
                        optEl.textContent = `${rawJc} (Data Saat Ini)`;
                        optEl.selected = true;
                        jcInput.appendChild(optEl);
                    }
                }
            }

            if (gradeInput) {
                const rawGrade = (grade || '').trim();
                if (jcInput && typeof jcInput._updateGrades === 'function') {
                    jcInput._updateGrades(rawGrade);
                } else {
                    gradeInput.value = rawGrade;
                }
            }

            setMonthYearPair('edit-emp-grade-month', 'edit-emp-grade-year', 'edit-emp-grade-since', gradeSince);
            setMonthYearPair('edit-emp-pos-month', 'edit-emp-pos-year', 'edit-emp-position-since', positionSince);

            window.openModal('modal-edit-karyawan');
        });
    });

    // Helper to sync Month & Year dropdowns with a hidden string input e.g. "April 2026"
    function bindMonthYearPair(monthSelectId, yearSelectId, hiddenInputId) {
        const mEl = document.getElementById(monthSelectId);
        const yEl = document.getElementById(yearSelectId);
        const hEl = document.getElementById(hiddenInputId);
        if (!mEl || !yEl || !hEl) return;

        const updateHidden = () => {
            const m = (mEl.value || '').trim();
            const y = (yEl.value || '').trim();
            if (m && y) {
                hEl.value = `${m} ${y}`;
            } else if (y) {
                hEl.value = y;
            } else if (m) {
                hEl.value = m;
            } else {
                hEl.value = '';
            }
        };

        mEl.addEventListener('change', updateHidden);
        yEl.addEventListener('change', updateHidden);
    }

    function setMonthYearPair(monthSelectId, yearSelectId, hiddenInputId, val) {
        const mEl = document.getElementById(monthSelectId);
        const yEl = document.getElementById(yearSelectId);
        const hEl = document.getElementById(hiddenInputId);
        if (!mEl || !yEl || !hEl) return;

        hEl.value = val ? val.trim() : '';
        mEl.value = '';
        yEl.value = '';

        if (!val) return;
        const raw = val.trim();

        // 1. Match Year (4-digit or 2-digit)
        let yVal = null;
        const yMatch = raw.match(/\b(19\d\d|20\d\d)\b/);
        if (yMatch) {
            yVal = yMatch[1];
        } else {
            // Check 2-digit year (e.g. Apr-26 -> 2026, Apr-09 -> 2009, 98 -> 1998)
            const y2Match = raw.match(/(?:^|[^\d])(\d{2})(?:[^\d]|$)/);
            if (y2Match) {
                const y2 = parseInt(y2Match[1], 10);
                yVal = (y2 > 50 ? 1900 + y2 : 2000 + y2).toString();
            }
        }

        if (yVal) {
            let foundY = false;
            for (let i = 0; i < yEl.options.length; i++) {
                if (yEl.options[i].value === yVal) {
                    yEl.value = yVal;
                    foundY = true;
                    break;
                }
            }
            if (!foundY) {
                const opt = document.createElement('option');
                opt.value = yVal;
                opt.textContent = yVal;
                opt.selected = true;
                yEl.appendChild(opt);
            }
        }

        // 2. Match Month
        const months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const monthAliases = {
            'jan': 'Januari', 'january': 'Januari',
            'feb': 'Februari', 'february': 'Februari',
            'mar': 'Maret', 'march': 'Maret',
            'apr': 'April', 'april': 'April',
            'may': 'Mei', 'mei': 'Mei',
            'jun': 'Juni', 'june': 'Juni',
            'jul': 'Juli', 'july': 'Juli',
            'aug': 'Agustus', 'august': 'Agustus', 'agt': 'Agustus', 'agustus': 'Agustus',
            'sep': 'September', 'sept': 'September', 'september': 'September',
            'oct': 'Oktober', 'okt': 'Oktober', 'october': 'Oktober', 'oktober': 'Oktober',
            'nov': 'November', 'november': 'November',
            'dec': 'Desember', 'des': 'Desember', 'december': 'Desember', 'desember': 'Desember'
        };

        const words = raw.split(/[\s,/-]+/);
        for (const w of words) {
            const lower = w.toLowerCase();
            const foundExact = months.find(m => m.toLowerCase() === lower);
            if (foundExact) {
                mEl.value = foundExact;
                break;
            } else if (monthAliases[lower]) {
                mEl.value = monthAliases[lower];
                break;
            }
        }

        if (mEl.value && yEl.value) {
            hEl.value = `${mEl.value} ${yEl.value}`;
        }
    }

    bindMonthYearPair('add-emp-grade-month', 'add-emp-grade-year', 'add-emp-grade-since');
    bindMonthYearPair('add-emp-pos-month', 'add-emp-pos-year', 'add-emp-position-since');
    bindMonthYearPair('edit-emp-grade-month', 'edit-emp-grade-year', 'edit-emp-grade-since');
    bindMonthYearPair('edit-emp-pos-month', 'edit-emp-pos-year', 'edit-emp-position-since');
    bindMonthYearPair('add-karir-month', 'add-karir-year', 'add-karir-date');
    bindMonthYearPair('edit-karir-month', 'edit-karir-year', 'edit-karir-date');

    const formAddKarir = document.querySelector('#modal-tambah-karir form');
    if (formAddKarir) {
        formAddKarir.addEventListener('submit', () => {
            const m = document.getElementById('add-karir-month')?.value;
            const y = document.getElementById('add-karir-year')?.value;
            if (m && y) {
                document.getElementById('add-karir-date').value = `${m} ${y}`;
            }
        });
    }
    const formEditKarirSubmit = document.getElementById('form-edit-karir');
    if (formEditKarirSubmit) {
        formEditKarirSubmit.addEventListener('submit', () => {
            const m = document.getElementById('edit-karir-month')?.value;
            const y = document.getElementById('edit-karir-year')?.value;
            if (m && y) {
                document.getElementById('edit-karir-date').value = `${m} ${y}`;
            }
        });
    }

    // 9.1 Dynamic Job Class (KJ) & Sub Golongan (Grade) Chaining
    const kjSubGolMap = {
        'KJ 1':  ['1 A', '2 A'],
        'KJ 2':  ['1 E', '2 E'],
        'KJ 3':  ['2 B', '3 B'],
        'KJ 4':  ['2 E', '3 D'],
        'KJ 5':  ['3 B', '3 F'],
        'KJ 6':  ['3 E', '4 B'],
        'KJ 7':  ['4 A', '4 D'],
        'KJ 8':  ['4 E', '4 F'],
        'KJ 9':  ['5 A', '5 B'],
        'KJ 10': ['5 C', '5 D'],
        'KJ 11': ['6 A', '6 B'],
        'KJ 12': ['6 C', '6 D'],
        'KJ 13': ['7 A', '7 B'],
        'KJ 14': ['7 C', '7 D']
    };

    function setupKjGradePair(jcId, gradeId) {
        const jcEl = document.getElementById(jcId);
        const grEl = document.getElementById(gradeId);
        if (!jcEl || !grEl) return;

        const updateGradeOptions = (targetGradeToSelect = null) => {
            const rawJc = (jcEl.value || '').trim();
            let selectedKJ = rawJc;
            if (/^JC\s*\d+/i.test(rawJc)) {
                selectedKJ = 'KJ ' + rawJc.replace(/^JC\s*/i, '');
            } else if (/^KJ\d+/i.test(rawJc)) {
                selectedKJ = 'KJ ' + rawJc.replace(/^KJ/i, '');
            }

            const validGrades = kjSubGolMap[selectedKJ];
            const currentSelected = targetGradeToSelect !== null ? targetGradeToSelect : (grEl.value || '').trim();

            grEl.innerHTML = '';
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = '-- Pilih Grade --';
            grEl.appendChild(defaultOpt);

            if (validGrades && validGrades.length > 0) {
                validGrades.forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g;
                    opt.textContent = g;
                    grEl.appendChild(opt);
                });

                if (currentSelected) {
                    let matched = false;
                    for (let i = 1; i < grEl.options.length; i++) {
                        const optValNorm = grEl.options[i].value.replace(/\s+/g, '').toUpperCase();
                        const checkValNorm = currentSelected.replace(/\s+/g, '').toUpperCase();
                        if (optValNorm === checkValNorm) {
                            grEl.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                    if (!matched && currentSelected) {
                        const legacyOpt = document.createElement('option');
                        legacyOpt.value = currentSelected;
                        legacyOpt.textContent = `${currentSelected} (Data Saat Ini)`;
                        legacyOpt.selected = true;
                        grEl.appendChild(legacyOpt);
                    }
                }
            }
        };

        jcEl.addEventListener('change', () => {
            updateGradeOptions();
        });

        jcEl._updateGrades = updateGradeOptions;

        // Initialize state
        updateGradeOptions(grEl.value);
    }

    setupKjGradePair('add-emp-jobclass', 'add-emp-grade');
    setupKjGradePair('edit-emp-jobclass', 'edit-emp-grade');
    setupKjGradePair('add-d2-jobclass', 'add-d2-grade');
    setupKjGradePair('edit-d2-class', 'edit-d2-grade');
    setupKjGradePair('add-karir-jobclass', 'add-karir-grade');
    setupKjGradePair('edit-karir-jobclass', 'edit-karir-grade');

    // 9.2 Dynamic HAV 16 Box Auto-Resize with Card C4 (C3 stretches and matrix expands)
    function syncHavMatrixSize() {
        const c3Card = document.getElementById('card-c3-hav-box');
        const c4Card = document.getElementById('card-c4-strength');
        const container = document.querySelector('.hav-matrix-container');
        const gridBox = document.getElementById('hav-matrix-grid-box');
        if (!c3Card || !container || !gridBox) return;

        // On mobile / small tablet (single column stack), keep clean standard size
        if (window.innerWidth <= 992) {
            gridBox.style.width = '216px';
            gridBox.style.height = '216px';
            gridBox.style.setProperty('--hav-box-font', '18px');
            return;
        }

        // Available area inside the flex container in Card C3
        const availW = container.clientWidth || 300;
        const availH = container.clientHeight || 220;

        // Calculate maximum square that fits comfortably without overflowing
        const paddingOffset = 12;
        let targetSize = Math.floor(Math.min(availW - paddingOffset, availH - paddingOffset));
        if (targetSize < 216) targetSize = 216;

        gridBox.style.width = `${targetSize}px`;
        gridBox.style.height = `${targetSize}px`;

        // Proportionally scale cell font size for numbers 1..16
        const fontSize = Math.max(17, Math.min(36, Math.round(targetSize / 11)));
        gridBox.style.setProperty('--hav-box-font', `${fontSize}px`);
    }

    // Trigger initial calculation
    syncHavMatrixSize();
    window.addEventListener('resize', syncHavMatrixSize);

    // Watch for dynamic DOM changes (e.g., adding/editing/deleting key strengths in C4)
    if (typeof ResizeObserver !== 'undefined') {
        const havResizeObserver = new ResizeObserver(() => {
            syncHavMatrixSize();
        });
        const c4CardEl = document.getElementById('card-c4-strength');
        const c3CardEl = document.getElementById('card-c3-hav-box');
        const containerEl = document.querySelector('.hav-matrix-container');
        if (c4CardEl) havResizeObserver.observe(c4CardEl);
        if (c3CardEl) havResizeObserver.observe(c3CardEl);
        if (containerEl) havResizeObserver.observe(containerEl);
    }

    // 10. Super Admin: Hapus Karyawan Handlers
    document.querySelectorAll('.btn-hapus-karyawan').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const nik = btn.getAttribute('data-nik');
            const name = btn.getAttribute('data-name') || '';

            const form = document.getElementById('form-hapus-karyawan');
            if (form) {
                form.action = `/karyawan/${nik}`;
            }

            const nameSpan = document.getElementById('hapus-emp-name');
            if (nameSpan) nameSpan.textContent = name;

            const nikSpan = document.getElementById('hapus-emp-nik');
            if (nikSpan) nikSpan.textContent = nik;

            window.openModal('modal-hapus-karyawan');
        });
    });

    // 11. Dynamic Multi-Section Modals & Quick Action Triggers
    const getCurrentEmployeeNik = () => {
        const match = window.location.pathname.match(/\/karyawan\/([^\/]+)/);
        if (match && match[1]) return match[1];
        const nikInput = document.querySelector('input[name="nik"]');
        if (nikInput && nikInput.value) return nikInput.value;
        return '012345';
    };

    // 11.1 Section Switcher Dropdowns inside Modals
    document.querySelectorAll('.section-switcher').forEach(select => {
        select.addEventListener('change', () => {
            const val = select.value;
            const modal = select.closest('.modal-card') || select.closest('.modal-backdrop') || document;
            const subforms = modal.querySelectorAll('.dynamic-subform');
            subforms.forEach(form => {
                if (form.getAttribute('data-section') === val) {
                    form.style.display = '';
                    // Trigger change on item select if present to prefill
                    const itemSelect = form.querySelector('select[id^="select-edit-"], select[id^="select-del-"]');
                    if (itemSelect) {
                        itemSelect.dispatchEvent(new Event('change'));
                    }
                } else {
                    form.style.display = 'none';
                }
            });
        });
    });

    // 11.2 Pre-select section & type when clicking quick action buttons
    document.querySelectorAll('[data-preselect-section], [data-preselect-type]').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.getAttribute('data-preselect-section');
            const targetModalId = btn.getAttribute('data-modal-target');
            const targetModal = targetModalId ? document.getElementById(targetModalId) : null;
            if (targetModal) {
                if (section) {
                    const switcher = targetModal.querySelector('.section-switcher');
                    if (switcher) {
                        switcher.value = section;
                        switcher.dispatchEvent(new Event('change'));
                    }
                }
                const preselectType = btn.getAttribute('data-preselect-type');
                if (preselectType) {
                    const typeSelect = targetModal.querySelector('#tambah-gap-type, #tambah-idp-competency-type, #tambah-review-competency-type, #edit-review-competency-type');
                    if (typeSelect) {
                        typeSelect.value = preselectType;
                        typeSelect.dispatchEvent(new Event('change'));
                    }
                    if (targetModalId === 'modal-edit-review' || targetModalId === 'modal-hapus-review') {
                        const selectItem = targetModal.querySelector('#select-edit-review-item, #select-del-review-item');
                        if (selectItem) {
                            for (let i = 0; i < selectItem.options.length; i++) {
                                const opt = selectItem.options[i];
                                const optText = opt.textContent || '';
                                const optType = opt.getAttribute('data-type') || (optText.includes('[' + preselectType + ']') ? preselectType : '');
                                if (optType.toLowerCase() === preselectType.toLowerCase()) {
                                    selectItem.selectedIndex = i;
                                    selectItem.dispatchEvent(new Event('change'));
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        });
    });

    // 11.3 C4 Kekuatan Utama: Pre-fill Edit and Action
    const selectEditC4 = document.getElementById('select-edit-c4-item');
    const formEditC4 = document.getElementById('form-edit-c4');
    const btnDeleteC4FromEdit = document.getElementById('btn-delete-c4-from-edit');
    const formDeleteC4Direct = document.getElementById('form-delete-c4-direct');

    if (selectEditC4 && formEditC4) {
        const updateC4Edit = () => {
            const opt = selectEditC4.selectedOptions[0];
            if (!opt) {
                if (btnDeleteC4FromEdit) btnDeleteC4FromEdit.style.display = 'none';
                return;
            }
            if (btnDeleteC4FromEdit) btnDeleteC4FromEdit.style.display = 'inline-flex';
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditC4.action = `/karyawan/${nik}/key-strength/${id}`;
            const strInput = document.getElementById('edit-c4-strength');
            const descInput = document.getElementById('edit-c4-desc');
            const srcInput = document.getElementById('edit-c4-source');
            if (strInput) strInput.value = opt.getAttribute('data-strength') || '';
            if (descInput) descInput.value = opt.getAttribute('data-desc') || '';
            if (srcInput) srcInput.value = opt.getAttribute('data-source') || '';

            const docWrap = document.getElementById('edit-c4-doc-current-wrap');
            const docLink = document.getElementById('edit-c4-doc-link');
            const docRemove = document.getElementById('edit-c4-remove-doc');
            const docInput = document.getElementById('edit-c4-doc');

            if (docInput) docInput.value = '';
            if (docRemove) docRemove.checked = false;

            const docPath = opt.getAttribute('data-doc');
            const docUrl = opt.getAttribute('data-doc-url');
            const docExt = (opt.getAttribute('data-doc-ext') || '').toUpperCase();

            if (docWrap && docLink) {
                if (docPath && docUrl) {
                    docWrap.style.display = 'block';
                    docLink.href = docUrl;
                    docLink.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block; vertical-align:middle; margin-right:4px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg><span>Lihat File (${docExt || 'Dokumen'})</span>`;
                } else {
                    docWrap.style.display = 'none';
                    docLink.href = '#';
                }
            }
        };
        selectEditC4.addEventListener('change', updateC4Edit);
        updateC4Edit();

        if (btnDeleteC4FromEdit && formDeleteC4Direct) {
            btnDeleteC4FromEdit.addEventListener('click', function() {
                const opt = selectEditC4.selectedOptions[0];
                if (!opt) return;
                const id = opt.value;
                const strName = opt.getAttribute('data-strength') || opt.textContent.trim();
                if (confirm(`Apakah Anda yakin ingin menghapus kekuatan utama "${strName}"?`)) {
                    const nik = getCurrentEmployeeNik();
                    formDeleteC4Direct.action = `/karyawan/${nik}/key-strength/${id}`;
                    formDeleteC4Direct.submit();
                }
            });
        }
    }

    // 11.4 C4 Kekuatan Utama: Pre-fill Delete Action
    const selectDelC4 = document.getElementById('select-del-c4-item');
    const formDelC4 = document.getElementById('form-hapus-c4');
    if (selectDelC4 && formDelC4) {
        const updateC4Del = () => {
            const id = selectDelC4.value;
            const nik = getCurrentEmployeeNik();
            formDelC4.action = `/karyawan/${nik}/key-strength/${id}`;
        };
        selectDelC4.addEventListener('change', updateC4Del);
        updateC4Del();
    }

    // Helper Sync Dropdown Periode (Bulan & Tahun 2 Digit)
    function setupPeriodPicker(monthId, yearId, hiddenId) {
        const m = document.getElementById(monthId);
        const y = document.getElementById(yearId);
        const h = document.getElementById(hiddenId);
        if (m && y && h) {
            const sync = () => {
                h.value = `${m.value}-${y.value}`;
            };
            m.addEventListener('change', sync);
            y.addEventListener('change', sync);
        }
    }
    setupPeriodPicker('add-c6-month', 'add-c6-year', 'add-c6-date-val');
    setupPeriodPicker('edit-c6-month', 'edit-c6-year', 'edit-c6-date');
    setupPeriodPicker('edit-c2-prev-month', 'edit-c2-prev-year', 'edit-c2-prev-val');
    setupPeriodPicker('edit-c2-last-month', 'edit-c2-last-year', 'edit-c2-last-val');
    setupPeriodPicker('add-c2-prev-month', 'add-c2-prev-year', 'add-c2-prev-val');
    setupPeriodPicker('add-c2-last-month', 'add-c2-last-year', 'add-c2-last-val');

    // 11.5 C6 Riwayat POTASS: Pre-fill Edit and Action
    const selectEditC6 = document.getElementById('select-edit-c6-item');
    const formEditC6 = document.getElementById('form-edit-c6');
    const btnDeleteC6FromEdit = document.getElementById('btn-delete-c6-from-edit');
    const formDeleteC6Direct = document.getElementById('form-delete-c6-direct');

    if (selectEditC6 && formEditC6) {
        const updateC6Edit = () => {
            const opt = selectEditC6.selectedOptions[0];
            if (!opt) {
                if (btnDeleteC6FromEdit) btnDeleteC6FromEdit.style.display = 'none';
                return;
            }
            if (btnDeleteC6FromEdit) btnDeleteC6FromEdit.style.display = 'inline-flex';
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditC6.action = `/karyawan/${nik}/talent-assessment/${id}`;
            const dateInput = document.getElementById('edit-c6-date');
            const posInput = document.getElementById('edit-c6-pos');
            const assessorInput = document.getElementById('edit-c6-assessor');
            const rawDate = opt.getAttribute('data-date') || '';
            if (dateInput) dateInput.value = rawDate;
            const mSelect = document.getElementById('edit-c6-month');
            const ySelect = document.getElementById('edit-c6-year');
            if (mSelect && ySelect && rawDate) {
                const parts = rawDate.split('-');
                if (parts.length >= 2) {
                    mSelect.value = parts[0];
                    let yr = parts[1];
                    if (yr.length === 4) yr = yr.slice(-2);
                    ySelect.value = yr;
                }
            }
            if (posInput) {
                const rawPos = (opt.getAttribute('data-pos') || '').trim();
                posInput.value = rawPos;
                if (!posInput.value && rawPos) {
                    let matched = false;
                    for (let i = 0; i < posInput.options.length; i++) {
                        if (posInput.options[i].value.toLowerCase() === rawPos.toLowerCase()) {
                            posInput.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                    if (!matched) {
                        const optEl = document.createElement('option');
                        optEl.value = rawPos;
                        optEl.textContent = rawPos;
                        optEl.selected = true;
                        posInput.appendChild(optEl);
                    }
                }
            }
            if (assessorInput) assessorInput.value = opt.getAttribute('data-assessor') || 'HR Development';

            for (let i = 1; i <= 8; i++) {
                const bInput = document.getElementById(`edit-c6-b${i}`);
                if (bInput) {
                    bInput.value = opt.getAttribute(`data-b${i}`) || (i === 4 || i === 5 ? '3.0' : '4.0');
                }
            }
            if (typeof window.calcEditC6 === 'function') {
                window.calcEditC6();
            }
        };
        selectEditC6.addEventListener('change', updateC6Edit);
        updateC6Edit();

        if (btnDeleteC6FromEdit && formDeleteC6Direct) {
            btnDeleteC6FromEdit.addEventListener('click', function() {
                const opt = selectEditC6.selectedOptions[0];
                if (!opt) return;
                const id = opt.value;
                const dateLabel = opt.getAttribute('data-date') || opt.textContent.trim();
                if (confirm(`Apakah Anda yakin ingin menghapus data riwayat asesmen "${dateLabel}"?`)) {
                    const nik = getCurrentEmployeeNik();
                    formDeleteC6Direct.action = `/karyawan/${nik}/talent-assessment/${id}`;
                    formDeleteC6Direct.submit();
                }
            });
        }
    }

    // 11.6 C6 Riwayat POTASS: Pre-fill Delete Action
    const selectDelC6 = document.getElementById('select-del-c6-item');
    const formDelC6 = document.getElementById('form-hapus-c6');
    if (selectDelC6 && formDelC6) {
        const updateC6Del = () => {
            const id = selectDelC6.value;
            const nik = getCurrentEmployeeNik();
            formDelC6.action = `/karyawan/${nik}/talent-assessment/${id}`;
        };
        selectDelC6.addEventListener('change', updateC6Del);
        updateC6Del();
    }

    // 11.7 D2 Job Class Plan: Pre-fill Edit and Action
    const selectEditD2 = document.getElementById('select-edit-d2-item');
    const formEditD2 = document.getElementById('form-edit-d2');
    if (selectEditD2 && formEditD2) {
        const updateD2Edit = () => {
            const opt = selectEditD2.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditD2.action = `/karyawan/${nik}/job-class-plan/${id}`;
            const yrInput = document.getElementById('edit-d2-year');
            const ageInput = document.getElementById('edit-d2-age');
            const jcInput = document.getElementById('edit-d2-class');
            const grInput = document.getElementById('edit-d2-grade');
            const typeInput = document.getElementById('edit-d2-type');
            const posInput = document.getElementById('edit-d2-targetpos');
            const deptInput = document.getElementById('edit-d2-targetdept');
            const notesInput = document.getElementById('edit-d2-notes');
            if (yrInput) yrInput.value = opt.getAttribute('data-year') || '';
            if (ageInput) ageInput.value = opt.getAttribute('data-age') || '';
            const d2Class = opt.getAttribute('data-class') || '';
            const d2Grade = opt.getAttribute('data-grade') || '';
            if (jcInput) {
                jcInput.value = d2Class;
                if (typeof jcInput._updateGrades === 'function') {
                    jcInput._updateGrades(d2Grade);
                } else if (grInput) {
                    grInput.value = d2Grade;
                }
            } else if (grInput) {
                grInput.value = d2Grade;
            }
            if (typeInput) typeInput.value = opt.getAttribute('data-type') || 'Kenaikan Pangkat Reguler';
            if (posInput) posInput.value = opt.getAttribute('data-targetpos') || '';
            if (deptInput) deptInput.value = opt.getAttribute('data-targetdept') || '';
            if (notesInput) notesInput.value = opt.getAttribute('data-notes') || '';
        };
        selectEditD2.addEventListener('change', updateD2Edit);
        updateD2Edit();
    }

    // 11.8 D2 Job Class Plan: Pre-fill Delete Action
    const selectDelD2 = document.getElementById('select-del-d2-item');
    const formDelD2 = document.getElementById('form-hapus-d2');
    if (selectDelD2 && formDelD2) {
        const updateD2Del = () => {
            const id = selectDelD2.value;
            const nik = getCurrentEmployeeNik();
            formDelD2.action = `/karyawan/${nik}/job-class-plan/${id}`;
        };
        selectDelD2.addEventListener('change', updateD2Del);
        updateD2Del();
    }

    // 11.9 E1 Succession Position: Pre-fill Edit and Action
    const selectEditE1 = document.getElementById('select-edit-e1-item');
    const formEditE1 = document.getElementById('form-edit-e1');
    if (selectEditE1 && formEditE1) {
        const updateE1Edit = () => {
            const opt = selectEditE1.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditE1.action = `/karyawan/${nik}/succession-position/${id}`;
            const targetInput = document.getElementById('edit-e1-target');
            const deptInput = document.getElementById('edit-e1-dept');
            const levelInput = document.getElementById('edit-e1-level');
            const neededInput = document.getElementById('edit-e1-needed');
            const readInput = document.getElementById('edit-e1-readiness');
            const reasonInput = document.getElementById('edit-e1-reason');
            if (targetInput) targetInput.value = opt.getAttribute('data-target') || '';
            if (deptInput) deptInput.value = opt.getAttribute('data-dept') || '';
            if (levelInput) levelInput.value = opt.getAttribute('data-level') || '';
            if (neededInput) neededInput.value = opt.getAttribute('data-needed') || '';
            if (readInput) readInput.value = opt.getAttribute('data-readiness') || '3–5 Tahun';
            if (reasonInput) reasonInput.value = opt.getAttribute('data-reason') || '';
        };
        selectEditE1.addEventListener('change', updateE1Edit);
        updateE1Edit();
    }

    // 11.10 E1 Succession Position: Pre-fill Delete Action
    const selectDelE1 = document.getElementById('select-del-e1-item');
    const formDelE1 = document.getElementById('form-hapus-e1');
    if (selectDelE1 && formDelE1) {
        const updateE1Del = () => {
            const id = selectDelE1.value;
            const nik = getCurrentEmployeeNik();
            formDelE1.action = `/karyawan/${nik}/succession-position/${id}`;
        };
        selectDelE1.addEventListener('change', updateE1Del);
        updateE1Del();
    }

    // 11.11 E2 Succession Candidate: Pre-fill Edit and Action
    const selectEditE2 = document.getElementById('select-edit-e2-item');
    const formEditE2 = document.getElementById('form-edit-e2');
    if (selectEditE2 && formEditE2) {
        const updateE2Edit = () => {
            const opt = selectEditE2.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditE2.action = `/karyawan/${nik}/succession-candidate/${id}`;
            const nameInput = document.getElementById('edit-e2-name');
            const rankInput = document.getElementById('edit-e2-ranking');
            const deptInput = document.getElementById('edit-e2-dept');
            const posInput = document.getElementById('edit-e2-pos');
            const readInput = document.getElementById('edit-e2-readiness');
            const neededInput = document.getElementById('edit-e2-needed');
            const notesInput = document.getElementById('edit-e2-notes');
            if (nameInput) nameInput.value = opt.getAttribute('data-name') || '';
            if (rankInput) rankInput.value = opt.getAttribute('data-ranking') || '';
            if (deptInput) deptInput.value = opt.getAttribute('data-dept') || '';
            if (posInput) posInput.value = opt.getAttribute('data-pos') || '';
            if (readInput) readInput.value = opt.getAttribute('data-readiness') || '3–5 Tahun';
            if (neededInput) neededInput.value = opt.getAttribute('data-needed') || '';
            if (notesInput) notesInput.value = opt.getAttribute('data-notes') || '';
        };
        selectEditE2.addEventListener('change', updateE2Edit);
        updateE2Edit();
    }

    // 11.12 E2 Succession Candidate: Pre-fill Delete Action
    const selectDelE2 = document.getElementById('select-del-e2-item');
    const formDelE2 = document.getElementById('form-hapus-e2');
    if (selectDelE2 && formDelE2) {
        const updateE2Del = () => {
            const id = selectDelE2.value;
            const nik = getCurrentEmployeeNik();
            formDelE2.action = `/karyawan/${nik}/succession-candidate/${id}`;
        };
        selectDelE2.addEventListener('change', updateE2Del);
        updateE2Del();
    }

    // 11.13 Competency Gap: Pre-fill Edit and Action
    const selectEditGap = document.getElementById('select-edit-gap-item');
    const formEditGap = document.getElementById('form-edit-gap-detail');
    if (selectEditGap && formEditGap) {
        const updateGapEdit = () => {
            const opt = selectEditGap.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditGap.action = `/karyawan/${nik}/competency-gap/${id}`;
            const compInput = document.getElementById('edit-gap-comp');
            const typeInput = document.getElementById('edit-gap-type');
            const currInput = document.getElementById('edit-gap-curr');
            const currDescInput = document.getElementById('edit-gap-currdesc');
            const stdInput = document.getElementById('edit-gap-std');
            const stdDescInput = document.getElementById('edit-gap-stddesc');
            const impInput = document.getElementById('edit-gap-improvement');
            if (compInput) compInput.value = opt.getAttribute('data-comp') || '';
            if (typeInput) typeInput.value = opt.getAttribute('data-type') || 'Manajerial';
            if (currInput) currInput.value = opt.getAttribute('data-curr') || '1';
            if (currDescInput) currDescInput.value = opt.getAttribute('data-currdesc') || '';
            if (stdInput) stdInput.value = opt.getAttribute('data-std') || '1';
            if (stdDescInput) stdDescInput.value = opt.getAttribute('data-stddesc') || '';
            if (impInput) impInput.value = opt.getAttribute('data-improvement') || '';
        };
        selectEditGap.addEventListener('change', updateGapEdit);
        updateGapEdit();
    }

    // 11.14 Competency Gap: Pre-fill Delete Action
    const selectDelGap = document.getElementById('select-del-gap-item');
    const formDelGap = document.getElementById('form-hapus-gap');
    if (selectDelGap && formDelGap) {
        const updateGapDel = () => {
            const id = selectDelGap.value;
            const nik = getCurrentEmployeeNik();
            formDelGap.action = `/karyawan/${nik}/competency-gap/${id}`;
        };
        selectDelGap.addEventListener('change', updateGapDel);
        updateGapDel();
    }

    // 11.15 IDP Action Plan: Pre-fill Edit and Action
    const selectEditIdp = document.getElementById('select-edit-idp-item');
    const formEditIdp = document.getElementById('form-edit-idp-action');
    if (selectEditIdp && formEditIdp) {
        const updateIdpEdit = () => {
            const opt = selectEditIdp.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditIdp.action = `/karyawan/${nik}/idp-action-plan/${id}`;
            const compInput = document.getElementById('edit-idp-comp');
            const goalInput = document.getElementById('edit-idp-goal');
            const methodsInput = document.getElementById('edit-idp-methods');
            const progInput = document.getElementById('edit-idp-program');
            const picInput = document.getElementById('edit-idp-pic');
            const statusInput = document.getElementById('edit-idp-status');
            const startInput = document.getElementById('edit-idp-start');
            const endInput = document.getElementById('edit-idp-end');
            const indInput = document.getElementById('edit-idp-indicator');
            const progressInput = document.getElementById('edit-idp-progress');
            const typeInput = document.getElementById('edit-idp-competency-type');
            if (typeInput) typeInput.value = opt.getAttribute('data-type') || 'Manajerial';
            if (compInput) compInput.value = opt.getAttribute('data-comp') || '';
            if (goalInput) goalInput.value = opt.getAttribute('data-goal') || '';
            if (methodsInput) methodsInput.value = opt.getAttribute('data-methods') || '';
            if (progInput) progInput.value = opt.getAttribute('data-program') || '';
            if (picInput) picInput.value = opt.getAttribute('data-pic') || '';
            if (statusInput) statusInput.value = opt.getAttribute('data-status') || 'On Progress';
            if (startInput) startInput.value = opt.getAttribute('data-start') || '';
            if (endInput) endInput.value = opt.getAttribute('data-end') || '';
            if (indInput) indInput.value = opt.getAttribute('data-indicator') || '';
            if (progressInput) progressInput.value = opt.getAttribute('data-progress') || '0';
        };
        selectEditIdp.addEventListener('change', updateIdpEdit);
        updateIdpEdit();
    }

    // 11.16 IDP Action Plan: Pre-fill Delete Action
    const selectDelIdp = document.getElementById('select-del-idp-item');
    const formDelIdp = document.getElementById('form-hapus-idp');
    if (selectDelIdp && formDelIdp) {
        const updateIdpDel = () => {
            const id = selectDelIdp.value;
            const nik = getCurrentEmployeeNik();
            formDelIdp.action = `/karyawan/${nik}/idp-action-plan/${id}`;
        };
        selectDelIdp.addEventListener('change', updateIdpDel);
        updateIdpDel();
    }

    // 11.17 Development Review: Pre-fill Edit and Action
    const selectEditReview = document.getElementById('select-edit-review-item');
    const formEditReview = document.getElementById('form-edit-review');
    if (selectEditReview && formEditReview) {
        const updateReviewEdit = () => {
            const opt = selectEditReview.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditReview.action = `/karyawan/${nik}/development-review/${id}`;
            const compInput = document.getElementById('edit-review-comp');
            const typeInput = document.getElementById('edit-review-competency-type');
            const periodInput = document.getElementById('edit-review-period');
            const prevInput = document.getElementById('edit-review-prev');
            const currInput = document.getElementById('edit-review-curr');
            const targetInput = document.getElementById('edit-review-target');
            const statusInput = document.getElementById('edit-review-status');
            const notesInput = document.getElementById('edit-review-notes');

            if (typeInput) typeInput.value = opt.getAttribute('data-type') || 'Manajerial';
            if (compInput) compInput.value = opt.getAttribute('data-comp') || '';
            if (periodInput) periodInput.value = opt.getAttribute('data-period') || '';
            if (prevInput) prevInput.value = opt.getAttribute('data-prev') || '0';
            if (currInput) currInput.value = opt.getAttribute('data-curr') || '0';
            if (targetInput) targetInput.value = opt.getAttribute('data-target') || '0';
            if (statusInput) statusInput.value = opt.getAttribute('data-status') || '';
            if (notesInput) notesInput.value = opt.getAttribute('data-notes') || '';
        };
        selectEditReview.addEventListener('change', updateReviewEdit);
        updateReviewEdit();
    }

    // 11.18 Development Review: Pre-fill Delete Action
    const selectDelReview = document.getElementById('select-del-review-item');
    const formDelReview = document.getElementById('form-hapus-review');
    if (selectDelReview && formDelReview) {
        const updateReviewDel = () => {
            const id = selectDelReview.value;
            const nik = getCurrentEmployeeNik();
            formDelReview.action = `/karyawan/${nik}/development-review/${id}`;
        };
        selectDelReview.addEventListener('change', updateReviewDel);
        updateReviewDel();
    }
});

// Global Modal Control Functions
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
};

window.openEditReviewModal = function(id) {
    const modal = document.getElementById('modal-edit-review');
    const select = document.getElementById('select-edit-review-item');
    if (select) {
        select.value = id;
        select.dispatchEvent(new Event('change'));
    }
    if (modal) {
        modal.classList.add('show');
    }
};

window.openDeleteReviewModal = function(id) {
    const modal = document.getElementById('modal-hapus-review');
    const select = document.getElementById('select-del-review-item');
    if (select) {
        select.value = id;
        select.dispatchEvent(new Event('change'));
    }
    if (modal) {
        modal.classList.add('show');
    }
};

// ==========================================================================
// Interactive Avatar Cropper & Position Adjuster
// ==========================================================================
(function() {
    let cropCanvas = null;
    let cropCtx = null;
    let cropImage = null;
    let cropState = {
        x: 0,
        y: 0,
        scale: 1,
        rotation: 0,
        isDragging: false,
        dragStartX: 0,
        dragStartY: 0,
        initialScale: 1
    };

    let activeInputElem = null;
    let activeHiddenInput = null;
    let activePreviewImg = null;
    let activeContainer = null;

    function initCropper() {
        cropCanvas = document.getElementById('crop-canvas');
        if (!cropCanvas) return;
        cropCtx = cropCanvas.getContext('2d');

        const wrapper = document.getElementById('crop-canvas-wrapper');
        const zoomRange = document.getElementById('crop-zoom-range');
        const zoomText = document.getElementById('zoom-level-text');
        const btnZoomIn = document.getElementById('btn-zoom-in');
        const btnZoomOut = document.getElementById('btn-zoom-out');
        const btnRotate = document.getElementById('btn-rotate-crop');
        const btnReset = document.getElementById('btn-reset-crop');
        const btnApply = document.getElementById('btn-apply-crop');
        const btnCancel = document.getElementById('btn-cancel-crop');
        const btnClose = document.getElementById('btn-close-cropper');

        // Mouse Drag Handlers
        wrapper.addEventListener('mousedown', (e) => {
            cropState.isDragging = true;
            cropState.dragStartX = e.clientX - cropState.x;
            cropState.dragStartY = e.clientY - cropState.y;
            wrapper.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!cropState.isDragging) return;
            cropState.x = e.clientX - cropState.dragStartX;
            cropState.y = e.clientY - cropState.dragStartY;
            drawCropCanvas();
        });

        window.addEventListener('mouseup', () => {
            if (cropState.isDragging) {
                cropState.isDragging = false;
                if (wrapper) wrapper.style.cursor = 'grab';
            }
        });

        // Touch Handlers for Mobile / Touchpad
        wrapper.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                cropState.isDragging = true;
                cropState.dragStartX = e.touches[0].clientX - cropState.x;
                cropState.dragStartY = e.touches[0].clientY - cropState.y;
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (!cropState.isDragging || e.touches.length !== 1) return;
            cropState.x = e.touches[0].clientX - cropState.dragStartX;
            cropState.y = e.touches[0].clientY - cropState.dragStartY;
            drawCropCanvas();
        }, { passive: true });

        window.addEventListener('touchend', () => {
            cropState.isDragging = false;
        });

        // Mouse Wheel Zoom
        wrapper.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.08 : -0.08;
            setZoom(cropState.scale + delta);
        }, { passive: false });

        // Zoom Controls
        if (zoomRange) {
            zoomRange.addEventListener('input', (e) => {
                setZoom(parseFloat(e.target.value));
            });
        }

        if (btnZoomIn) {
            btnZoomIn.addEventListener('click', () => setZoom(cropState.scale + 0.15));
        }

        if (btnZoomOut) {
            btnZoomOut.addEventListener('click', () => setZoom(cropState.scale - 0.15));
        }

        if (btnRotate) {
            btnRotate.addEventListener('click', () => {
                cropState.rotation = (cropState.rotation + 90) % 360;
                drawCropCanvas();
            });
        }

        if (btnReset) {
            btnReset.addEventListener('click', resetCropState);
        }

        // Apply Cropped Image
        if (btnApply) {
            btnApply.addEventListener('click', applyCroppedImage);
        }

        // Cancel / Close Handlers
        const closeCropper = () => {
            window.closeModal('modal-cropper-avatar');
        };

        if (btnCancel) btnCancel.addEventListener('click', closeCropper);
        if (btnClose) btnClose.addEventListener('click', closeCropper);
    }

    function setZoom(val) {
        const min = 0.2;
        const max = 4.0;
        cropState.scale = Math.max(min, Math.min(max, val));
        const zoomRange = document.getElementById('crop-zoom-range');
        const zoomText = document.getElementById('zoom-level-text');
        if (zoomRange) zoomRange.value = cropState.scale;
        if (zoomText) zoomText.textContent = `${Math.round(cropState.scale * 100)}%`;
        drawCropCanvas();
    }

    function resetCropState() {
        if (!cropImage) return;
        cropState.x = 0;
        cropState.y = 0;
        cropState.rotation = 0;
        // Fit viewport circle (240px diameter)
        const minDim = Math.min(cropImage.width, cropImage.height);
        const fitScale = Math.max(240 / minDim, 0.5);
        cropState.initialScale = fitScale;
        setZoom(fitScale);
    }

    function drawCropCanvas() {
        if (!cropCtx || !cropImage) return;

        const cw = cropCanvas.width;
        const ch = cropCanvas.height;

        cropCtx.clearRect(0, 0, cw, ch);
        cropCtx.save();

        // Center transform
        cropCtx.translate(cw / 2 + cropState.x, ch / 2 + cropState.y);
        cropCtx.rotate((cropState.rotation * Math.PI) / 180);
        cropCtx.scale(cropState.scale, cropState.scale);

        // Draw image centered
        cropCtx.drawImage(
            cropImage,
            -cropImage.width / 2,
            -cropImage.height / 2,
            cropImage.width,
            cropImage.height
        );

        cropCtx.restore();

        // Update real-time previews
        updateLivePreviews();
    }

    function updateLivePreviews() {
        // Stencil circle is centered at (170, 170) with diameter 240, from (50, 50) to (290, 290)
        const srcX = 50;
        const srcY = 50;
        const srcSize = 240;

        // 1. Large Preview (100x100)
        const pLarge = document.getElementById('crop-preview-large');
        if (pLarge) {
            const ctxL = pLarge.getContext('2d');
            ctxL.clearRect(0, 0, 100, 100);
            ctxL.save();
            ctxL.beginPath();
            ctxL.arc(50, 50, 50, 0, Math.PI * 2);
            ctxL.clip();
            ctxL.drawImage(cropCanvas, srcX, srcY, srcSize, srcSize, 0, 0, 100, 100);
            ctxL.restore();
        }

        // 2. Small Preview (40x40)
        const pSmall = document.getElementById('crop-preview-small');
        if (pSmall) {
            const ctxS = pSmall.getContext('2d');
            ctxS.clearRect(0, 0, 40, 40);
            ctxS.save();
            ctxS.beginPath();
            ctxS.arc(20, 20, 20, 0, Math.PI * 2);
            ctxS.clip();
            ctxS.drawImage(cropCanvas, srcX, srcY, srcSize, srcSize, 0, 0, 40, 40);
            ctxS.restore();
        }
    }

    function applyCroppedImage() {
        if (!cropCanvas) return;

        // Render high-res 400x400 output canvas
        const exportCanvas = document.createElement('canvas');
        exportCanvas.width = 400;
        exportCanvas.height = 400;
        const expCtx = exportCanvas.getContext('2d');

        // Draw cropped circular area scaled up
        expCtx.drawImage(cropCanvas, 50, 50, 240, 240, 0, 0, 400, 400);

        const croppedDataUrl = exportCanvas.toDataURL('image/png', 0.95);

        // Populate hidden input with base64 data URL
        if (activeHiddenInput) {
            activeHiddenInput.value = croppedDataUrl;
        }

        // Update preview image
        if (activePreviewImg) {
            activePreviewImg.src = croppedDataUrl;
        }

        // Show container if applicable
        if (activeContainer) {
            activeContainer.style.display = 'flex';
        }

        // If from modal-ubah-foto, clear preset selection
        const presetInput = document.getElementById('input-preset-avatar');
        if (presetInput) presetInput.value = '';
        document.querySelectorAll('.preset-avatar-option').forEach(el => el.classList.remove('active'));

        window.closeModal('modal-cropper-avatar');
    }

    window.openCropperForInput = function(inputElem, hiddenSelector, previewImgSelector, containerSelector) {
        if (!inputElem.files || !inputElem.files[0]) return;

        activeInputElem = inputElem;
        activeHiddenInput = document.querySelector(hiddenSelector);
        activePreviewImg = document.querySelector(previewImgSelector);
        activeContainer = containerSelector ? document.querySelector(containerSelector) : null;

        const file = inputElem.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            cropImage = new Image();
            cropImage.onload = function() {
                if (!cropCanvas) {
                    initCropper();
                }
                resetCropState();
                window.openModal('modal-cropper-avatar');
                drawCropCanvas();
            };
            cropImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
    };

    // DOM Ready hook
    document.addEventListener('DOMContentLoaded', initCropper);
})();

// Avatar Form Trigger Handlers
window.previewTambahAvatar = function(input) {
    window.openCropperForInput(
        input,
        '#tambah-avatar-cropped-input',
        '#preview-tambah-avatar-img',
        '#preview-tambah-avatar-container'
    );
};

window.previewEditAvatar = function(input) {
    window.openCropperForInput(
        input,
        '#edit-avatar-cropped-input',
        '#preview-edit-avatar-img',
        '#preview-edit-avatar-container'
    );
};

window.handleModalAvatarChange = function(input) {
    window.openCropperForInput(
        input,
        '#modal-avatar-cropped-input',
        '#avatar-preview-display',
        null
    );
};

window.selectPresetAvatar = function(path, element) {
    const display = document.getElementById('avatar-preview-display');
    if (display) {
        display.src = path;
    }

    const presetInput = document.getElementById('input-preset-avatar');
    if (presetInput) {
        presetInput.value = path;
    }

    // Clear cropped base64 & file input
    const croppedInput = document.getElementById('modal-avatar-cropped-input');
    if (croppedInput) croppedInput.value = '';

    const fileInput = document.getElementById('modal-avatar-file-input');
    if (fileInput) {
        fileInput.value = '';
    }

    document.querySelectorAll('.preset-avatar-option').forEach(el => el.classList.remove('active'));
    if (element) {
        element.classList.add('active');
    }
};

window.prefillPerfModal = function(year, rating, notes) {
    const yearInput = document.getElementById('input-modal-perf-year');
    const ratingInput = document.getElementById('input-modal-perf-rating');
    const notesInput = document.getElementById('input-modal-perf-notes');
    if (yearInput) yearInput.value = year;
    if (ratingInput) ratingInput.value = rating;
    if (notesInput) notesInput.value = notes;
    if (yearInput) {
        yearInput.focus();
        yearInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
};

// ==========================================
// 12. TALENT SNAPSHOT FORMULAS (C1, C5, C6)
// ==========================================

// 12.1 C1: Performance 3 Tahun Terakhir (C1.xlsx)
document.addEventListener('DOMContentLoaded', () => {
    const ratingToPoints = (rating) => {
        if (!rating) return 0;
        const r = rating.trim().toUpperCase();
        if (r === 'S') return 8;
        if (r === 'AS') return 7;
        if (r === 'A') return 6;
        if (r === 'B+') return 5;
        if (r === 'B') return 4;
        if (r === '-') return 0;
        if (r === 'C') return 2;
        if (r === 'K') return 1;
        if (r === 'IST' || r === 'A+') return 8;
        if (r === 'BS+') return 7;
        if (r === 'BS') return 6;
        if (r === 'C+') return 3;
        if (r === 'D' || r === 'E') return 1;
        return 0;
    };

    const curInput = document.getElementById('input-c1-current');

    const updateC1Calc = () => {
        const ratingSelects = document.querySelectorAll('#form-edit-c1 .c1-rating-select');
        if (!ratingSelects || ratingSelects.length === 0) return;

        let total = 0;
        let lastVal = '';
        ratingSelects.forEach((sel) => {
            total += ratingToPoints(sel.value);
            lastVal = sel.value;
        });

        const elTotal = document.getElementById('c1-preview-total');
        if (elTotal) elTotal.textContent = total;

        let barisCode = 'R0';
        if (total >= 21) {
            barisCode = 'R3';
        } else if (total >= 16) {
            barisCode = 'R2';
        } else if (total >= 12) {
            barisCode = 'R1';
        }

        const elBaris = document.getElementById('c1-preview-baris');
        if (elBaris) elBaris.textContent = barisCode;

        if (curInput && lastVal) curInput.value = lastVal;
    };

    const ratingSelects = document.querySelectorAll('#form-edit-c1 .c1-rating-select');
    if (ratingSelects.length > 0) {
        ratingSelects.forEach(s => s.addEventListener('change', updateC1Calc));
        updateC1Calc();
    }

    // 12.2 C5: Flying Risk Assessment (C5flyrisk.png)
    const growthSel = document.getElementById('edit-c5-growth');
    const marketSel = document.getElementById('edit-c5-market');
    const compSel = document.getElementById('edit-c5-comp');

    const updateC5Calc = () => {
        if (!growthSel || !marketSel || !compSel) return;
        const optGrowth = growthSel.selectedOptions[0];
        const optMarket = marketSel.selectedOptions[0];
        const optComp = compSel.selectedOptions[0];

        const p1 = optGrowth ? parseInt(optGrowth.getAttribute('data-pts') || '0', 10) : 0;
        const p2 = optMarket ? parseInt(optMarket.getAttribute('data-pts') || '0', 10) : 0;
        const p3 = optComp ? parseInt(optComp.getAttribute('data-pts') || '0', 10) : 0;

        const total = p1 + p2 + p3;
        const elTotal = document.getElementById('c5-preview-total');
        if (elTotal) elTotal.textContent = total;

        let level = 'Low Risk';
        let badgeClass = 'badge-green';
        let interp = 'Employee is highly likely to stay. Minimal intervention needed.';

        if (total <= 2) {
            level = 'Low Risk';
            badgeClass = 'badge-green';
            interp = 'Employee is highly likely to stay. Minimal intervention needed.';
        } else if (total <= 4) {
            level = 'Moderate Risk';
            badgeClass = 'badge-orange';
            interp = 'Employee may have some concerns but is not actively looking to leave. Engagement and career development efforts recommended.';
        } else {
            level = 'High Risk';
            badgeClass = 'badge-red';
            interp = 'Employee shows multiple risk factors and could leave within the next 6-12 months. Proactive retention strategies needed.';
        }

        const elBadge = document.getElementById('c5-preview-badge');
        if (elBadge) {
            elBadge.className = badgeClass;
            elBadge.textContent = level;
        }

        const elInterp = document.getElementById('c5-preview-interpretation');
        if (elInterp) elInterp.textContent = interp;
    };

    if (growthSel && marketSel && compSel) {
        [growthSel, marketSel, compSel].forEach(s => s.addEventListener('change', updateC5Calc));
        updateC5Calc();
    }

    // 12.2b C5: Flying Risk Live Calculation for Add Modal
    const growthAddSel = document.getElementById('add-c5-growth');
    const marketAddSel = document.getElementById('add-c5-market');
    const compAddSel = document.getElementById('add-c5-comp');

    const updateC5AddCalc = () => {
        if (!growthAddSel || !marketAddSel || !compAddSel) return;
        const optGrowth = growthAddSel.selectedOptions[0];
        const optMarket = marketAddSel.selectedOptions[0];
        const optComp = compAddSel.selectedOptions[0];

        const p1 = optGrowth ? parseInt(optGrowth.getAttribute('data-pts') || '0', 10) : 0;
        const p2 = optMarket ? parseInt(optMarket.getAttribute('data-pts') || '0', 10) : 0;
        const p3 = optComp ? parseInt(optComp.getAttribute('data-pts') || '0', 10) : 0;

        const total = p1 + p2 + p3;
        const elTotal = document.getElementById('add-c5-preview-total');
        if (elTotal) elTotal.textContent = total;

        let level = 'Low Risk';
        let badgeClass = 'badge-green';
        let interp = 'Employee is highly likely to stay. Minimal intervention needed.';

        if (total <= 2) {
            level = 'Low Risk';
            badgeClass = 'badge-green';
            interp = 'Employee is highly likely to stay. Minimal intervention needed.';
        } else if (total <= 4) {
            level = 'Moderate Risk';
            badgeClass = 'badge-orange';
            interp = 'Employee may have some concerns but is not actively looking to leave. Engagement and career development efforts recommended.';
        } else {
            level = 'High Risk';
            badgeClass = 'badge-red';
            interp = 'Employee shows multiple risk factors and could leave within the next 6-12 months. Proactive retention strategies needed.';
        }

        const elBadge = document.getElementById('add-c5-preview-badge');
        if (elBadge) {
            elBadge.className = badgeClass;
            elBadge.textContent = level;
        }

        const elInterp = document.getElementById('add-c5-preview-interpretation');
        if (elInterp) elInterp.textContent = interp;
    };

    if (growthAddSel && marketAddSel && compAddSel) {
        [growthAddSel, marketAddSel, compAddSel].forEach(s => s.addEventListener('change', updateC5AddCalc));
        updateC5AddCalc();
    }

    // 12.3 C6: Behavior Competencies Calculator (C6.xlsx)
    const weights = [0.15, 0.15, 0.10, 0.10, 0.10, 0.15, 0.10, 0.15];

    const calcCompetencies = (prefix) => {
        const isAdd = prefix === 'add';
        const inputs = isAdd
            ? document.querySelectorAll('.c6-calc-input-add')
            : document.querySelectorAll('.c6-calc-input-edit');

        if (!inputs || inputs.length < 8) return;

        let weighted = 0;
        inputs.forEach((inp, idx) => {
            const val = parseFloat(inp.value) || 0;
            const w = weights[idx] || 0.1;
            weighted += (val * w);
        });

        const pct = (weighted / 5.0) * 100.0;

        let kolom = 'C0';
        let cat = 'Below Average';
        if (pct >= 71.0) {
            kolom = 'C3';
            cat = 'High';
        } else if (pct >= 61.0) {
            kolom = 'C2';
            cat = 'Average';
        } else if (pct >= 50.0) {
            kolom = 'C1';
            cat = 'Average';
        } else {
            kolom = 'C0';
            cat = 'Below Average';
        }

        const elWeighted = document.getElementById(`${prefix}-c6-preview-weighted`);
        const elPct = document.getElementById(`${prefix}-c6-preview-percentage`);
        const elKolom = document.getElementById(`${prefix}-c6-preview-kolom`);
        const elCat = document.getElementById(`${prefix}-c6-preview-category`);

        if (elWeighted) elWeighted.textContent = weighted.toFixed(2);
        if (elPct) elPct.textContent = `${pct.toFixed(1)}%`;
        if (elKolom) elKolom.textContent = kolom;
        if (elCat) elCat.textContent = cat;
    };

    window.calcAddC6 = () => calcCompetencies('add');
    window.calcEditC6 = () => calcCompetencies('edit');

    document.querySelectorAll('.c6-calc-input-add').forEach(inp => {
        inp.addEventListener('input', window.calcAddC6);
        inp.addEventListener('change', window.calcAddC6);
    });

    document.querySelectorAll('.c6-calc-input-edit').forEach(inp => {
        inp.addEventListener('input', window.calcEditC6);
        inp.addEventListener('change', window.calcEditC6);
    });

    // Auto-update category & talent pool when changing HAV box in edit modal
    const selHavBoxEdit = document.getElementById('select-hav-box-edit');
    const inpHavCatEdit = document.getElementById('input-hav-cat-edit');
    const selHavTpEdit = document.getElementById('select-hav-tp-edit');
    if (selHavBoxEdit) {
        selHavBoxEdit.addEventListener('change', () => {
            const opt = selHavBoxEdit.selectedOptions[0];
            if (opt) {
                if (inpHavCatEdit && opt.getAttribute('data-name')) {
                    inpHavCatEdit.value = opt.getAttribute('data-name');
                }
                if (selHavTpEdit && opt.getAttribute('data-tp')) {
                    selHavTpEdit.value = opt.getAttribute('data-tp');
                }
            }
        });
    }

    // Auto-update category & talent pool when changing HAV box in add modal
    const selHavBoxAdd = document.getElementById('select-hav-box-add');
    const inpHavCatAdd = document.getElementById('input-hav-cat-add');
    const selHavTpAdd = document.getElementById('select-hav-tp-add');
    if (selHavBoxAdd) {
        selHavBoxAdd.addEventListener('change', () => {
            const opt = selHavBoxAdd.selectedOptions[0];
            if (opt) {
                if (inpHavCatAdd && opt.getAttribute('data-name')) {
                    inpHavCatAdd.value = opt.getAttribute('data-name');
                }
                if (selHavTpAdd && opt.getAttribute('data-tp')) {
                    selHavTpAdd.value = opt.getAttribute('data-tp');
                }
            }
        });
    }

    // --------------------------------------------------------------------------
    // 13. Collapsible & Resizable Sidebar (Geser / Ciutkan Sidebar)
    // --------------------------------------------------------------------------
    const appSidebar = document.getElementById('app-sidebar');
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebarResizer = document.getElementById('sidebar-resizer');

    if (appSidebar && sidebarToggleBtn) {
        const toggleSidebar = (shouldCollapse) => {
            const isCurrentlyCollapsed = appSidebar.classList.contains('collapsed');
            const willCollapse = shouldCollapse !== undefined ? shouldCollapse : !isCurrentlyCollapsed;

            if (willCollapse) {
                appSidebar.classList.add('collapsed');
                appSidebar.style.removeProperty('width');
                appSidebar.style.removeProperty('min-width');
                sidebarToggleBtn.setAttribute('title', 'Perluas Sidebar (Geser ke Kanan)');
                try { localStorage.setItem('mapin_sidebar_collapsed', 'true'); } catch (err) {}
            } else {
                appSidebar.classList.remove('collapsed');
                try {
                    const savedWidth = localStorage.getItem('mapin_sidebar_width');
                    if (savedWidth && parseInt(savedWidth) >= 160) {
                        appSidebar.style.width = savedWidth + 'px';
                        appSidebar.style.minWidth = savedWidth + 'px';
                    } else {
                        appSidebar.style.removeProperty('width');
                        appSidebar.style.removeProperty('min-width');
                    }
                    localStorage.setItem('mapin_sidebar_collapsed', 'false');
                } catch (err) {}
                sidebarToggleBtn.setAttribute('title', 'Ciutkan Sidebar (Geser ke Kiri)');
            }
        };

        sidebarToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSidebar();
        });

        // Click search button in collapsed mode opens and focuses search input
        const searchBtnInSidebar = appSidebar.querySelector('.search-btn');
        if (searchBtnInSidebar) {
            searchBtnInSidebar.addEventListener('click', (e) => {
                if (appSidebar.classList.contains('collapsed')) {
                    e.preventDefault();
                    toggleSidebar(false);
                    const inp = document.getElementById('sidebar-nik-search');
                    if (inp) setTimeout(() => inp.focus(), 250);
                }
            });
        }

        // Restore saved collapsed state or width
        try {
            const savedCollapsed = localStorage.getItem('mapin_sidebar_collapsed');
            if (savedCollapsed === 'true') {
                toggleSidebar(true);
            } else if (savedCollapsed === 'false') {
                toggleSidebar(false);
            } else if (window.innerWidth <= 850) {
                // Default to collapsed on mobile & small tablet
                toggleSidebar(true);
            } else {
                const savedWidth = localStorage.getItem('mapin_sidebar_width');
                if (savedWidth && parseInt(savedWidth) >= 160) {
                    appSidebar.style.width = savedWidth + 'px';
                    appSidebar.style.minWidth = savedWidth + 'px';
                }
            }
        } catch (err) {}

        // Drag to resize sidebar width
        if (sidebarResizer) {
            let isDragging = false;
            let startClientX = 0;
            let initialWidth = 0;

            sidebarResizer.addEventListener('mousedown', (e) => {
                isDragging = true;
                startClientX = e.clientX;
                initialWidth = appSidebar.getBoundingClientRect().width;
                appSidebar.style.transition = 'none';
                sidebarResizer.classList.add('is-resizing');
                document.body.classList.add('sidebar-resizing');
                e.preventDefault();
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const delta = e.clientX - startClientX;
                let newWidth = initialWidth + delta;

                if (newWidth < 120) {
                    if (!appSidebar.classList.contains('collapsed')) {
                        appSidebar.classList.add('collapsed');
                        appSidebar.style.removeProperty('width');
                        appSidebar.style.removeProperty('min-width');
                        sidebarToggleBtn.setAttribute('title', 'Perluas Sidebar (Geser ke Kanan)');
                        try { localStorage.setItem('mapin_sidebar_collapsed', 'true'); } catch (err) {}
                    }
                } else {
                    if (appSidebar.classList.contains('collapsed')) {
                        appSidebar.classList.remove('collapsed');
                        sidebarToggleBtn.setAttribute('title', 'Ciutkan Sidebar (Geser ke Kiri)');
                        try { localStorage.setItem('mapin_sidebar_collapsed', 'false'); } catch (err) {}
                    }
                    newWidth = Math.max(160, Math.min(newWidth, 380));
                    appSidebar.style.width = newWidth + 'px';
                    appSidebar.style.minWidth = newWidth + 'px';
                    try { localStorage.setItem('mapin_sidebar_width', Math.round(newWidth)); } catch (err) {}
                }
            });

            document.addEventListener('mouseup', () => {
                if (!isDragging) return;
                isDragging = false;
                sidebarResizer.classList.remove('is-resizing');
                document.body.classList.remove('sidebar-resizing');
                appSidebar.style.removeProperty('transition');
            });
        }
    }

    window.calcAddC6();
    window.calcEditC6();
});

