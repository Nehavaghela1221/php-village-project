<?php
require 'config/db.php';

$serial_no = $_GET['serial_no'] ?? '';
if ($serial_no=='') die("Invalid Serial No");

/* MAIN MEMBER */
$stmt = $conn->prepare("SELECT * FROM members WHERE serial_no=?");
$stmt->bind_param("s",$serial_no);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
if(!$member) die("Member not found");

/* FAMILY */
$fam = $conn->prepare("SELECT * FROM family_members WHERE member_serial_no=?");
$fam->bind_param("s",$serial_no);
$fam->execute();
$family = $fam->get_result();

/* FORM SUBMIT */
if(isset($_POST['update_member'])){

    $photo = $member['photo'];
    if(!empty($_FILES['photo']['name'])){
        $photo = time().'_'.$_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../uploads/".$photo);
    }

    /* STORE PENDING DATA */
    $data = [
        'full_name'=>$_POST['full_name'],
        'dob'=>$_POST['dob'],
        'address'=>$_POST['address'],
        'area'=>$_POST['area'],
        'city'=>$_POST['city'],
        'phone'=>$_POST['phone'],
        'shakh'=>$_POST['shakh'],
        'samaj'=>$_POST['samaj'],
        'family_no'=>$_POST['family_no'],
        'marriage_status'=>$_POST['marriage_status'],
        'occupation'=>$_POST['occupation'],
        'business_address'=>$_POST['business_address'],
        'status'=>$_POST['status'],
        'photo'=>$photo
    ];

    $json = json_encode($data);

    $up = $conn->prepare("
        UPDATE members 
        SET pending_data=?, update_status='pending'
        WHERE serial_no=?
    ");
    $up->bind_param("ss",$json,$serial_no);
    $up->execute();

    /* ADD FAMILY */
    if(!empty($_POST['fam_name'])){
        $f = $conn->prepare("
            INSERT INTO family_members 
            (member_serial_no,name,relation,dob,phone)
            VALUES (?,?,?,?,?)
        ");
        $f->bind_param(
            "sssss",
            $serial_no,
            $_POST['fam_name'],
            $_POST['fam_relation'],
            $_POST['fam_dob'],
            $_POST['fam_phone']
        );
        $f->execute();
    }

    echo "<p style='color:orange'>⏳ Update sent for admin approval</p>";
}

/* PHOTO */
$photoSrc = "../uploads/no-user.png";
if(!empty($member['photo']) && file_exists("../uploads/".$member['photo'])){
    $photoSrc = "../uploads/".$member['photo'];
}
?>

<h3>Edit Member</h3>

<?php
if($member['update_status']=='pending'){
 echo "<p style='color:red'>⏳ Admin approval pending</p>";
}
if($member['update_status']=='approved'){
 echo "<p style='color:green'>✅ Update approved</p>";
}
?>

<form method="POST" enctype="multipart/form-data">
<img src="<?= $photoSrc ?>" width="100"><br><br>
<input type="file" name="photo"><br><br>

<input name="full_name" value="<?= $member['full_name'] ?>"><br>
<input type="date" name="dob" value="<?= $member['dob'] ?>"><br>
<input name="address" value="<?= $member['address'] ?>"><br>
<input name="area" value="<?= $member['area'] ?>"><br>
<input name="city" value="<?= $member['city'] ?>"><br>
<input name="phone" value="<?= $member['phone'] ?>"><br>
<input name="shakh" value="<?= $member['shakh'] ?>"><br>
<input name="samaj" value="<?= $member['samaj'] ?>"><br>
<input name="family_no" value="<?= $member['family_no'] ?>"><br>
<input name="marriage_status" value="<?= $member['marriage_status'] ?>"><br>
<input name="occupation" value="<?= $member['occupation'] ?>"><br>
<input name="business_address" value="<?= $member['business_address'] ?>"><br>

<select name="status">
<option value="pending">Pending</option>
<option value="approved">Approved</option>
</select><br><br>

<h4>Family Members</h4>
<?php while($f=$family->fetch_assoc()): ?>
<?= $f['name'] ?> (<?= $f['relation'] ?>)<br>
<?php endwhile; ?>

<h4>Add Family</h4>
<input name="fam_name" placeholder="Name"><br>
<input name="fam_relation" placeholder="Relation"><br>
<input type="date" name="fam_dob"><br>
<input name="fam_phone" placeholder="Phone"><br><br>

<button name="update_member">Submit</button>
</form>
