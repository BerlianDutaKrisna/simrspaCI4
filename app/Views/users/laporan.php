<?= $this->include('templates/dashboard/header_dashboard'); ?>
<?= $this->include('templates/dashboard/navbar_dashboard'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buku Laporan</h6>
    </div>
    <div class="card-body">
        <h1>Buku Laporan Kinerja Users</h1>
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-primary mb-3"><i class="fas fa-reply"></i> Kembali</a>

        <form class="form-inline mb-4" method="get" action="<?= base_url('users/laporan') ?>">
            <div class="form-group mr-2">
                <label for="bulan" class="mr-2">Bulan:</label>
                <select name="bulan" id="bulan" class="form-control">
                    <option value="" <?= $bulan === null || $bulan === '' ? 'selected' : '' ?>>Semua Bulan</option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) :
                        $selected = (string)$i === (string)$bulan ? 'selected' : '';
                    ?>
                        <option value="<?= $i ?>" <?= $selected ?>>
                            <?= date("F", mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group mr-2">
                <label for="tahun" class="mr-2">Tahun:</label>
                <select name="tahun" id="tahun" class="form-control">
                    <option value="" <?= $tahun === null || $tahun === '' ? 'selected' : '' ?>>Semua Tahun</option>
                    <?php
                    for ($y = 2022; $y <= date('Y'); $y++) :
                        $selected = (string)$y === (string)$tahun ? 'selected' : '';
                    ?>
                        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </form>

        <div class="table-responsive mb-5">
            <h5 class="text-primary font-weight-bold">Data Dokter</h5>
            <table class="table table-bordered text-center" id="dataTableDokter" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>Pembacaan HPA</th>
                        <th>Pembacaan FRS</th>
                        <th>Pembacaan SRS</th>
                        <th>Pembacaan IHC</th>
                        <th>Authorized HPA</th>
                        <th>Authorized FRS</th>
                        <th>Authorized SRS</th>
                        <th>Authorized IHC</th>
                        <th><strong>Total Pekerjaan</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dokter)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($dokter as $row) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($row['nama_user']) ?></td>
                                <td><?= $row['pembacaan_hpa'] ?? 0 ?></td>
                                <td><?= $row['pembacaan_frs'] ?? 0 ?></td>
                                <td><?= $row['pembacaan_srs'] ?? 0 ?></td>
                                <td><?= $row['pembacaan_ihc'] ?? 0 ?></td>
                                <td><?= $row['authorized_hpa'] ?? 0 ?></td>
                                <td><?= $row['authorized_frs'] ?? 0 ?></td>
                                <td><?= $row['authorized_srs'] ?? 0 ?></td>
                                <td><?= $row['authorized_ihc'] ?? 0 ?></td>
                                <td><strong><?= array_sum([
                                                $row['pembacaan_hpa'] ?? 0,
                                                $row['pembacaan_frs'] ?? 0,
                                                $row['pembacaan_srs'] ?? 0,
                                                $row['pembacaan_ihc'] ?? 0,
                                                $row['authorized_hpa'] ?? 0,
                                                $row['authorized_frs'] ?? 0,
                                                $row['authorized_srs'] ?? 0,
                                                $row['authorized_ihc'] ?? 0,
                                            ]) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data dokter</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <h5 class="text-primary font-weight-bold">Data Analis</h5>
            <table class="table table-bordered text-center" id="dataTableAnalis" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>Penerimaan HPA</th>
                        <th>Penerimaan FRS</th>
                        <th>Penerimaan SRS</th>
                        <th>Penerimaan IHC</th>
                        <th>Pemotongan HPA</th>
                        <th>Pemrosesan HPA</th>
                        <th>Penanaman HPA</th>
                        <th>Pemotongan Tipis HPA</th>
                        <th>Pewarnaan HPA</th>
                        <th>Penulisan HPA</th>
                        <th>Penulisan FRS</th>
                        <th>Penulisan SRS</th>
                        <th>Penulisan IHC</th>
                        <th>Pemverifikasi HPA</th>
                        <th>Pemverifikasi FRS</th>
                        <th>Pemverifikasi SRS</th>
                        <th>Pemverifikasi IHC</th>
                        <th><strong>Total Pekerjaan</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($analis)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($analis as $row) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($row['nama_user']) ?></td>
                                <td><?= $row['penerimaan_hpa'] ?? 0 ?></td>
                                <td><?= $row['penerimaan_frs'] ?? 0 ?></td>
                                <td><?= $row['penerimaan_srs'] ?? 0 ?></td>
                                <td><?= $row['penerimaan_ihc'] ?? 0 ?></td>
                                <td><?= $row['pemotongan_hpa'] ?? 0 ?></td>
                                <td><?= $row['pemprosesan_hpa'] ?? 0 ?></td>
                                <td><?= $row['penanaman_hpa'] ?? 0 ?></td>
                                <td><?= $row['pemotongan_tipis_hpa'] ?? 0 ?></td>
                                <td><?= $row['pewarnaan_hpa'] ?? 0 ?></td>
                                <td><?= $row['penulisan_hpa'] ?? 0 ?></td>
                                <td><?= $row['penulisan_frs'] ?? 0 ?></td>
                                <td><?= $row['penulisan_srs'] ?? 0 ?></td>
                                <td><?= $row['penulisan_ihc'] ?? 0 ?></td>
                                <td><?= $row['pemverifikasi_hpa'] ?? 0 ?></td>
                                <td><?= $row['pemverifikasi_frs'] ?? 0 ?></td>
                                <td><?= $row['pemverifikasi_srs'] ?? 0 ?></td>
                                <td><?= $row['pemverifikasi_ihc'] ?? 0 ?></td>
                                <td><strong><?= array_sum([
                                                $row['penerimaan_hpa'] ?? 0,
                                                $row['penerimaan_frs'] ?? 0,
                                                $row['penerimaan_srs'] ?? 0,
                                                $row['penerimaan_ihc'] ?? 0,
                                                $row['pemotongan_hpa'] ?? 0,
                                                $row['pemprosesan_hpa'] ?? 0,
                                                $row['penanaman_hpa'] ?? 0,
                                                $row['pemotongan_tipis_hpa'] ?? 0,
                                                $row['pewarnaan_hpa'] ?? 0,
                                                $row['penulisan_hpa'] ?? 0,
                                                $row['penulisan_frs'] ?? 0,
                                                $row['penulisan_srs'] ?? 0,
                                                $row['penulisan_ihc'] ?? 0,
                                                $row['pemverifikasi_hpa'] ?? 0,
                                                $row['pemverifikasi_frs'] ?? 0,
                                                $row['pemverifikasi_srs'] ?? 0,
                                                $row['pemverifikasi_ihc'] ?? 0,
                                            ]) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="15" class="text-center">Tidak ada data analis</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('templates/notifikasi') ?>
<?= $this->include('templates/dashboard/footer_dashboard') ?>
<?= $this->include('templates/exam/cetak_pencarian'); ?>