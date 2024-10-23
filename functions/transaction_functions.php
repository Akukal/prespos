<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/product_functions.php';

function createTransaction($db, $items, $total_price, $payment_method) {
    try {
        $db->beginTransaction();
        $query = "INSERT INTO tb_history (total_price, payment_method, date) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([$total_price, $payment_method]);
        $transaction_id = $db->lastInsertId();

        foreach ($items as $item) {
            updateProductStock($db, $item['id'], $item['quantity']);
        }

        $db->commit();
        return $transaction_id;
    } catch (Exception $e) {
        $db->rollBack();
        error_log($e->getMessage());
        return false;
    }
}

function getAllTransactions($db) {
    return executeQuery($db, "SELECT * FROM tb_history ORDER BY date DESC")->fetchAll();
}

function getTransactionById($db, $id) {
    return executeQuery($db, "SELECT * FROM tb_history WHERE id_transaction = ?", [$id])->fetch();
}

function getDailyIncome($db, $date = null) {
    $date = $date ?: date('Y-m-d');
    $result = executeQuery($db, "SELECT SUM(total_price) as daily_income FROM tb_history WHERE DATE(date) = ?", [$date])->fetch();
    return $result['daily_income'] ?: 0;
}

function getDailyTransactionCount($db, $date = null) {
    $date = $date ?: date('Y-m-d');
    return executeQuery($db, "SELECT COUNT(*) as count FROM tb_history WHERE DATE(date) = ?", [$date])->fetch()['count'];
}

function getTransactionsByDateRange($db, $start_date, $end_date) {
    return executeQuery($db, "SELECT * FROM tb_history WHERE DATE(date) BETWEEN ? AND ? ORDER BY date DESC", [$start_date, $end_date])->fetchAll();
}

function getTotalIncomeByDateRange($db, $start_date, $end_date) {
    $result = executeQuery($db, "SELECT SUM(total_price) as total_income FROM tb_history WHERE DATE(date) BETWEEN ? AND ?", [$start_date, $end_date])->fetch();
    return $result['total_income'] ?: 0;
}

function getMostUsedPaymentMethod($db) {
    return executeQuery($db, "SELECT payment_method, COUNT(*) as count FROM tb_history GROUP BY payment_method ORDER BY count DESC LIMIT 1")->fetch();
}
