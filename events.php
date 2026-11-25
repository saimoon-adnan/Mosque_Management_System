<?php
/*
 * events.php  -  CRUD (Create, Read, Update, Delete)
 * ------------------------------------------------------
 * event table er shob kaj ekhane hoy. members.php er ekoi pattern.
 */
require "config.php";

// ---------- 1. CREATE ----------
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO event (event_name, event_date, location) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['event_name'], $_POST['event_date'], $_POST['location']]);
    header("Location: events.php?msg=" . urlencode("Event add hoyeche."));
    exit;
}

// ---------- 3. UPDATE ----------
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE event SET event_name = ?, event_date = ?, location = ? WHERE event_id = ?");
    $stmt->execute([$_POST['event_name'], $_POST['event_date'], $_POST['location'], $_POST['event_id']]);
    header("Location: events.php?msg=" . urlencode("Event update hoyeche."));
    exit;
}

// ---------- 4. DELETE ----------
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM event WHERE event_id = ?");
        $stmt->execute([$_POST['event_id']]);
        header("Location: events.php?msg=" . urlencode("Event delete hoyeche."));
    } catch (PDOException $ex) {
        // event er donation thakle database delete korte dey na (foreign key)
        header("Location: events.php?err=" . urlencode("Eta delete kora jabe na, karon ei event er donation ache."));
    }
    exit;
}

// ---------- 2. READ ----------
$events = $pdo->query("SELECT * FROM event ORDER BY event_id")->fetchAll();

// Edit mode
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM event WHERE event_id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

require "header.php";
?>

<h2>Events</h2>

<form method="post" class="form">
  <h3><?= $edit ? "Edit event" : "Add new event" ?></h3>

  <?php if ($edit): ?>
    <input type="hidden" name="event_id" value="<?= e($edit['event_id']) ?>">
  <?php endif; ?>

  <label>Event name</label>
  <input type="text" name="event_name" required value="<?= e($edit['event_name'] ?? '') ?>">

  <label>Date</label>
  <input type="date" name="event_date" required value="<?= e($edit['event_date'] ?? date('Y-m-d')) ?>">

  <label>Location</label>
  <input type="text" name="location" value="<?= e($edit['location'] ?? '') ?>">

  <button type="submit" name="<?= $edit ? 'update' : 'add' ?>">Save</button>
  <?php if ($edit): ?><a class="cancel" href="events.php">Cancel</a><?php endif; ?>
</form>

<table>
  <tr><th>ID</th><th>Event</th><th>Date</th><th>Location</th><th>Action</th></tr>

  <?php foreach ($events as $ev): ?>
    <tr>
      <td><?= e($ev['event_id']) ?></td>
      <td><?= e($ev['event_name']) ?></td>
      <td><?= e($ev['event_date']) ?></td>
      <td><?= e($ev['location']) ?></td>
      <td>
        <a class="edit" href="events.php?edit=<?= e($ev['event_id']) ?>">Edit</a>

        <form method="post" style="display:inline" onsubmit="return confirm('Delete this event?');">
          <input type="hidden" name="event_id" value="<?= e($ev['event_id']) ?>">
          <button class="del" type="submit" name="delete">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require "footer.php"; ?>