<?php
include('baglan.php');
$cikti = '<h1>Arama Sonuçları</h1>';
if (!empty($_GET['ara'])) {
  $ara  = "%{$_GET['ara']}%";
  $blog = $db->prepare("SELECT * FROM blog WHERE baslik LIKE ? OR yazi LIKE ?");
  $blog->bind_param("ss", $ara, $ara);
  $blog->execute();
  
  /* Sonuçları elde edelim (mysqlnd) yoksa çalışmaz */
  $blog_sonuc = $blog->get_result();
  if ($blog_sonuc->num_rows == 0) {
    $cikti = '<h3>Aradığınız bilgi bizde yok</h3>';
  }
  
  while ($row = $blog_sonuc->fetch_array()) {
    $detay = strip_tags($row['yazi']);
    /* içeriğin uzunluğu 50 den büyük ise detay için link oluşturalım */
    if (strlen($detay) >= 150) {
      $detay = substr($detay, 0, 150);
      $detay .= "...<br><a href='detay.php?id={$row['blog_id']}' class='btn btn-info btn-xs'>Devamı</a>";
    }
    /* içeriği ekrana yazdırmak için hazırlayalım */
    $saat = date('d/m/Y', strtotime($row['tarih']));
    $cikti .= "<hr><h2><a href='detay.php?id={$row['blog_id']}'>{$row['baslik']}</a></h2>
    <h5><span class='glyphicon glyphicon-time'></span> $saat </h5><br>
    <p> $detay </p><br><br>";
  }
}
include('sablon/sablon_old.php');