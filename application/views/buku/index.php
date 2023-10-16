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
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
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
                            <h1 class="d-flex flex-column text-dark fw-bold my-0 fs-1">Management Buku</h1>
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
                                    <form method="post" action="<?= base_url('buku/search'); ?>">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchUser" name="keyword" placeholder="Search book...">
                                            <button type="submit" class="btn btn-primary" id="btnSearch">Search</button>
                                            <?php if (isset($searched) && $searched === true) : ?>
                                                <a href="<?= base_url('buku/index'); ?>" class="btn btn-secondary">Back</a>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                    <!--end::Search-->
                                    <?= $this->session->flashdata('message'); ?>
                                    <!--begin::Toolbar-->
                                    <!-- Add this button to your HTML -->

                                    <!--end::Toolbar-->

                                </div>


                                <!-- Button trigger modal -->
                                <div class="px-7 py-5">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                        Tambah Buku
                                    </button>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Buku</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="<?= base_url('buku/create'); ?>" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="judul_buku" aria-label="Sizing example input" placeholder="Judul Buku" aria-describedby="inputGroup-sizing-default">
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="inputGroupSelect01">Kategori</label>
                                                        <select class="form-select" id="inputGroupSelect01" name="id_kategori_buku">
                                                            <?php foreach ($data_kategori_buku as $kategori) : ?>
                                                                <option value="<?= $kategori->id_kategori_buku; ?>"><?= $kategori->nama_kategori; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="file" class="form-control" name="userfile" accept="image/*" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Detail Buku -->
                                <?php foreach ($data_buku as $buku) : ?>
                                    <div class="modal fade" id="detailModal<?= $buku->id_buku; ?>" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <?php if (!empty($buku->foto)) : ?>
                                                            <img src="<?= base_url('image/buku/' . $buku->foto); ?>" alt="<?= $buku->nama_buku; ?>" class="img-thumbnail" style="max-width: 200px;">
                                                        <?php else : ?>
                                                            No Photo
                                                        <?php endif; ?>
                                                    </div>
                                                    <p><strong>Nama Buku:</strong> <?= $buku->nama_buku; ?></p>
                                                    <p><strong>Kategori Buku:</strong> <?= $buku->nama_kategori; ?></p>

                                                    <!-- Menampilkan QR code di bawah ini -->
                                                    <div id="QRCode<?= $buku->id_buku; ?>"></div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- JavaScript untuk menghasilkan QR code -->
                                    <script>
                                        // Panggil fungsi generateQRCodeForSharing dengan data yang sesuai (misalnya, URL ke detail buku) untuk setiap buku
                                        generateQRCodeForSharing("URL?id=<?= $buku->id_buku; ?>", "QRCode<?= $buku->id_buku; ?>");
                                    </script>

                                <?php endforeach; ?>


                                <!-- Modal Konfirmasi Delete -->
                                <?php foreach ($data_buku as $buku) : ?>
                                    <div class="modal fade" id="confirmDeleteModal<?= $buku->id_buku; ?>" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus Buku</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus buku dengan judul: <strong><?= $buku->nama_buku; ?></strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <a href="<?= base_url('buku/delete/' . $buku->id_buku); ?>" class="btn btn-danger">Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!--begin::Card title-->

                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body py-4">
                                <!--begin::Table-->

                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <!-- Kolom QR Code -->

                                            <!-- Kolom lainnya -->
                                            <th class="min-w-125px">Id</th>
                                            <th class="min-w-125px">Kategori</th>
                                            <th class="min-w-125px">Judul Buku</th>
                                            <th class="min-w-125px">Photo</th>
                                            <th class="text-end min-w-100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        <?php foreach ($data_buku as $buku) : ?>
                                            <tr>

                                                <!-- Kolom lainnya -->
                                                <td><?= $buku->id_buku; ?></td>
                                                <td><?= $buku->nama_kategori; ?></td>
                                                <td><?= $buku->nama_buku; ?></td>

                                                <td>
                                                    <!-- Display the book's photo here -->
                                                    <?php if (!empty($buku->foto)) : ?>
                                                        <img src="<?= base_url('image/buku/' . $buku->foto); ?>" alt="<?= $buku->nama_buku; ?>" class="img-thumbnail" style="max-width: 100px;">
                                                    <?php else : ?>
                                                        No Photo
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Kolom QR Code -->

                                                <td class="text-end">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?= $buku->id_buku; ?>">
                                                        Detail
                                                        <i class="ki ki-bold-arrow-right fs-5 ms-1"></i>
                                                    </button>

                                                    <!-- Action Menu -->
                                                    <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                    </a>
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

                                <div class="ms-2">
                                    <!--begin::Name-->

                                    <a href="<?= base_url('buku/komentar'); ?>" class="text-black-800 fs-6 fw-bold lh-1">Komentar</a>
                                    <!--end::Name-->
                                </div>
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
    <script>
        // Fungsi untuk menghasilkan QR code
        function generateQRCodeForSharing(data, elementId) {
            var qrcode = new QRCode(document.getElementById(elementId), {
                text: data,
                width: 128,
                height: 128,
            });
        }

        <?php foreach ($data_buku as $buku) : ?>
            // Panggil fungsi generateQRCodeForSharing dengan data yang sesuai (misalnya, URL ke detail buku) untuk setiap buku
            generateQRCodeForSharing("URL?id=<?= $buku->id_buku; ?>", "QRCode<?= $buku->id_buku; ?>");
        <?php endforeach; ?>
    </script>