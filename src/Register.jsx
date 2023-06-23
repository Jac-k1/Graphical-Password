import React, { useState } from 'react';
import axios from 'axios';


export const Register = (props) => {
    const [username, setUser] = useState('');
    const [password, setPass] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        axios.post('http://localhost:3000/api/register', { username, password })
            .then(res => {
                console.log(res);
                setUser('');
                setPass('');
            })

    }

    return (
        <div className='auth-form-container'>
            <div className='title'>
                <h1>Register</h1>
            </div>
            <form onSubmit={handleSubmit}>
                <label htmlFor="username">Username: </label>
                <input DeafultValue={username} OnChange={(e) => setUser(e.target.value)} type="username" placeholder="username" id="username" name="username"></input>
                <br></br>
                <br></br>
                <label htmlFor="password">Password: </label>
                <input DefaultValue={password} OnChange={(e) => setPass(e.target.value)} type="password" placeholder="******" id="password" name="password"></input>
                <br></br>
                <br></br>
                <button type="submit">Register</button>
            </form>
            <br></br>
            <button onClick={() => props.onFormSwitch('login')}>Already have an Account? Login here.</button>
        </div>
    )
}