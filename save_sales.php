<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if(isset($_POST['sales']) && isset($_POST['patient_name'])) {
    $sales = json_decode($_POST['sales'], true);
    $patient_name = trim($_POST['patient_name']);

    if(count($sales) == 0 || $patient_name === '') {
        echo json_encode(['status'=>'error','message'=>'Invalid data']);
        exit;
    }

    $totalAmount = 0;
    foreach($sales as $item) {
        $totalAmount += $item['total'];
    }

    try {
        $conn->beginTransaction();

        // Insert into sales table
        $stmt = $conn->prepare("INSERT INTO sales (total_amount, patient_name) VALUES (:total, :patient_name)");
        $stmt->execute([':total' => $totalAmount, ':patient_name' => $patient_name]);
        $sale_id = $conn->lastInsertId();

        // Insert sale items
        $stmtItem = $conn->prepare("INSERT INTO sale_items (sale_id, medicine_id, qty, unit_price, total_price) 
                                    VALUES (:sale_id, :medicine_id, :qty, :unit_price, :total_price)");
        foreach($sales as $item) {
            $stmtItem->execute([
                ':sale_id' => $sale_id,
                ':medicine_id' => $item['medId'],
                ':qty' => $item['qty'],
                ':unit_price' => $item['unitPrice'],
                ':total_price' => $item['total']
            ]);

            // Update medicine stock
            $stmtStock = $conn->prepare("UPDATE medicines SET quantity = quantity - :qty WHERE medicine_id = :mid");
            $stmtStock->execute([':qty' => $item['qty'], ':mid' => $item['medId']]);
        }

        $conn->commit();
        echo json_encode(['status'=>'success','sale_id'=>$sale_id]);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
}
