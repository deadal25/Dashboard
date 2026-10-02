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
    const filterCategory = document.getElementById('filter-category');
    const filterType = document.getElementById('filter-type');
    const trainingTable = document.getElementById('training-table-body');

    if (trainingTable && (searchTrainingInput || filterCategory || filterType)) {
        const filterRows = () => {
            const query = searchTrainingInput ? searchTrainingInput.value.toLowerCase() : '';
            const cat = filterCategory ? filterCategory.value : 'all';
            const type = filterType ? filterType.value : 'all';

            const rows = trainingTable.querySelectorAll('tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowCat = row.getAttribute('data-category') || '';
                const rowType = row.getAttribute('data-type') || '';

                const matchesSearch = text.includes(query);
                const matchesCat = (cat === 'all' || rowCat === cat);
                const matchesType = (type === 'all' || rowType === type);

                if (matchesSearch && matchesCat && matchesType) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        };

        if (searchTrainingInput) searchTrainingInput.addEventListener('input', filterRows);
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
                modal.classList.add('show');
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-backdrop');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });

    // Close modal when clicking on backdrop outside modal card
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.classList.remove('show');
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

            document.getElementById('edit-date').value = opt.getAttribute('data-date') || '';
            document.getElementById('edit-dept').value = opt.getAttribute('data-dept') || '';
            document.getElementById('edit-pos').value = opt.getAttribute('data-pos') || '';
            document.getElementById('edit-grade').value = opt.getAttribute('data-grade') || '';
            document.getElementById('edit-type').value = opt.getAttribute('data-type') || 'Kenaikan Pangkat Reguler';
            document.getElementById('edit-notes').value = opt.getAttribute('data-notes') || '';
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
            if (posInput) posInput.value = position;

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
            if (jcInput) jcInput.value = jobclass;

            const gradeInput = document.getElementById('edit-emp-grade');
            if (gradeInput) gradeInput.value = grade;

            const gsInput = document.getElementById('edit-emp-grade-since');
            if (gsInput) gsInput.value = gradeSince;

            const psInput = document.getElementById('edit-emp-position-since');
            if (psInput) psInput.value = positionSince;

            window.openModal('modal-edit-karyawan');
        });
    });

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

    // 11.2 Pre-select section when clicking quick action buttons
    document.querySelectorAll('[data-preselect-section]').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.getAttribute('data-preselect-section');
            const targetModalId = btn.getAttribute('data-modal-target');
            const targetModal = document.getElementById(targetModalId);
            if (targetModal) {
                const switcher = targetModal.querySelector('.section-switcher');
                if (switcher) {
                    switcher.value = section;
                    switcher.dispatchEvent(new Event('change'));
                }
            }
        });
    });

    // 11.3 C4 Kekuatan Utama: Pre-fill Edit and Action
    const selectEditC4 = document.getElementById('select-edit-c4-item');
    const formEditC4 = document.getElementById('form-edit-c4');
    if (selectEditC4 && formEditC4) {
        const updateC4Edit = () => {
            const opt = selectEditC4.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditC4.action = `/karyawan/${nik}/key-strength/${id}`;
            const strInput = document.getElementById('edit-c4-strength');
            const descInput = document.getElementById('edit-c4-desc');
            const srcInput = document.getElementById('edit-c4-source');
            if (strInput) strInput.value = opt.getAttribute('data-strength') || '';
            if (descInput) descInput.value = opt.getAttribute('data-desc') || '';
            if (srcInput) srcInput.value = opt.getAttribute('data-source') || '';
        };
        selectEditC4.addEventListener('change', updateC4Edit);
        updateC4Edit();
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

    // 11.5 C6 Riwayat POTASS: Pre-fill Edit and Action
    const selectEditC6 = document.getElementById('select-edit-c6-item');
    const formEditC6 = document.getElementById('form-edit-c6');
    if (selectEditC6 && formEditC6) {
        const updateC6Edit = () => {
            const opt = selectEditC6.selectedOptions[0];
            if (!opt) return;
            const id = opt.value;
            const nik = getCurrentEmployeeNik();
            formEditC6.action = `/karyawan/${nik}/talent-assessment/${id}`;
            const dateInput = document.getElementById('edit-c6-date');
            const posInput = document.getElementById('edit-c6-pos');
            const scoreInput = document.getElementById('edit-c6-score');
            const catInput = document.getElementById('edit-c6-cat');
            const assessorInput = document.getElementById('edit-c6-assessor');
            if (dateInput) dateInput.value = opt.getAttribute('data-date') || '';
            if (posInput) posInput.value = opt.getAttribute('data-pos') || '';
            if (scoreInput) scoreInput.value = opt.getAttribute('data-score') || '';
            if (catInput) catInput.value = opt.getAttribute('data-cat') || 'Average';
            if (assessorInput) assessorInput.value = opt.getAttribute('data-assessor') || 'HR Development';
        };
        selectEditC6.addEventListener('change', updateC6Edit);
        updateC6Edit();
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
            if (jcInput) jcInput.value = opt.getAttribute('data-class') || '';
            if (grInput) grInput.value = opt.getAttribute('data-grade') || '';
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
            const currInput = document.getElementById('edit-gap-curr');
            const currDescInput = document.getElementById('edit-gap-currdesc');
            const stdInput = document.getElementById('edit-gap-std');
            const stdDescInput = document.getElementById('edit-gap-stddesc');
            const impInput = document.getElementById('edit-gap-improvement');
            if (compInput) compInput.value = opt.getAttribute('data-comp') || '';
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



