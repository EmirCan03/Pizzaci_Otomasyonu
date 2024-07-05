<?php
    include 'connection.php';
    // ürün silme
    if(isset($_GET['delete'])){
        $delete_id=$_GET['delete'];
        $delete_query=mysqli_query($conn, "DELETE FROM `products` WHERE id=$delete_id") or die('query failed');
        if($delete_query){
            $messge[]='Ürün silme başarılı';
        }else{
            $messge[]='Ürün silme başarısız';
        }
        
    }

    //ürün güncelleme
    if(isset($_POST['update_product'])){
        $update_p_id = $_POST['update_p_id'];
        $update_p_name = $_POST['update_p_name'];
        $update_p_price = $_POST['update_p_price'];
        $update_p_img = $_FILES['update_p_image']['name'];
        $update_p_img_tmp_name = $_FILES['update_p_image']['tmp_name'];
        $update_p_folder = 'image/'.$update_p_img;

        $update_query = mysqli_query($conn, "UPDATE `products` SET `id` = '$update_p_id',`name`='$update_p_name',`price`='$update_p_price',`image`='$update_p_img' WHERE `id`= '$update_p_id'") or die('query failed');
        if($update_query){
            move_uploaded_file($update_p_img_tmp_name,$update_p_folder);
            $messge[]='ürün güncellemesi başarılı';
            header('location:admin.php');
        }else{
            $messge[]='ürün güncelleme başarısız';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">  <!-- alışveriş sepeti iconu-->
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Paneli</title>
</head>
<body>

<header>
        <div class="flex">  <!--başlık bölümündeki öğeleri yatay olarak hizalamak için bir esnek (flex) kutu oluşturur.-->
            <a href="" class="logo">gurme lezzetler-Admin Paneli</a> <!--web sitesinin logosunu içeren bir bağlantıdır. Boş href değeri, bu bağlantının tıklanabilir olduğunu ancak henüz yönlendireceği bir adres olmadığını belirtir.-->
            <div class="navbar">    <!-- navigasyon bağlantılarını içeren bir bölümdür.-->
            </div>
            <?php 
                $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die ('query failed');
                $row_count = mysqli_num_rows($select_rows);
            ?>
        
        </div>
    </header>

    <?php
        if(isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                        <span>'.$messge.'<i class="bi bi-x"
                            onclick="this.parentElement.style.display=\'none\'"></i></span>
                    </div>'       
                ;
            }
        }
    ?>
    <a href="product_form.php" class="add">+</a>    <!--+ işaretine basınca ürün ekleme sayfasına yönlendiriyor-->
    <section class="show-product">
        <table>
            <thead>     <!--Tablonun başlık satırlarını içerir-->
                <th>Ürün Resmi</th>  <!--Tablonun başlık hücrelerini tanımlar-->
                <th>Ürün İsmi</th>
                <th>Ürün Fiyatı</th>
                <th>Aksiyon</th>
            </thead>
            <tbody> <!--Tablonun gövdesini içer-->
                <?php
                   $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                    if (mysqli_num_rows($select_products)>0) {
                        while($row = mysqli_fetch_assoc($select_products)){ /* Eğer ürünler varsa, her bir ürünü döngü ile işler ve tablo satırları oluşturur */

                       
                ?>
                <tr>
                    <td><img src="image/<?php echo $row['image'];?>"></td>   <!--Ürün resmini gösterir.-->
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['price'];?>₺/-</td>
                    <td>
                        <a href="admin.php?delete=<?php echo $row['id'];?>" class="delete-btn"><i class="bi bi-trash" onclick="return confirm('Bu ürünü silmek istediğinizden emin misiniz?')"></i>sil</a>
                        <a href="admin.php?edit=<?php echo $row['id'];?>" class="option-btn"><i class="bi bi-pencil"></i>düzenle</a>
                    </td>
                </tr>
                <?php
                        }
                    }
                ?>

            </tbody>
        </table>
    </section>
    <section class="edit-form">
                    <?php 
                        if(isset($_GET['edit'])){
                            $edit_id=$_GET['edit'];
                            $edit_query=mysqli_query($conn, "SELECT * FROM `products` WHERE id=$edit_id") or die('query failed');
                            if(mysqli_num_rows($edit_query) > 0){
                                while($fetch_edit = mysqli_fetch_assoc($edit_query)){
                    ?>
                    <form method="post" enctype="multipart/form-data">
                        <h3>Ürün Güncelle</h3>
                        <img src="image/<?php echo $fetch_edit['image'];?>">
                        <input type="hidden" name ="update_p_id" value="<?php echo $fetch_edit['id'];?>">
                        <input type="text" name="update_p_name" value="<?php echo $fetch_edit['name'];?>" required>
                        <input type="number" name="update_p_price" min="0" value="<?php echo $fetch_edit['price'];?>" required>
                        <input type="file" name="update_p_image" accept="image/png,image/jpg,image/jpeg" required>
                        <input type="submit" name="update_product" value="Ürünü Güncelle" class="btn update">
                        <input type="reset" value="İptal" class="btn cancle" id="close-edit">
                    </form>
                    <?php                
                                }
                            }
                            echo "<script>document.querySelector('.edit-form').style.display='block'</script>";//.edit-form sınıfına sahip HTML elementinin görünür olmasını sağlar.
                        }
                    ?>
    </section>
    <script type="text/javascript">
        const closeBtn=document.querySelector('#close-edit');   // closeBtn adlı bir değişken oluşturuluyor ve '#close-edit' id'sine sahip olan HTML elementini seçiyor

        closeBtn.addEventListener('click',()=>{                 // closeBtn elementine bir tıklama (click) olay dinleyicisi ekleniyor
            document.querySelector('.edit-form').style.display='none' // '.edit-form' sınıfına sahip olan HTML elementinin stilini değiştiriyor ve görünmez yapıyor
        })
    </script>
</body>
</html>