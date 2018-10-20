<?php
include('baglan.php');

/* detay.php?id=1 gibi detay görme isteği varmı? kontrol edelim */
$istek = isset($_GET['id']) ? $_GET['id'] : die('Hatalı istek');
/* Yönetici üye varmı? Bu bilgiyi elde edelim */
$uye  = isset($_SESSION['uye']) ? unserialize($_SESSION['uye']) : null;

/* Blog tablosu ve yorum tablosu için SQL sorgusunu hazırlayalım */
$blog = $db->prepare("SELECT *,blog.blog_id as bid, blog.tarih as btarih,yorum.*,yorum.tarih as ytarih FROM blog LEFT JOIN yorum USING(blog_id) LEFT JOIN uye USING(uye_id) WHERE blog.blog_id = ?");

/* istek varsa sorgudaki ? için  veri tipini ve değişkeni tanımlayalım */
$blog->bind_param("i", $istek);

/* hazırlanan SQL sorgusunu çalıştıralım */
$blog->execute();

/* blog ve yorum tablosunun sonuçlarını döndürelim(mysqlnd) yoksa çalışmaz */
$blog_sonuc = $blog->get_result();

/* kaç adet sonuç var? öğrenelim, yorum sayısı için kullanacağız */
$sonuc_sayisi = $blog_sonuc->num_rows;

/* Bütün sonuçları bir defada elde edelim (mysqlnd) yoksa çalışmaz */
$rows = $blog_sonuc->fetch_all(MYSQLI_ASSOC);

$cikti = '';
/* Önce blok bilgilerini ekrana yazdırmak için hazırlayalım */
if (isset($rows[0])) {
  $yorum_var = (($sonuc_sayisi - 1) == 0 && empty($rows[0]['mesaj'])) ? 0 : $sonuc_sayisi;
  $saat = date('d/m/Y', strtotime($rows[0]['btarih']));
  $cikti .= '<h4><small>Gönderi Detayı</small></h4>';
  $cikti .= "<hr><h2>{$rows[0]['baslik']}</h2>
  <h5><span class='glyphicon glyphicon-time'></span> Ekleyen {$rows[0]['ad']} , $saat</h5>
  <h5><span class='label label-danger'>Kişisel</span>
  <span class='badge'>$yorum_var</span> yorum var</h5><br>
  <p class='icerik'> {$rows[0]['yazi']} </p><br><br style='clear:both'>";
  
  $yorum = '';
  /* Varsa yorumları ekrana yazdıralım */
  if ($yorum_var) {
    foreach ($rows as $row) {
      $yid = $row['yorum_id'];
      $bid = $row['blog_id'];
      $sil = ($uye['durum'] == 1) ? "<a href='admin.php?yorum_sil=$yid&id=$bid'>Sil</a>" : '';
      $yazan = ucfirst(mb_substr($row['yazan'], 0,2,'UTF-8'));	  
      $yorum .= "
		<div class='col-sm-1 text-center'>
		<div style='background:#05C1E0;color:#fff;padding:30px; width:80px; border-radius:40px;'>{$yazan}</div>		
		</div>
		<div class='col-sm-11'> $sil
		<h4>{$row['yazan']} <small>{$row['ytarih']}</small></h4>
		<p>{$row['mesaj']}</p><br></div>";
    }
  }
  /* Yorum ekleme formunu hazırlayalım */
  $cikti .= '
  <hr><h4>Yorum ekle:</h4>
  <form role="form" method="post" action="yorum.php">
  <div class="form-group">
  <input type="hidden" name="blog_id" value="' . $rows[0]['bid'] . '"/>
  Ad Soyad: <br><input type="text" name="yazan" maxlength="10" class="form-control" required>
  </div>
  <div class="form-group">
  <textarea class="form-control" rows="3" required name="mesaj"></textarea>
  </div>
  <input type="submit" class="btn btn-success" name="yorum" value="Gönder">
  </form><br><br>
  <div class="row"><br>' . $yorum . '</div>';
} else {
  $cikti .= '<h3>Maalesef kayıtlı bir içerik bulamadık</h3>';
}
/* Sorguları ve veritabanı bağlantılarını kapatalım */
$blog->close();
$db->close();
include('sablon/sablon_old.php');
?>