const express = require('express');
const mysql = require('mysql');

const app = express();
const port = 3000;

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'project_magang'
});

db.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
    } else {
        console.log('Connected to MySQL');
    }
});

app.get('/api/getPeminjamanData', (req, res) => {
    const startDate = req.query.startDate; // Mengambil tanggal dari parameter query

    const query = `SELECT * FROM peminjaman WHERE tanggal_peminjaman >= ?`;
    db.query(query, [startDate], (err, results) => {
        if (err) {
            console.error('Error executing MySQL query:', err);
            res.status(500).json({ error: 'Internal server error' });
        } else {
            res.json(results);
        }
    });
});

app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
