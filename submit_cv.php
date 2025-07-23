<?php
require 'config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $email     = $_POST['email'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $template  = $_POST['template'] ?? '';
    $sections  = $_POST['sections'] ?? [];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, template) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $phone, $template]);
        $user_id = $pdo->lastInsertId();

        $sectionStmt = $pdo->prepare("INSERT INTO user_data (user_id, section_type, content) VALUES (?, ?, ?)");
        foreach ($sections as $type => $content) {
            $sectionStmt->execute([$user_id, $type, $content]);
        }

        echo json_encode(['status' => 'success', 'message' => 'تم إرسال السيرة الذاتية بنجاح!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'حدث خطأ أثناء الحفظ.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير صالح.']);
}
?>
