<?php

namespace App\Models;

use SQLite3;

class QuoteModel
{
    private SQLite3 $db;

    public function __construct()
    {
        $this->db = new SQLite3(__DIR__ . '/../../storage/database/quotes.db');
        $this->initializeTable();
    }

    /**
     * Initializes the "quotes" table in the database if it doesn't exist.
     * @return void
     */
    private function initializeTable(): void
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS quotes (
            id INTEGER PRIMARY KEY,
            car TEXT,
            glass TEXT,
            vendor TEXT,
            price INTEGER,
            delivery_time TEXT,
            warranty TEXT,
            final_price INTEGER,
            delivery_price INTEGER,
            date TEXT
        )");
    }
    /**
     * Saves a quote to the database.
     *
     * @param array $quoteData An associative array containing the following keys:
     *                         - 'car': The car information (JSON encoded).
     *                         - 'glass': The glass type.
     *                         - 'vendor': The vendor name.
     *                         - 'price': The price of the quote.
     *                         - 'delivery_time': The delivery time of the quote.
     *                         - 'warranty': The warranty of the quote.
     *                         - 'delivery_price': The delivery price of the quote.
     *                         - 'final_price': The final price of the quote.
     * @return bool Returns true if the quote was successfully saved, false otherwise.
     */
    public function saveQuote(array $quoteData): bool
    {
        $stmt = $this->db->prepare("INSERT INTO quotes (car, glass, vendor, price, delivery_time, warranty, final_price, delivery_price, date) 
                                    VALUES (:car, :glass, :vendor, :price, :delivery_time, :warranty, :final_price, :delivery_price, :date)");
        $stmt->bindValue(':car', json_encode($quoteData['car']), SQLITE3_TEXT);
        $stmt->bindValue(':glass', $quoteData['glass'], SQLITE3_TEXT);
        $stmt->bindValue(':vendor', $quoteData['vendor'], SQLITE3_TEXT);
        $stmt->bindValue(':price', $quoteData['price'], SQLITE3_INTEGER);
        $stmt->bindValue(':delivery_time', $quoteData['delivery_time'], SQLITE3_TEXT);
        $stmt->bindValue(':warranty', $quoteData['warranty'], SQLITE3_TEXT);
        $stmt->bindValue(':delivery_price', $quoteData['delivery_price'], SQLITE3_INTEGER);
        $stmt->bindValue(':final_price', $quoteData['final_price'], SQLITE3_INTEGER);
        $stmt->bindValue(':date', date('Y-m-d H:i:s'), SQLITE3_TEXT);

        return $stmt->execute() !== false;
    }
}
