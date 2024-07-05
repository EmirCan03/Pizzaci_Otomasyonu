<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
</head>
<body>
    <header>    <!--sayfanın üst kısmında yer alan başlık bölümünü tanımlar.-->
        <div class="flex">  <!--başlık bölümündeki öğeleri yatay olarak hizalamak için bir esnek (flex) kutu oluşturur.-->
            <a href="" class="logo">gurme lezzetler</a> <!--web sitesinin logosunu içeren bir bağlantıdır. Boş href değeri, bu bağlantının tıklanabilir olduğunu ancak henüz yönlendireceği bir adres olmadığını belirtir.-->
            <div class="navbar">    <!-- navigasyon bağlantılarını içeren bir bölümdür.-->
                <a href="admin.php">ürünleri görüntüle</a>  <!-- yönetici paneline yönlendiren bir bağlantıdır.-->
                <a href="products.php">mağaza</a>   <!--mağaza paneline yönlendiren bir bağlantıdır-->
            </div>
            <?php 
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
            ?>
            <a href="cart.php" class="cart"><i class="bi bi-cart-check-fill"></i><span><?php echo $row_count;?></span></a> <!--alışveriş sepetine yönlendiren bir bağlantıdır. Sepet simgesi ve sepetteki ürün sayısını gösteren bir sayı içerir -->
        </div>
    </header>
</body>
</html>