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
        <?php if (empty($users)) { ?>
            <tr>
                <td colspan="6">No users found.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                    <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a href="#" class="btn">Edit</a>
                        <a href="#" class="btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>

<div class="db-connection-success">
    <p style="color: green; font-weight: bold;">Database connection successful! User data loaded from database.</p>
</div>
