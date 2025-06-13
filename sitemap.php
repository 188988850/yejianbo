<?php
require_once 'config.php';
header('Content-Type: application/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
$sql = "SELECT id, add_time FROM news ORDER BY add_time DESC";
$result = $db->query($sql);
while($row = $result->fetch_assoc()) {
    echo "<url>\n";
      $host = $_SERVER['HTTP_HOST'];
    echo "<loc>https://$host/news/detail.php?id={$row['id']}</loc>\n";
    echo "<lastmod>".date('Y-m-d', strtotime($row['add_time']))."</lastmod>\n";
    echo "<changefreq>weekly</changefreq>\n";
    echo "<priority>0.8</priority>\n";
    echo "</url>\n";
}
echo "</urlset>";
?>