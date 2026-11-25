<?php
/*
 * donations.php  -  JOIN concept
 * ------------------------------------------------------
 * donation table e shudhu member_id ar event_id (number) thake.
 * Naam ar phone number dekhanor jonno JOIN diye member ar event table er sathe milano hoy.
 * Ekhane: add kora, JOIN diye dekha, ar delete kora jay.
 */
require "config.php";

// ---------- ADD : notun donation ----------
if (isset($_POST['add'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO donation (member_id, event_id, amount, donation_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['member_id'], $_POST['event_id'], $_POST['amount'], $_POST['donation_date']]);
        header("Location: donations.php?msg=" . urlencode("Donation add hoyeche."));
    } catch (PDOException $ex) {
        header("Location: donations.php?err=" . urlencode("Donation add kora gelo na. Amount 0 er beshi hote hobe."));
    }
    exit;
}

// ---------- DELETE ----------
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM donation WHERE donation_id = ?");
    $stmt->execute([$_POST['donation_id']]);
    header("Location: donations.php?msg=" . urlencode("Donation delete hoyeche."));
    exit;
}

// ---------- READ with JOIN ----------
// donation + member + event, tinta table ek sathe milano
$sql = "SELECT d.donation_id, m.name AS member_name, m.phone AS member_phone, e.event_name, d.amount, d.donation_date
FROM donation d
JOIN member m ON d.member_id = m.member_id
JOIN event  e ON d.event_id  = e.event_id
ORDER BY d.donation_id";
$donations = $pdo->query($sql)->fetchAll();

// Form er drop-down er jonno member ar event list
$members = $pdo->query("SELECT member_id, name FROM member ORDER BY name")->fetchAll();
$events  = $pdo->query("SELECT event_id, event_name, event_date FROM event ORDER BY event_date")->fetchAll();

require "header.php";
?>

<h2>Donations</h2>

<form method="post" class="form">
  <h3>Add new donation</h3>

  <label>Member (kon member dilo)</label>
  <select name="member_id" required>
    <?php foreach ($members as $m): ?>
      <option value="<?= e($m['member_id']) ?>"><?= e($m['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Event (kon event er jonno)</label>
  <select name="event_id" required>
    <?php foreach ($events as $ev): ?>
      <option value="<?= e($ev['event_id']) ?>"><?= e($ev['event_name']) ?> (<?= e($ev['event_date']) ?>)</option>
    <?php endforeach; ?>
  </select>

  <label>Amount (Tk)</label>
  <input type="number" name="amount" min="1" step="0.01" required>

  <label>Date</label>
  <input type="date" name="donation_date" value="<?= date('Y-m-d') ?>" required>

  <button type="submit" name="add">Save</button>
</form>

<!-- JOIN diye ana data -->
<table>
  <tr><th>ID</th><th>Member</th><th>Phone</th><th>Event</th><th>Amount (Tk)</th><th>Date</th><th>Action</th></tr>

  <?php foreach ($donations as $d): ?>
    <tr>
      <td><?= e($d['donation_id']) ?></td>
      <td><?= e($d['member_name']) ?></td>
      <td><?= e($d['member_phone']) ?></td>
      <td><?= e($d['event_name']) ?></td>
      <td><?= e(number_format($d['amount'], 2)) ?></td>
      <td><?= e($d['donation_date']) ?></td>
      <td>
        <form method="post" style="display:inline" onsubmit="return confirm('Delete this donation?');">
          <input type="hidden" name="donation_id" value="<?= e($d['donation_id']) ?>">
          <button class="del" type="submit" name="delete">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<!-- Ei table ta je JOIN query diye toiri hoyeche seta dekhano holo -->
<h3>JOIN query</h3>
<pre><?= e($sql) ?></pre>

<?php require "footer.php"; ?>