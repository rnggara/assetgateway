<?php 

include '../config/setting.php';

$sql = "SELECT id from do_server order by id desc limit 1";
$res = $db->Execute($sql);
if($res){
    if(!empty($res->fields("id"))){
        // $url = "http://cypher.vesselholding.com/cypher4/public/api/assetgateway/do/".$res->fields("id");
        $url = "http://localhost/cypher4/public/api/assetgateway/do/".$res->fields("id");
        $content = file_get_contents($url);
        $js = json_decode($content, true);
        if($js['success']){
            $data = $js['data'];
            foreach ($data as $key => $value) {
                $sql = "INSERT INTO do_server (";
                foreach($value as $field => $val){
                    if($field != "detail"){
                        $sql .= "$field, ";
                    }
                }
                $sql = trim($sql, ", ");
                $sql .= ") VALUES (";
                foreach($value as $field => $val){
                    if($field != "detail"){
                        if(empty($val)){
                            $sql .= "NULL, ";
                        } else {
                            $sql .= "'$val', ";
                        }
                    }
                }
                $sql = trim($sql, ", ");
                $sql .= ");";
                $db->Execute($sql);

                foreach($value['detail'] as $detail){
                    $sqlDetail = "INSERT INTO do_detail_server (";
                    foreach($detail as $field => $val){
                        $sqlDetail .= "$field, ";
                    }
                    $sqlDetail = trim($sqlDetail, ", ");
                    $sqlDetail .= ") VALUES (";
                    foreach($detail as $field => $val){
                        if(empty($val)){
                            $sqlDetail .= "NULL, ";
                        } else {
                            $sqlDetail .= "'$val', ";
                        }
                    }
                    $sqlDetail = trim($sqlDetail, ", ");
                    $sqlDetail .= ");";
                    $db->Execute($sqlDetail);
                }
            }

            $sDo = "SELECT id FROM do_server order by id desc limit 1";
            $rDo = $db->Execute($sDo);
            $db->Execute("SELECT * FROM last_id_do_server");
            if($rDo->RecordCount() > 0){
                $last_do_server = $rDo->fields('id');
                $db->Execute("UPDATE last_id_do_server SET id_do = $last_do_server");
            } else {
                $last_do_server = 0;
                $db->Execute("INSERT INTO last_id_do_server VALUES ($last_do_server)");
            }
        }

        $rlast = $db->Execute("SELECT id_do FROM last_id_do_server");
        $id_last_do = $rlast->fields("id_do");

        //do_local
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        $sql = "SELECT * from do where no_do is null and deleted_at is null";
        $res = $db->Execute($sql);
        $count = $res->RecordCount();
        if($count > 0){
            $_data = [];
            foreach($res as $row){
                $qDetail = "SELECT * FROM do_detail where do_id = ".$row['id'];
                $rDetail = $db->Execute($qDetail);
                $detail = [];
                foreach($rDetail as $det){
                    $detail[] = $det;
                }
                $row['detail'] = $detail;
                $_data[] = $row;
            }
            $query = [];
            $array_bln = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
            foreach($_data as $value){

                $last_no_do = "select cast(left(no_do, 3) as unsigned ) as num, no_do from do_server where company_id = 1 order by num desc limit 1;";
                $reslast = $db->Execute($last_no_do);
                $lastCount = $reslast->RecordCount();
                $num = "001";
                if($lastCount > 0){
                    $num = sprintf("%03d", $reslast->fields("num") + 1);
                }

                $bln = $array_bln[date("n", strtotime($value['deliver_date']))];
                $yr = date("y", strtotime($value['deliver_date']));
                $no_do = "$num/VESSEL/DO/$bln/$yr";

                $sql = "INSERT INTO do_server (";
                foreach($value as $field => $val){
                    if($field != "id" && $field != "detail" && $field != "no_do_local"){
                        $sql .= "$field, ";
                    }
                }
                $sql = trim($sql, ", ");
                $sql .= ") VALUES (";
                foreach($value as $field => $val){
                    if($field != "id" && $field != "detail" && $field != "no_do_local"){
                        if($field == "no_do"){
                            $sql .= "'$no_do', ";
                        } elseif($field == "company_id"){
                            $sql .= "1, ";
                        } else {
                            if(empty($val)){
                                $sql .= "NULL, ";
                            } else {
                                $sql .= "'$val', ";
                            }
                        }
                    }
                }
                $sql = trim($sql, ", ");
                $sql .= ");";
                $db->Execute($sql);
                $query[] = $sql;
                $last_id = $db->insert_Id();
                foreach ($value['detail'] as $detail) {
                    $sqlDetail = "INSERT INTO do_detail_server (";
                    foreach($detail as $field => $val){
                        if($field != "id"){
                            $sqlDetail .= "$field, ";
                        }
                    }
                    $sqlDetail = trim($sqlDetail, ", ");
                    $sqlDetail .= ") VALUES (";
                    foreach($detail as $field => $val){
                        if($field != "id"){
                            if($field == "do_id"){
                                $sqlDetail .= "$last_id, ";
                            } else {
                                if(empty($val)){
                                    $sqlDetail .= "NULL, ";
                                } else {
                                    $sqlDetail .= "'$val', ";
                                }
                            }
                        }
                    }
                    $sqlDetail = trim($sqlDetail, ", ");
                    $sqlDetail .= ");";
                    $query[] = $sqlDetail;
                    $db->Execute($sqlDetail);
                    // $db->Execute($sqlDetail);
                }

                $db->Execute("UPDATE do SET no_do = '$no_do' WHERE id = ".$value['id']);
                // $db->Execute($sql);
                // $last_id = $db->insert_Id();
            }
        }


        $sql = "SELECT * from do_server where id > $id_last_do";
        $rsql = $db->Execute($sql);
        if($rsql->RecordCount() > 0){
            $data = [];
            foreach($rsql as $item){
                $detail = $db->Execute("SELECT * from do_detail_server WHERE do_id = ".$item['id']);
                $child = [];
                foreach($detail as $val){
                    unset($val['id']);
                    $child[] = $val;
                }
                $item['detail'] = $child;
                $data[] = $item;
            }

            $url = 'http://localhost/cypher4/public/api/assetgateway/do/insert';

            $post = [
                "do" => $data
            ];

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($post));
            
            try {
                $result = curl_exec($ch);
                
                $js = json_decode($result, true);

                curl_close($ch);

                if($js['success']){
                    $db->Execute("UPDATE last_id_do_server set id_do = ".$js['data'].", last_update = '".date("Y-m-d H:i")."'");
                    $success = 1;
                } else {
                    $success = 0;
                }

                $response = [
                    "success" => $success,
                    "message" => $result
                ];
                echo json_encode($response);
            } catch (\Throwable $th) {
                $data = [
                    "success" => 0,
                    "message" => $th->getMessage()
                ];
                echo json_encode($data);
            }
        } else {
            $data = [
                "success" => 0,
                "id" => NULL
            ];
            echo json_encode($data);
        }
    } else {
        $data = [
            "success" => 0,
            "id" => NULL
        ];
        echo json_encode($data);
    }
} else {
    $data = [
        "success" => 0,
        "id" => NULL
    ];
    echo json_encode($data);
}

?>