<?php
/*
 * header.php
 * Protita page er upor ongsho: design (CSS), menu ar message.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masjid Management System</title>

  <style>
    body { font-family: Arial, sans-serif; background: #eaf2fc; color: #14263f; margin: 0; }

    /* Upore header. Background pic alada file: images/pic4.jpg */
    .header {
      background: #0f3a75 url("images/pic4.jpg") center / cover no-repeat;
      color: white;
      text-align: center;
      padding: 50px 20px;
      border-bottom: 5px solid #3b9eff;
    }
    .header h1 { margin: 0; }

    /* Menu */
    .nav { background: white; text-align: center; padding: 10px; border-bottom: 1px solid #d3e0f0; }
    .nav a { display: inline-block; padding: 8px 18px; color: #0f3a75; text-decoration: none; font-weight: bold; }
    .nav a:hover { background: #eaf2fc; }

    .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
    h2 { color: #0f3a75; }

    /* Message (ok = shobuj/neel, err = lal) */
    .msg { padding: 12px; border-radius: 6px; margin-bottom: 15px; }
    .ok  { background: #e1eefc; color: #0f3a75; }
    .err { background: #fbe0de; color: #8a1f17; }

    /* Form */
    .form { background: white; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
    .form label { display: block; margin-top: 10px; font-size: 14px; color: #5b6b80; }
    .form input, .form select { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box;
                                border: 1px solid #c3d3e8; border-radius: 6px; }
    button { background: #1d6fd6; color: white; border: none; padding: 9px 20px;
             border-radius: 6px; cursor: pointer; margin-top: 14px; }
    .cancel { margin-left: 10px; color: #5b6b80; }

    /* Table */
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
    th { background: #0f3a75; color: white; text-align: left; padding: 10px; }
    td { padding: 10px; border-top: 1px solid #d3e0f0; }
    .del { background: white; color: #a3281f; border: 1px solid #e3b3af; padding: 3px 10px; margin: 0; }
    .edit { color: #1d6fd6; margin-right: 10px; }

    /* JOIN query dekhanor box */
    pre { background: #14263f; color: #d6e8ff; padding: 12px; border-radius: 6px; overflow-x: auto; }
  </style>
</head>
<body>

  <div class="header">
    <h1>Masjid Management System</h1>
  </div>

   <div class="nav">
    <a href="members.php">Members</a>
    <?php /* ekhon shudhu Members dekhabo. Pore lagle niche duto line ager moto kore dio:
    <a href="events.php">Events</a>
    <a href="donations.php">Donations</a>
    */ ?>
  </div>
  <div class="container">

    <?php
    // Kaj sesh hole URL e ?msg=... ba ?err=... thake, seta ekhane dekhay
    if (isset($_GET['msg'])) echo '<div class="msg ok">'  . e($_GET['msg']) . '</div>';
    if (isset($_GET['err'])) echo '<div class="msg err">' . e($_GET['err']) . '</div>';
    ?>