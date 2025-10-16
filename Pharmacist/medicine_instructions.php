<?php
session_start();
include 'connect.php';

// Only pharmacists can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Pharmacist') {
    header("Location: login.php");
    exit();
}

// Pharmacist name
$pharmacist_name = $_SESSION['full_name'];

// CRUD actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'add' || $action == 'update') {
        $medicine_name = trim($_POST['medicine_name']);
        $instruction_lines = $_POST['instruction_lines'] ?? [];

        // Validate and clean instruction lines
        $valid_instructions = [];
        foreach ($instruction_lines as $line) {
            $text = trim($line['text'] ?? '');
            $mark = $line['mark'] ?? '✔';
            if ($text !== '') {
                $valid_instructions[] = ['text'=>$text, 'mark'=>$mark];
            }
        }
        $instructions = !empty($valid_instructions) ? json_encode($valid_instructions) : '';

        if ($action == 'add') {
            // UPDATED: Using 'instruction' column name
            $stmt = $conn->prepare("INSERT INTO medicine_instructions (medicine_name, instruction) VALUES (?, ?)");
            $stmt->execute([$medicine_name, $instructions]);
        } else {
            $id = $_POST['id'];
            // UPDATED: Using 'instruction' column name
            $stmt = $conn->prepare("UPDATE medicine_instructions SET medicine_name=?, instruction=? WHERE id=?");
            $stmt->execute([$medicine_name, $instructions, $id]);
        }

        echo json_encode(['status'=>'success']);
        exit;
    }

    if ($action == 'delete') {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM medicine_instructions WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['status'=>'success']);
        exit;
    }

    if ($action == 'fetch') {
        // UPDATED: Selects 'instruction' column and aliases it as 'instruction_text' 
        // for compatibility with existing JavaScript variable names.
        $stmt = $conn->query("SELECT id, medicine_name, instruction AS instruction_text FROM medicine_instructions ORDER BY id ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pharmacist - Medicine Instructions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; min-height:100vh; display:flex; background-color:#f5f7fa; margin:0; }
.sidebar { width:240px; min-height:100vh; background:#0d6efd; color:#fff; display:flex; flex-direction:column; padding-top:30px; position:fixed; }
.sidebar-profile { text-align:center; margin-bottom:30px; }
.sidebar-profile h3 { color:#fff; margin:0; padding:20px 0; font-weight:600; }
.sidebar a { color:#fff; text-decoration:none; display:flex; align-items:center; padding:12px 20px; border-radius:8px; margin-bottom:5px; transition:0.3s; }
.sidebar a i { margin-right:10px; min-width:20px; text-align:center; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }
.content { margin-left:240px; flex:1; padding:30px; }
.card { border-radius:10px; margin-bottom:15px; background:#fff; border-left:6px solid #dc3545; }
.card-title { color:#28a745; font-weight:bold; }
textarea{resize:none;}
.correct{color:black;} /* SET TO BLACK */
.wrong{color:black;}  /* SET TO BLACK */
.instruction-line { display:flex; gap:10px; margin-bottom:5px; }
.instruction-line input { flex:1; }
.instruction-line select { width:70px; }
</style>
</head>
<body>

<nav class="sidebar">
    <div class="sidebar-profile">
        <h3><i class="bi bi-person-circle"></i> Pharmacist</h3>
    </div>
    <a href="pharmacist_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="manage_medicines.php"><i class="bi bi-pills me-2"></i> Manage Medicines</a>
    <a href="pharmacist_sales.php"><i class="bi bi-cash-stack me-2"></i> Sales</a>
    <a href="purchases.php"><i class="bi bi-truck me-2"></i> Purchases</a>
    <a href="suppliers.php"><i class="bi bi-people me-2"></i> Suppliers</a>
    <a href="pharmacist_medicine_instructions.php" class="active"><i class="bi bi-capsule me-2"></i> Medicine Instructions</a>
    <a href="pharmacist_report.php"><i class="bi bi-bar-chart me-2"></i> Reports</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</nav>

<div class="content">
<h2 class="mb-4">Manage Medicine Instructions</h2>

<div class="card p-3 mb-4">
    <h5>Add Instruction</h5>
    <form id="addInstructionForm">
        <div class="mb-2">
            <input type="text" name="medicine_name" class="form-control" placeholder="Medicine Name" required>
        </div>
        <div id="instructionLines">
            <div class="instruction-line">
                <input type="text" name="instruction_text[]" class="form-control" placeholder="Instruction text" required>
                <select name="instruction_mark[]" class="form-select">
                    <option value="✔">✔</option>
                    <option value="❌">❌</option>
                </select>
                <button type="button" class="btn btn-danger removeLine">-</button>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mb-2" id="addLine">Add Line</button>
        <br>
        <button type="submit" class="btn btn-success">Save Instruction</button>
    </form>
</div>

<h3 class="mb-4"><i class="bi bi-capsule me-2"></i> Medicine Instructions</h3>
<div class="row g-4" id="instructionsDisplay"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    let editId = null;

    function fetchInstructions(){
        $.post('pharmacist_medicine_instructions.php', {action:'fetch'}, function(data){
            let cards = '';
            JSON.parse(data).forEach(inst => {
                let linesHtml = '';
                const instructionsRaw = inst.instruction_text; // The raw value from the database
                
                try {
                    // Try to parse as JSON first
                    JSON.parse(instructionsRaw).forEach(line => {
                        let markClass = line.mark === '✔' ? 'correct' : 'wrong';
                        linesHtml += `<p class="${markClass}">${line.mark} ${line.text}</p>`;
                    });
                } catch(e) {
                    // FIX FOR 'UNDEFINED' START: If JSON fails, check if the raw text is actually valid to display
                    if (instructionsRaw && instructionsRaw.trim() !== '' && instructionsRaw.trim().toLowerCase() !== 'null') {
                        linesHtml = `<p>${instructionsRaw}</p>`;
                    } else {
                        linesHtml = ''; // Set to empty string if no valid instruction exists
                    }
                    // FIX FOR 'UNDEFINED' END
                }
                
                const jsonText = encodeURIComponent(instructionsRaw);
                cards += `<div class="col-md-6">
                    <div class="card p-3 important">
                        <h5 class="card-title">${inst.medicine_name}</h5>
                        ${linesHtml}
                        <button class="btn btn-sm btn-primary editBtn" data-id="${inst.id}" data-name="${inst.medicine_name}" data-text="${jsonText}"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${inst.id}"><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </div>`;
            });
            $('#instructionsDisplay').html(cards);
        });
    }

    fetchInstructions();

    $('#addLine').click(function(){
        $('#instructionLines').append(`
            <div class="instruction-line">
                <input type="text" name="instruction_text[]" class="form-control" placeholder="Instruction text" required>
                <select name="instruction_mark[]" class="form-select">
                    <option value="✔">✔</option>
                    <option value="❌">❌</option>
                </select>
                <button type="button" class="btn btn-danger removeLine">-</button>
            </div>
        `);
    });

    $(document).on('click','.removeLine', function(){
        $(this).parent().remove();
    });

    $('#addInstructionForm').submit(function(e){
        e.preventDefault();
        let texts = $('input[name="instruction_text[]"]').map(function(){return $(this).val();}).get();
        let marks = $('select[name="instruction_mark[]"]').map(function(){return $(this).val();}).get();
        let instructions = texts.map((t,i)=>({text:t, mark:marks[i]}));
        const actionType = editId ? 'update' : 'add';

        $.post('pharmacist_medicine_instructions.php',{
            action: actionType,
            id: editId,
            medicine_name:$('input[name="medicine_name"]').val(),
            instruction_lines: instructions
        }, function(){
            $('#addInstructionForm')[0].reset();
            $('#instructionLines').html(`<div class="instruction-line">
                <input type="text" name="instruction_text[]" class="form-control" placeholder="Instruction text" required>
                <select name="instruction_mark[]" class="form-select">
                    <option value="✔">✔</option>
                    <option value="❌">❌</option>
                </select>
                <button type="button" class="btn btn-danger removeLine">-</button>
            </div>`);
            editId = null;
            $('#cancelEdit').remove();
            fetchInstructions();
        });
    });

    $(document).on('click','.deleteBtn', function(){
        if(confirm("Are you sure to delete?")){
            $.post('pharmacist_medicine_instructions.php',{action:'delete', id:$(this).data('id')}, fetchInstructions);
        }
    });

    $(document).on('click','.editBtn', function(){
        editId = $(this).data('id');
        const name = $(this).data('name');
        const text = decodeURIComponent($(this).data('text'));
        let lines = [];
        try { lines = JSON.parse(text); } catch(e){
            // If the old data is not JSON, put it into a single line object for editing
            if (text && text.trim() !== '' && text.trim().toLowerCase() !== 'null') {
                lines = [{text: text, mark: '✔'}]; // Default to correct mark
            }
        }

        $('input[name="medicine_name"]').val(name);
        $('#instructionLines').html('');
        
        // If lines is still empty, add one default line
        if (lines.length === 0) {
            lines = [{text: '', mark: '✔'}];
        }
        
        lines.forEach(line => {
            $('#instructionLines').append(`
                <div class="instruction-line">
                    <input type="text" name="instruction_text[]" class="form-control" value="${line.text}" required>
                    <select name="instruction_mark[]" class="form-select">
                        <option value="✔" ${line.mark==='✔'?'selected':''}>✔</option>
                        <option value="❌" ${line.mark==='❌'?'selected':''}>❌</option>
                    </select>
                    <button type="button" class="btn btn-danger removeLine">-</button>
                </div>
            `);
        });

        if($('#cancelEdit').length===0){
            $('#addInstructionForm').append('<button type="button" id="cancelEdit" class="btn btn-secondary mt-2">Cancel Edit</button>');
        }
    });

    $(document).on('click','#cancelEdit', function(){
        editId = null;
        $('#addInstructionForm')[0].reset();
        $('#instructionLines').html(`<div class="instruction-line">
            <input type="text" name="instruction_text[]" class="form-control" placeholder="Instruction text" required>
            <select name="instruction_mark[]" class="form-select">
                <option value="✔">✔</option>
                <option value="❌">❌</option>
            </select>
            <button type="button" class="btn btn-danger removeLine">-</button>
        </div>`);
        $(this).remove();
    });
});
</script>
</body>
</html>
