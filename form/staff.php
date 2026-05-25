<?php
include('function.php');
if($_SESSION['usertype'] != 'staff')
{
    alert("Access Denied. Redirecting...", $returnpage);
}
//TODO: 
//// add datetouse + loan:daycount
// add book:timestart,timeend
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //TODO: Sanitize and validate input. Add label for form input
    //- check if ${asset}count at least has one > 0

    if(isset($_POST['addrequest']))
    {
        $userid = $_POST['request_userid'];
        $requestid = bin2hex(random_bytes(8));//TODO: Better ID generation
        $submittime = date(' Y-m-d H:i:s', time());
        $reason = $_POST['request_reason'];
        $remark = $_POST['request_remark'];
        $purpose = $_POST['request_purpose'];
        $type = $_POST['request_type'];
        $datetouse = $_POST['request_datetouse'];
        $status = "PENDING_MANAGER_APPROVAL";
        $detailsdata = [];
        $detailsdata['location'] = $_POST['request_location'];
        $detailsdata['datetoreceive'] = $_POST['request_datetoreceive'];
        foreach (array_keys($asset_fields) as $assettype) {
            $value = 'request_' . str_replace(' ', '_', trim(strtolower($assettype))) . '_count';
            $label = str_replace(' ','_',$assettype).'_count';
            if(isset($_POST[$value]) && $_POST[$value] > 0)
            {
                $detailsdata[$label] = $_POST[$value];
            }
        }
        $detailsdata['daycount'] = $_POST['request_daycount'];
        $details = json_encode($detailsdata);
        $stmt = $conn->prepare("INSERT INTO T3_request (T3_requestid,T3T1_userid,T3_submittime,T3_reason,T3_remark,T3_purpose,T3_type,T3_datetouse,T3_status,T3_details) VALUES(?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssss",$requestid,$userid,$submittime,$reason,$remark,$purpose,$type,$datetouse,$status,$details);
        if($stmt->execute())
        {
            $stmt->close();
            alert("Permohonan dihantar.", "staff.php?request");
        }
        else
        {
            alert("Ralat: " . $stmt->error);
        }
    }
    elseif(isset($_POST['updaterequest']))
    {
        // Handle request update
    }
}
$content = '';
$title = 'Staf';
$content .= nav();
if($_GET)
{
    if(isset($_GET['request']) && isset($_GET['new']))
    {
        $content .= requestform();
    }
    else if(isset($_GET['request']) && !empty($_GET['request']))
    {
        // Handle request view by THIS staff
    }
    else if(isset($_GET['request']))
    {
        // Handle request view all (for manager)
        $stmt = $conn->prepare("SELECT * FROM T3_request WHERE T3T1_userid = ?");
        $stmt->bind_param("s", $_SESSION['userid']);
        $stmt->execute();
        $requests = $stmt->get_result();
        ob_start();?>
        <h1>Senarai Permohonan</h1>
        <table class="table table-bordered w-75 m-auto">
            <tr>
                <th>ID Permohonan</th>
                <th>Jenis Permohonan</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Tarikh Hantar</th>
                <th>Butiran</th>
            </tr>
            <?php
            while($r = mysqli_fetch_assoc($requests))
            {?>
            <tr>
                <td><?php echo(e($r['T3_requestid'])); ?></td>
                <td><?php echo(e($r['T3_type'])); ?></td>
                <td><?php echo(e($r['T3_reason'])); ?></td>
                <td><?php echo(e($r['T3_status'])); ?></td>
                <td><?php echo(e($r['T3_submittime'])); ?></td>
                <td><button class="btn btn-info" onclick='alert(`<?php echo(e($r['T3_details'])); ?>`)'>Lihat Butiran</button></td>
            </tr>
            <?php
            }
            ?>
        </table>
        <?php
        $content .= ob_get_clean();
    }
}
else
{
    
}
?>
<?php include('layout.php');?>
<?php
function requestform($requestid = null)
{
    global $conn;
    ob_start();
    if($requestid)
    {
        //* should it be editable or just show status of request?
        // Handle request view by THIS staff
        $submitbtn = ["updaterequest","Kemaskini Permohonan"];
    }
    else
    {
        // Handle request add
        $submitbtn = ["addrequest","Hantar Permohonan"];
    }?>
    <div class="row">
        <div class="col-md-12">
            <h1>Borang Permohonan</h1>
        </div>
    </div>
    <form class="w-75 m-auto" action="staff.php" method="post">
        <input type="hidden" name="request_userid" id="" value="<?php echo(e($_SESSION['userid'])); ?>">
        <div class="row">
            <div class="col-12">
                <label for="request_type">Jenis Permohonan</label>
                <select class="form-control" name="request_type" id="request_type" required>
                    <option value="">-- Sila pilih jenis permohonan --</option>
                    <option value="loan">Peminjaman Alatan Komputer</option>
                    <option value="book">Tempahan Makmal Komputer</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label for="request_purpose">Pengguna</label>
                <select class="form-control" name="request_purpose" id="request_purpose" required>
                    <option value="">--- Sila pilih jenis pengguna---</option>
                    <option value="individual">Individu</option>
                    <option value="department">Bahagian/Jabatan</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label for="request_datetouse">Tarikh Penggunaan</label>
                <input class="form-control" type="date" name="request_datetouse" id="request_datetouse" required>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <label for="request_reason">Tujuan Permohonan</label>
                <input class="form-control" type="text" name="request_reason" id="request_reason" placeholder="Tujuan Permohonan" required>
            </div>
            <div class="col-6">
                <label for="request_remark">Catatan (Jika Perlu)</label>
                <input class="form-control" type="text" name="request_remark" id="request_remark" placeholder="Catatan (Jika Perlu)">
            </div>
        </div>
        <h3>Butiran Permohonan</h3>
        <div id="details" class="row border border-secondary"></div>
        <input type="submit" class="btn btn-primary" name="<?php echo($submitbtn[0]); ?>" value="<?php echo($submitbtn[1]); ?>">
        <script>
            const request_details = document.getElementById('details');
            const request_type = document.getElementById('request_type');
            //*Set date and minimum date to tomorrow
            const datetouse = document.getElementById('request_datetouse');
            setdatetomorrow(datetouse);
            setmindatetomorrow(datetouse);
            request_type.addEventListener('change', (event) => {
                const type = request_type.value;
                request_details.replaceChildren();
                if(type === 'loan')
                {
                    const datetoreceive_label = document.createElement('label');
                    datetoreceive_label.textContent = 'Tarikh untuk Diterima';
                    datetoreceive_label.setAttribute('for','request_datetoreceive');
                    request_details.appendChild(datetoreceive_label);
                    const datetoreceive = document.createElement('input');
                    datetoreceive.setAttribute('type','date');
                    datetoreceive.setAttribute('name','request_datetoreceive');
                    datetoreceive.setAttribute('class','form-control');
                    datetoreceive.setAttribute('required', true);
                    //*Set date and minimum date to tomorrow
                    setdatetomorrow(datetoreceive);
                    setmindatetomorrow(datetoreceive);
                    request_details.appendChild(datetoreceive);

                    const daycount_label = document.createElement('label');
                    daycount_label.textContent = 'Tempoh Peminjaman (Hari)';
                    daycount_label.setAttribute('for','request_daycount');
                    request_details.appendChild(daycount_label);
                    const request_daycount = document.createElement('input');
                    request_daycount.setAttribute('type','number');
                    request_daycount.setAttribute('name','request_daycount');
                    request_daycount.setAttribute('class','form-control');
                    request_details.appendChild(request_daycount);

                    const location_label = document.createElement('label');
                    location_label.textContent = 'Tempat Penggunaan';
                    location_label.setAttribute('for','request_location');
                    request_details.appendChild(location_label);
                    const request_location = document.createElement('input');
                    request_location.setAttribute('type','text');
                    request_location.setAttribute('name','request_location');
                    request_location.setAttribute('class','form-control');
                    request_location.setAttribute('placeholder','Tempat Penggunaan');
                    request_location.setAttribute('required', true);
                    request_details.appendChild(request_location);

                    const assetcount_label = document.createElement('h4');
                    assetcount_label.textContent = 'Bilangan Aset';
                    assetcount_label.setAttribute('class','text-center w-100 margin-auto');
                    request_details.appendChild(assetcount_label);

                    const assetTypes = Object.keys(assetFields);
                    assetTypes.forEach(type => {
                        assetcountinput(type,0);
                    });
                }
                else if(type === 'book')
                {
                    request_details.textContent = 'Sistem Tempahan Makmal Komputer masih dalam pembangunan. Sila hubungi pihak pengurusan untuk tempahan buat masa ini.';
                }
            });
            function assetcountinput(name,value)
            {
                const row = document.createElement('div');
                row.setAttribute('class','w-100 row');
                request_details.appendChild(row);
                const col_left = document.createElement('div');
                col_left.setAttribute('class','col-6');
                row.appendChild(col_left);
                const col_right = document.createElement('div');
                col_right.setAttribute('class','col-6');
                row.appendChild(col_right);
                const label = document.createElement('label');
                label.textContent = name.charAt(0).toUpperCase() + name.slice(1);
                col_left.appendChild(label);
                name = `request_${name.toLowerCase().trim().replace(/\s+/g, '_')}_count`;
                const text = document.createElement('input');
                text.setAttribute('type','number');
                text.setAttribute('name',name);
                text.setAttribute('value',value);
                text.setAttribute('class','form-control');
                col_right.appendChild(text);
                request_details.appendChild(document.createElement('br'));
            }
        </script>
    </form>
    
    <?php
    return ob_get_clean();
}
