<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<button class="sidebar-toggle d-lg-none position-fixed top-0 start-0 m-3" type="button" id="sidebarToggle">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar p-3 text-white" id="sidebar">
    <div class="d-lg-none text-end">
        <button class="btn btn-close btn-close-white" id="closeSidebar"></button>
    </div>
    <h4 class="text-center mb-4 title">Admin Dashboard</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="users.php"><i class="bi bi-people me-2"></i>User Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="active_order.php"><i class="bi bi-cart-check me-2"></i>Active Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="completed_order.php"><i class="bi bi-cart-dash me-2"></i>Completed Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="failed_order.php"><i class="bi bi-cart-dash me-2"></i>Failed Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="return_order.php"><i class="bi bi-cart-dash me-2"></i>Returned Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="cancal_order.php"><i class="bi bi-cart-dash me-2"></i>Cancaled Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="manual_order.php"><i class="bi bi-pencil-square me-2 me-2"></i>Manual Orders 1</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="manual_nonRegistered_customer.php"><i class="bi bi-pencil-square me-2 me-2"></i>Manual Orders 2</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="product.php"><i class="bi bi-box-seam me-2"></i>Product</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="category.php"><i class="bi bi-tags me-2"></i>Category</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="stock.php"><i class="bi bi-graph-up me-2"></i>Stock</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="discount.php"><i class="bi bi-percent me-2"></i>Discount Item</a>
        </li>
        <li class="nav-item mt-auto">
            <a class="nav-link text-white" href="log_out.php"><i class="bi bi-box-arrow-left me-2"></i>Log Out</a>
        </li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar       = document.getElementById('sidebar');
        const toggleBtn     = document.getElementById('sidebarToggle');
        const closeBtn      = document.getElementById('closeSidebar');

        toggleBtn.addEventListener('click', () => sidebar.classList.add('open'));
        if (closeBtn) closeBtn.addEventListener('click', () => sidebar.classList.remove('open'));

        document.addEventListener('click', e => {
            if (window.innerWidth < 992 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    });
</script>

<style>
    @media (max-width: 991px) {
        .sidebar {
            transform: translateX(-1000px); /* Match width */
            width: 100%;
        }
        .sidebar.open {
            transform: translateX(0);
        }
    }
    @media (min-width: 820) {
        .sidebar {
            transform: translateX(0) !important;
        }
    }
</style>