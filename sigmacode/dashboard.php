<?php
    session_start();
    if(isset($_POST['logout'])) {
        session_destroy();
        header("location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - CRUD</title>
    <link rel="stylesheet" href="layout/dashboard.css" />
    <link rel="stylesheet" href="layout/animated-text.css" />
    <style>
        /* Additional CSS for CRUD UI */
        .crud-container {
            background: white;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .crud-header {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .crud-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .crud-form input[type="text"] {
            flex-grow: 1;
            padding: 12px 15px;
            font-size: 16px;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }
        .crud-form input[type="text"]:focus {
            border-color: rgb(52, 15, 185);
            outline: none;
        }
        .crud-form button {
            padding: 12px 20px;
            background: linear-gradient(135deg, rgb(52, 15, 185), rgb(67, 97, 238));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .crud-form button:hover {
            background: linear-gradient(135deg, rgb(40, 10, 160), rgb(52, 82, 220));
        }
        .crud-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .crud-list li {
            background: #f8f9fa;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .crud-list li .item-text {
            flex-grow: 1;
            font-size: 16px;
            color: #333;
        }
        .crud-list li button {
            margin-left: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: rgb(52, 15, 185);
            transition: color 0.3s ease;
        }
        .crud-list li button:hover {
            color: rgb(40, 10, 160);
        }
    </style>
</head>
<body>
    <?php include "layout/header.html" ?>

    <h3 class="animated-text fade-in">Selamat Datang <?= htmlspecialchars($_SESSION['username']) ?></h3>
    <form action="dashboard.php" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>

    <div class="crud-container" aria-label="CRUD Interface">
        <div class="crud-header animated-text fade-in">My Awesome List</div>
        <form id="crud-form" class="crud-form" aria-label="Add new item form">
            <input type="text" id="new-item-input" placeholder="Add new item..." aria-label="New item text" required />
            <button type="submit" aria-label="Add item">Add</button>
        </form>
        <ul id="crud-list" class="crud-list" aria-live="polite" aria-relevant="additions removals">
            <!-- List items will appear here -->
        </ul>
    </div>

    <script>
        // JavaScript for CRUD functionality
        const form = document.getElementById('crud-form');
        const input = document.getElementById('new-item-input');
        const list = document.getElementById('crud-list');

        // Load saved items from localStorage
        let items = JSON.parse(localStorage.getItem('crudItems')) || [];

        function saveItems() {
            localStorage.setItem('crudItems', JSON.stringify(items));
        }

        function renderList() {
            list.innerHTML = '';
            items.forEach((item, index) => {
                const li = document.createElement('li');
                li.setAttribute('data-index', index);

                const span = document.createElement('span');
                span.className = 'item-text';
                span.textContent = item;
                li.appendChild(span);

                const editBtn = document.createElement('button');
                editBtn.textContent = '✏️';
                editBtn.setAttribute('aria-label', 'Edit item');
                editBtn.addEventListener('click', () => editItem(index));
                li.appendChild(editBtn);

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = '🗑️';
                deleteBtn.setAttribute('aria-label', 'Delete item');
                deleteBtn.addEventListener('click', () => deleteItem(index));
                li.appendChild(deleteBtn);

                list.appendChild(li);
            });
        }

        function addItem(text) {
            items.push(text);
            saveItems();
            renderList();
        }

        function editItem(index) {
            const newText = prompt('Edit item:', items[index]);
            if (newText !== null && newText.trim() !== '') {
                items[index] = newText.trim();
                saveItems();
                renderList();
            }
        }

        function deleteItem(index) {
            if (confirm('Are you sure you want to delete this item?')) {
                items.splice(index, 1);
                saveItems();
                renderList();
            }
        }

        form.addEventListener('submit', e => {
            e.preventDefault();
            const text = input.value.trim();
            if (text !== '') {
                addItem(text);
                input.value = '';
                input.focus();
            }
        });

        // Initial render
        renderList();
    </script>

    <?php include "layout/footer.html" ?>
</body>
</html>
