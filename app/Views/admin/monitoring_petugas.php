<?php
$active='monitoring';
$labels=['DITUGASKAN'=>'Ditugaskan','DIKERJAKAN'=>'Dikerjakan','SELESAI'=>'Selesai','DIBATALKAN'=>'Dibatalkan'];
$ringkasan=array_fill_keys(array_keys($labels),0);
foreach($tugas as $t) if(isset($ringkasan[$t['status']])) $ringkasan[$t['status']]++;
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>EcoCampus</title>
<style>
*{box-sizing:border-box}body{margin:0;font:15px Arial,sans-serif;background:#e2f0e7;color:#153d2c}.layout{display:flex;min-height:100vh}.side{width:220px;flex-shrink:0;background:#10462e;color:white;padding:24px 16px}.side a{display:block;color:white;text-decoration:none;padding:13px;border-radius:9px;margin:8px 0}.side a:hover,.side a.active{background:#21854b}main{flex:1;min-width:0;padding:30px}h1{margin-top:0}.cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.card,.panel{background:white;border-radius:16px;padding:22px}.card strong{font-size:30px}.panel{margin-top:20px;overflow-x:auto}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:12px;border-bottom:1px solid #dce8df}th{background:#edf6ef}.badge{background:#e5f3e9;border-radius:16px;padding:5px 9px;display:inline-block}.chart{height:180px;display:flex;align-items:stretch;gap:12px}.barcol{flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:5px}.bar{width:28px;background:#8ac79c;min-height:2px}.muted{color:#496b5a}@media(max-width:750px){.layout{display:block}.side{width:100%}.cards{grid-template-columns:repeat(2,1fr)}main{padding:16px}}
</style></head><body><div class="layout"><aside class="side"><h2>EcoCampus</h2><a class="<?= $active==='dashboard'?'active':'' ?>" href="<?= site_url('admin/dashboard') ?>">Ringkasan</a><a class="<?= $active==='monitoring'?'active':'' ?>" href="<?= site_url('admin/monitoring-petugas') ?>">Petugas</a></aside><main>
<h1>Monitoring Petugas</h1><p class="muted">Pantau tugas penanganan laporan kebersihan.</p>
<div class="cards"><?php foreach($ringkasan as $k=>$v): ?><div class="card"><p><?= esc($labels[$k]) ?></p><strong><?= $v ?></strong></div><?php endforeach ?></div>
<div class="panel"><h2>Daftar Tugas Petugas</h2><p>Total tugas: <?= count($tugas) ?></p>
<table><thead><tr><th>No.</th><th>Petugas</th><th>Laporan</th><th>Prioritas</th><th>Status</th></tr></thead><tbody>
<?php foreach($tugas as $i=>$t): ?><tr><td><?= $i+1 ?></td><td><?= esc($t['nama_petugas']??'-') ?></td><td><?= esc($t['judul_laporan']??'-') ?></td><td><?= esc($t['prioritas']) ?></td><td><span class="badge"><?= esc($labels[$t['status']]??$t['status']) ?></span></td></tr><?php endforeach ?>
<?php if(!$tugas): ?><tr><td colspan="5">Belum ada tugas.</td></tr><?php endif ?></tbody></table></div></main></div></body></html>