<?php
// If specific quote ID is provided:
if (isset($_GET['id'])) {
    $quote->id = intval($_GET['id']);
    if ($quote->read_single()) {
        // Output single quote with author and category names
        echo json_encode([
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);  // Quote ID not found
    }
}
// If filtering by both authorId and categoryId:
else if (isset($_GET['authorId']) && isset($_GET['categoryId'])) {
    $quote->authorId = intval($_GET['authorId']);
    $quote->categoryId = intval($_GET['categoryId']);
    $result = $quote->read_by_author_and_category();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $quotesArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotesArr[] = [
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
            ];
        }
        echo json_encode($quotesArr);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
// If filtering by authorId only:
else if (isset($_GET['authorId']) && !isset($_GET['categoryId'])) {
    $quote->authorId = intval($_GET['authorId']);
    $result = $quote->read_by_author();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $quotesArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotesArr[] = [
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
            ];
        }
        echo json_encode($quotesArr);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
// If filtering by categoryId only:
else if (isset($_GET['categoryId']) && !isset($_GET['authorId'])) {
    $quote->categoryId = intval($_GET['categoryId']);
    $result = $quote->read_by_category();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $quotesArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotesArr[] = [
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
            ];
        }
        echo json_encode($quotesArr);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
// No filters, get all quotes:
else {
    $result = $quote->read();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $quotesArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quotesArr[] = [
                'id' => $row['id'],
                'quote' => $row['quote'],
                'author' => $row['author'],
                'category' => $row['category']
            ];
        }
        echo json_encode($quotesArr);
    } else {
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
exit();
