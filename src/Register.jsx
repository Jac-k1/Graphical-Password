import React, { useState } from 'react';
//import axios from 'axios';

export const Register = (props) => {
    const [username, setUser] = useState('');
    const [password, setPass] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        /*
        if(username === "" && password === ""){
            axios.post()
        }
        */
    }

    return (
        <div className='auth-form-container'>
            <div className='title'>
                <h1>Register</h1>
            </div>
            <form onSubmit={handleSubmit}>
                <label htmlFor="username">Username: </label>
                <input value={username} OnChange={(e) => setUser(e.target.value)} type="username" placeholder="username" id="username" name="username"></input>
                <br></br>
                <br></br>
                <label htmlFor="password">Password: </label>
                <input value={password} OnChange={(e) => setPass(e.target.value)} type="password" placeholder="******" id="password" name="password"></input>
                <br></br>
                <br></br>
                <button type="submit">Register</button>
            </form>
            <br></br>
            <button onClick={() => props.onFormSwitch('login')}>Already have an Account? Login here.</button>
        </div>
    )
}