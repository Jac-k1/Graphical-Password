const express = require('express');
const mysql = require('mysql');
const app = express();
const bodyParser = require('body-parser');
const cors = require('cors');

app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());
app.use(cors());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'password',
    database: 'test'
});

app.post('/register', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;
    db.query('INSERT INTO users (username, password) VALUES (?, ?)', [username, password],
    (err, result) => {
        if(err) {
            console.log(err);
        } else {
            res.send("Values Inserted");
        }
    });
}); 