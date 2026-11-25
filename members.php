<?php
/*
 * members.php  -  CRUD (Create, Read, Update, Delete)
 * ------------------------------------------------------
 * member table er shob kaj ekhane hoy.
 * ? chinho (placeholder) prepared statement er jonno, eta SQL injection theke bachay.
 */
require "config.php";

// ---------- 1. CREATE : "Save" chaple notun member add hoy ----------
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO member (name, phone, address, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['address'], $_POST['role']]);
    header("Location: members.php?msg=" . urlencode("Member add hoyeche."));
    exit;
}

// ---------- 3. UPDATE : Edit kore "Save" chaple purono member bodle jay ----------
if (isset($_POST['update'])) {
    $stmt = $pdo->prepare("UPDATE member SET name = ?, phone = ?, address = ?, role = ? WHERE member_id = ?");
    $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['address'], $_POST['role'], $_POST['member_id']]);
    header("Location: members.php?msg=" . urlencode("Member update hoyeche."));
    exit;
}

// ---------- 4. DELETE : "Delete" chaple member muche jay ----------
if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM member WHERE member_id = ?");
        $stmt->execute([$_POST['member_id']]);
        header("Location: members.php?msg=" . urlencode("Member delete hoyeche."));
    } catch (PDOException $ex) {
        // member er donation thakle database delete korte dey na (foreign key)
        header("Location: members.php?err=" . urlencode("Eta delete kora jabe na, karon ei member er donation ache."));
    }
    exit;
}

// ---------- 2. READ : shob member database theke ana ----------
$members = $pdo->query("SELECT * FROM member ORDER BY member_id")->fetchAll();

// Edit button chaple (members.php?edit=3) oi member er tothyo form e boshbe
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM member WHERE member_id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch();
}

$roles = ["Imam", "Muazzin", "Committee", "Regular"];

require "header.php";
?>

<h2>Members</h2>

<!-- Add / Edit form: $edit thakle "Edit", na thakle "Add" -->
<form method="post" class="form">
  <h3><?= $edit ? "Edit member" : "Add new member" ?></h3>

  <?php if ($edit): ?>
    <input type="hidden" name="member_id" value="<?= e($edit['member_id']) ?>">
  <?php endif; ?>

  <label>Name</label>
  <input type="text" name="name" required value="<?= e($edit['name'] ?? '') ?>">

  <label>Phone</label>
  <input type="text" name="phone" value="<?= e($edit['phone'] ?? '') ?>">

  <label>Address</label>
  <input type="text" name="address" value="<?= e($edit['address'] ?? '') ?>">

  <label>Role</label>
  <select name="role">
    <?php foreach ($roles as $r): ?>
      <option <?= (($edit['role'] ?? '') == $r) ? 'selected' : '' ?>><?= e($r) ?></option>
    <?php endforeach; ?>
  </select>

  <button type="submit" name="<?= $edit ? 'update' : 'add' ?>">Save</button>
  <?php if ($edit): ?><a class="cancel" href="members.php">Cancel</a><?php endif; ?>
</form>

<!-- Member er table -->
<table>
  <tr><th>ID</th><th>Name</th><th>Phone</th><th>Address</th><th>Role</th><th>Action</th></tr>

  <?php foreach ($members as $m): ?>
    <tr>
      <td><?= e($m['member_id']) ?></td>
      <td><?= e($m['name']) ?></td>
      <td><?= e($m['phone']) ?></td>
      <td><?= e($m['address']) ?></td>
      <td><?= e($m['role']) ?></td>
      <td>
        <a class="edit" href="members.php?edit=<?= e($m['member_id']) ?>">Edit</a>

        <form method="post" style="display:inline" onsubmit="return confirm('Delete this member?');">
          <input type="hidden" name="member_id" value="<?= e($m['member_id']) ?>">
          <button class="del" type="submit" name="delete">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php require "footer.php"; ?>