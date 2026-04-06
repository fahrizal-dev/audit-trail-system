<?php
$no = ($page - 1) * $per_page + 1;

if (empty($activity)): ?>
    <tr><td colspan="8" class="text-center muted">No records</td></tr>
<?php else:
    foreach ($activity as $a):
        $user = $a->user ?? $a->user_name ?? 'unknown';
        $status = $a->hasil ?? 'unknown';
        $badge_color = $status === 'success' ? 'var(--success)' : 'var(--danger)';
?>
<tr onclick="showActivityDetail(<?= (int)$a->id_activity ?>)">
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($a->modidate) ?></td>
  <td><span class="short"><?= htmlspecialchars($user) ?></span></td>
  <td><span class="short"><?= htmlspecialchars($a->menu_fitur) ?></span></td>
  <td><?= htmlspecialchars($a->aksi) ?></td>
  <td><span class="status-pill" style="background:<?= $badge_color ?>"><?= ucfirst($status) ?></span></td>
  <td><span class="short"><?= htmlspecialchars(substr($a->ket, 0, 120)) ?></span></td>
  <td><?= htmlspecialchars($a->ip_address) ?></td>
</tr>
<?php endforeach; endif; ?>