<?php
require 'config/db.php';

$results = [];
$search = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    if ($search !== '') {
        $stmt = $conn->prepare("
            SELECT full_name, phone 
            FROM members 
            WHERE full_name LIKE ?
            ORDER BY full_name ASC
        ");
        $like = "%".$search."%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Contact Search</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
<div class="container my-5">

<h3 class="mb-4 text-primary">
<i class="bi bi-search"></i> Search Member Contact
</h3>

<form method="GET" class="row g-3 mb-4">
  <div class="col-md-8">
    <input type="text"
           name="search"
           class="form-control"
           placeholder="Enter member name"
           value="<?= htmlspecialchars($search) ?>"
           required>
  </div>
  <div class="col-md-4">
    <button class="btn btn-primary w-100">
      <i class="bi bi-search"></i> Search
    </button>
  </div>
</form>

<?php if ($search !== ''): ?>

  <?php if ($results && $results->num_rows > 0): ?>
    <div class="card shadow">
      <div class="card-body">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Mobile Number</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
              <td>
                <i class="bi bi-person-fill text-primary"></i>
                <?= htmlspecialchars($row['full_name']) ?>
              </td>
              <td>
                <i class="bi bi-telephone-fill text-success"></i>
                <?= htmlspecialchars($row['phone']) ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      No member found with this name
    </div>
  <?php endif; ?>

<?php endif; ?>

<a href="index.php" class="btn btn-secondary mt-4">
<i class="bi bi-arrow-left"></i> Back
</a>

</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
