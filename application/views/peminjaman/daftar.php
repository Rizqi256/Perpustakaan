<!--begin::Body-->

<body id="kt_body" class="header-fixed">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">

            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header mt-0 mt-lg-0 pt-lg-0" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{lg: '300px'}">
                    <!--begin::Container-->
                    <div class="container d-flex flex-stack flex-wrap gap-4" id="kt_header_container">
                        <!--begin::Page title-->
                        <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-10 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                            <!--begin::Heading-->
                            <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">Daftar Peminjaman</h1>
                            <!--end::Heading-->

                        </div>
                        <!--end::Page title=-->
                        <!--begin::Wrapper-->
                        <div class="d-flex d-lg-none align-items-center ms-n3 me-2">
                            <!--begin::Aside mobile toggle-->
                            <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_toggle">
                                <i class="ki-duotone ki-abstract-14 fs-1 mt-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Aside mobile toggle-->
                            <!--begin::Logo-->
                            <a href="../../demo3/dist/index.html" class="d-flex align-items-center">
                                <img alt="Logo" src="assets/media/logos/demo3.svg" class="theme-light-show h-20px" />
                                <img alt="Logo" src="assets/media/logos/demo3-dark.svg" class="theme-dark-show h-20px" />
                            </a>
                            <!--end::Logo-->
                        </div>
                        <!--end::Wrapper-->

                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Container-->
                    <div class="container-xxl" id="kt_content_container">
                        <!--begin::Card-->
                        <div class="card">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <!--begin::Search-->

                                    <!--end::Search-->
                                </div>
                                <!--begin::Card title-->
                                <!--begin::Toolbar-->
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <!--begin::Filter-->
                                    <form method="get" action="<?= base_url('peminjaman/daftar'); ?>">
                                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-duotone ki-filter fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Filter</button>
                                        <!--begin::Menu 1-->
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Separator-->
                                            <!--begin::Content-->
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                <!--begin::Input group-->
                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">Status:</label>
                                                    <select name="status" class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="status" data-hide-search="true">
                                                        <option value="">Semua</option>
                                                        <option value="Pinjam">Pinjam</option>
                                                        <option value="Kembali">Kembali</option>
                                                    </select>
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply</button>
                                                </div>
                                                <!--end::Actions-->
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                        <!--end::Menu 1-->
                                        <!--end::Filter-->
                                    </form>
                                </div>
                                <!--end::Toolbar-->



                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body py-4">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="peminjamanTable">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th>ID Peminjaman</th>
                                            <th>ID User</th>
                                            <th>Nama Buku</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Tanggal Pengembalian</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        <?php foreach ($data_peminjaman as $peminjaman) : ?>
                                            <tr>
                                                <td><?= $peminjaman->id_peminjaman; ?></td>
                                                <td><?= $peminjaman->user_name; ?></td>
                                                <td><?= $peminjaman->nama_buku; ?></td>
                                                <td><?= $peminjaman->tanggal_peminjaman; ?></td>
                                                <td><?= $peminjaman->tanggal_pengembalian; ?></td>
                                                <td><?= $peminjaman->status; ?></td>
                                                <td>
                                                    <?php if ($peminjaman->status === 'Pinjam') : ?>
                                                        <a href="<?= base_url('peminjaman/kembalikan_buku/' . $peminjaman->id_peminjaman); ?>" class="btn btn-secondary btn-info">Kembalikan</a>
                                                    <?php elseif ($peminjaman->status === 'Kembali') : ?>
                                                        <a href="<?= base_url('peminjaman/hapus_peminjaman/' . $peminjaman->id_peminjaman); ?>" class="btn btn-secondary btn-danger">Hapus</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"></div>
                                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                        <?php echo $this->pagination->create_links(); ?>
                                    </div>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container d-flex flex-column flex-md-row flex-stack">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-gray-400 fw-semibold me-1">Created by</span>
                            <a href="https://keenthemes.com" target="_blank" class="text-muted text-hover-primary fw-semibold me-2 fs-6">Keenthemes</a>
                        </div>
                        <!--end::Copyright-->
                        <!--begin::Menu-->
                        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                            <li class="menu-item">
                                <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
                            </li>
                        </ul>
                        <!--end::Menu-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!--end::Main-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Scrolltop-->