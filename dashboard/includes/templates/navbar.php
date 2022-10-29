<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="items.php?do=add">Items</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="categories.php?do=add">Categories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php?do=manage">Comments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php?do=edit&userid=<?php echo $_SESSION['id'] ?>">Edit</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

