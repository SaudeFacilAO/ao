<?php

header('Content-Type: application/json');

require '../../../phpconexao/conexao.php';

$data = json_decode(file_get_contents("php://input"), true);

try{

    $stmt = $pdo->prepare("
        UPDATE solicitacoes_consulta
        SET
            data_desejada = ?,
            especialidade_id = ?,
            estado_solicitacao = 'pendente'
        WHERE id = ?
    ");

    $stmt->execute([
        $data['data_desejada'],
        $data['especialidade'],
        $data['solicitacao_id']
    ]);

    echo json_encode([
        "status"=>"ok"
    ]);

}catch(Exception $e){

    echo json_encode([
        "status"=>"erro",
        "msg"=>$e->getMessage()
    ]);
}