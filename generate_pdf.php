<?php
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Capture posted data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $email     = $_POST['email'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $template  = $_POST['template'] ?? '';
    $sections  = $_POST['sections'] ?? [];

    // Create HTML content
    $html = "
        <h1>Resume - {$full_name}</h1>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Template:</strong> {$template}</p>
        <hr>
        <h2>Experience</h2><p>{$sections['experience']}</p>
        <h2>Education</h2><p>{$sections['education']}</p>
        <h2>Skills</h2><p>{$sections['skills']}</p>
    ";

    // Initialize Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Force browser download
    $dompdf->stream("CV_{$full_name}.pdf", [
        "Attachment" => true // Change to false to open in browser
    ]);
    exit;
} else {
    echo "Invalid request.";
}
