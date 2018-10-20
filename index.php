<?php

include('baglan.php');
$cikti = '';

/* Sayfalama için blog tablosundaki toplam kayıt sayısını alalım*/
$toplam = $db->query("SELECT count(*) FROM blog");

/* Toplam kayıt sayısını elde edelim*/
$sayfa_sayisi = $toplam->fetch_row();		

/* Sorgu bittiğine göre kapatalım */
$toplam->close();

/* Sayfalama için gereken kayıt sayısı */
$limit = 10;

/* Sayfalama için ?id=1 gelmezse 0 olarak kabul et */
$ofset = isset($_GET['id']) ? $_GET['id'] : 0;

/* Yorum tablosundaki sonuçları limit ve ofset değerine göre alalım */
$blog = $db->prepare("SELECT blog.* ,uye.ad, COUNT( yorum.yorum_id ) AS yorumadet FROM blog LEFT JOIN yorum USING ( blog_id ) LEFT JOIN uye USING(uye_id) GROUP BY blog.blog_id LIMIT ? OFFSET ?");

/* Sorgudaki ? değerleri için tanım yapalım */
$blog->bind_param("ii", $limit, $ofset);

/* Sorguyu çalıştıralım */
$blog->execute();

/* Sonuçları döndürelim */
$blog_sonuc = $blog->get_result();

/* Sonuçları sütun adlarına göre elde edelim */
while($row = $blog_sonuc->fetch_array()){
	$detay = strip_tags($row['yazi']);
	
	/* İçeriğin uzunluğu 50 den büyükse yazı devamı için ayrı bir link oluşturalım */
	if(strlen($detay) >= 150){
		$detay = substr($detay, 0, 150);
		$detay .= "...<br><a href='detay.php?id={$row['blog_id']}' class='btn btn-info btn-xs'>Devamı</a>";
	}
	/* İçeriği ekrana yazdıralım */
	$saat = date('d/m/Y',strtotime($row['tarih']));
	$cikti .= '<h4><small>Yeni Gönderi</small></h4>';
	$cikti .= "<hr><h2><a href='detay.php?id={$row['blog_id']}'>{$row['baslik']}</a></h2>
	<h5><span class='glyphicon glyphicon-time'></span> Ekleyen {$row['ad']} , $saat</h5>
    <h5><span class='label label-danger'>Kişisel</span>
	<span class='badge'>{$row['yorumadet']}</span> yorum var</h5><br>
    <p> $detay </p><br><br>";
}
/* Sayfalama için linkleri oluşturalım */
if($sayfa_sayisi[0] > $limit){
	$x = 0;
	$cikti .= '<ul class="pagination">';
    for ($i = 0; $i < $sayfa_sayisi[0]; $i += $limit) {
        $x++;
		 $active = $ofset+1 == $x ? 'class="active"' : '';
        $cikti .= "<li $active><a href='?id=$i'> $x </a></li>";
 }
 $cikti .= '</ul>';
}
$blog->close();
$db->close();
include('sablon/sablon_old.php');

?>