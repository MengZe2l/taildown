<?php
require '../../config.php';
require '../../src/database.php';
require '../../src/auth.php';
require '../../src/helpers.php';

$config = require '../../config.php';
$db = new Database($config);
$auth = new Auth($db);

if (!$auth->isAuthenticated()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $version = $_POST['version'];
        $changelog = $_POST['changelog'];
        $file_url = $_POST['file_url'];
        $db->query("INSERT INTO versions (version, changelog, file_url) VALUES (?, ?, ?)", [$version, $changelog, $file_url]);
        header('Location: versions.php');
        exit;
    }

    if ($action === 'edit') {
        $id = $_POST['id'];
        $version = $_POST['version'];
        $changelog = $_POST['changelog'];
        $db->query("UPDATE versions SET version = ?, changelog = ? WHERE id = ?", [$version, $changelog, $id]);
        header('Location: versions.php');
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'];
        $db->query("DELETE FROM versions WHERE id = ?", [$id]);
        header('Location: versions.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $upload_dir = 'uploads/';
    $target_file = $upload_dir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $file_url = 'https://' . $_SERVER['HTTP_HOST'] . '/public/admin/' . $target_file;
        echo json_encode(['success' => true, 'fileUrl' => $file_url]);
        exit;
    } else {
        echo json_encode(['success' => false]);
        exit;
    }
}

$items_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$total_items = $db->query("SELECT COUNT(*) AS count FROM versions")->fetch_assoc()['count'];
$total_pages = ceil($total_items / $items_per_page);
$versions = $db->query("SELECT * FROM versions ORDER BY created_at DESC LIMIT $offset, $items_per_page")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 版本管理</title>
    <link href="https://cdn.bootcdn.net/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        table td, table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-r from-indigo-100 via-purple-200 to-indigo-100">
<?php include('header.php'); ?>
    <main class="container mx-auto p-6 mt-6 bg-white shadow-lg rounded-lg">
        <section class="mt-12">
            <h2 class="text-2xl font-semibold mb-6">新增版本</h2>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add">
                <div class="mb-4">
                    <label for="version" class="block text-sm font-medium text-gray-600">版本号</label>
                    <input name="version" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="版本号" required>
                </div>
                <div class="mb-4">
                    <label for="changelog" class="block text-sm font-medium text-gray-600">更新日志</label>
                    <textarea name="changelog" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="更新日志" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-600">选择文件</label>
                    <button type="button" id="uploadButton" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-indigo-500 text-white">
                        上传文件
                    </button>
                </div>
                <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
                    <div class="bg-white p-6 rounded-lg w-96">
                        <h3 class="text-lg font-semibold">选择文件</h3>
                        <input type="file" id="fileInput" class="w-full p-3 mt-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <div class="flex justify-end mt-4">
                            <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg">取消</button>
                            <button type="button" id="confirmBtn" class="ml-2 px-4 py-2 bg-indigo-500 text-white rounded-lg">确定</button>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="file_url" class="block text-sm font-medium text-gray-600">文件链接</label>
                    <input name="file_url" id="file_url" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="文件链接" required>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-3 rounded-lg hover:bg-gradient-to-l focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    新增版本
                </button>
            </form>
        </section>
        <section class="mt-12">
            <h2 class="text-2xl font-semibold mb-6">已发布版本</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto bg-gray-50 shadow-md rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">版本号</th>
                            <th class="px-4 py-2 text-left">更新日志</th>
                            <th class="px-4 py-2 text-left">文件链接</th>
                            <th class="px-4 py-2 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($versions as $version): ?>
                        <tr class="border-b">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($version['id']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($version['version']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($version['changelog']); ?></td>
                            <td class="px-4 py-2">
                                <a href="<?php echo htmlspecialchars($version['file_url']); ?>" class="text-indigo-500 hover:text-indigo-600" target="_blank">下载链接</a>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button type="button" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition duration-200" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($version)); ?>)">编辑</button>
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="id" value="<?php echo $version['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200">删除</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-center space-x-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="px-4 py-2 rounded-lg <?php echo $page == $i ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-700'; ?> hover:bg-indigo-400 hover:text-white transition duration-200">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
            </div>
        </section>
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-lg font-semibold mb-4">编辑版本信息</h3>
        <form method="POST" id="editForm">
            <!-- Action for edit -->
            <input type="hidden" name="action" value="edit">
            <!-- ID field -->
            <input type="hidden" name="id" id="editId">
            <div class="mb-4">
                <label for="editVersion" class="block text-sm font-medium text-gray-600">版本号</label>
                <input name="version" id="editVersion" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="editChangelog" class="block text-sm font-medium text-gray-600">更新日志</label>
                <textarea name="changelog" id="editChangelog" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-200" onclick="closeEditModal()">取消</button>
                <button type="submit" class="ml-3 bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600 transition duration-200">保存</button>
            </div>
        </form>
    </div>
</div>
    </main>
<?php include('footer.php'); ?>

    <script>
        const uploadButton = document.getElementById('uploadButton');
        const uploadModal = document.getElementById('uploadModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const fileInput = document.getElementById('fileInput');
        const fileUrlInput = document.getElementById('file_url');
        const successTip = document.createElement('div');
        successTip.classList.add('fixed', 'bottom-4', 'left-1/2', 'transform', '-translate-x-1/2', 'bg-green-500', 'text-white', 'px-6', 'py-3', 'rounded-lg', 'hidden', 'z-50');
        document.body.appendChild(successTip);
        uploadButton.addEventListener('click', () => {
            uploadModal.classList.remove('hidden');
        });
        cancelBtn.addEventListener('click', () => {
            uploadModal.classList.add('hidden');
        });
        confirmBtn.addEventListener('click', () => {
            const file = fileInput.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('file', file);
                fetch('', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fileUrlInput.value = data.fileUrl;
                        uploadModal.classList.add('hidden');
                        successTip.textContent = '上传成功！';
                        successTip.classList.remove('hidden');
                        setTimeout(() => {
                            successTip.classList.add('hidden');
                        }, 3000);
                    } else {
                        alert('上传失败');
                    }
                });
            }
        });
        
    </script>
<script>
    function openEditModal(version) {
        document.getElementById('editId').value = version.id;
        document.getElementById('editVersion').value = version.version;
        document.getElementById('editChangelog').value = version.changelog;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
</body>
</html>
