const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const app = express();
app.use(cors());
app.use(express.json());

const con = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'password',
    database: 'test'
})


//register
app.post('/Register', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;

    con.query("INSERT INTO users (username, password) VALUES (?,?)", [username, password], (err, result) => {
        if(err) {
            res.send({err: err})
        } else {
            res.send(result)
        }
    })
})

//login
app.post('/Login', (req, res) => {
    const username = req.body.username;
    const password = req.body.password;

    con.query("SELECT * FROM users WHERE username = ? and password = ?", [username, password], (err, result) => {
        if(err) {
            req.setEncoding({err: err})
        } else {
            if(result.length > 0) {
                res.send(result);
            } else {
                res.send({message: "Wrong username/password combination!"});
            }
        }
    })
})


app.listen(3001, () => {
    console.log("running");
})

