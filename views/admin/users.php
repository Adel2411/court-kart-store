<h1>User Management</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Registered</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <tr>
                <td><?= $i ?></td>
                <td>User <?= $i ?></td>
                <td>user<?= $i ?>@example.com</td>
                <td><?= $i <= 8 ? 'User' : 'Admin' ?></td>
                <td><?= date('Y-m-d', strtotime("-".rand(1, 100)." days")) ?></td>
                <td>
                    <a href="#" class="btn">Edit</a>
                    <a href="#" class="btn">Delete</a>
                </td>
            </tr>
        <?php endfor; ?>
    </tbody>
</table>
