import React, { useState } from "react";

export const Login = (props) => {
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log(username, password);
    }

    return (
        <div className='auth-form-container'>
            <div className='title'>
                <h1>Login</h1>
            </div>
            <form onSubmit={handleSubmit}>
                <label htmlFor="username">Username: </label>
                <input defaultValue={username} OnChange={(e) => setUsername(e.target.value)} type="username" placeholder="Username" id="username" name="username"></input>
                <br></br>
                <br></br>
                <label htmlFor="password">Password: </label>
                <input defaultValue={password} OnChange={(e) => setPassword(e.target.value)} type="password" placeholder="****************" id="password" name="password"></input>
                <br></br>
                <br></br>
                <button type="submit">Login</button>
            </form>
            <br></br>
            <button onClick={() => props.onFormSwitch('register')}>Dont have an Account? Register here.</button>
        </div>
    )
}