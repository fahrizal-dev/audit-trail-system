<?php
$no = ($page - 1) * $per_page + 1;

if (empty($log_api)): ?>
    <tr><td colspan="6" class="text-center muted">No records</td></tr>
<?php else:
    foreach ($log_api as $l): ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($l->waktu_akses) ?></td>
  <td><?= htmlspecialchars($l->ip_address) ?></td>
  <td><?= htmlspecialchars($l->metode) ?></td>
  <td><pre><?= htmlspecialchars($l->request) ?></pre></td>
  <td><pre><?= htmlspecialchars($l->response) ?></pre></td>
</tr>
<?php endforeach; endif; ?>