import React, { useState } from "react";
import axios from "axios";

export const Login = (props) => {
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [loginStatus, setLoginStatus] = useState("");

    /*
    const handleSubmit = (e) => {
        e.preventDefault();
        console.log(username, password);
    }
    */
    /*
    const login = (e) => {
        e.preventDefault();
        axios.post("http://localhost:3001/Login", {
            username: username,
            password: password
        }).then((response) => {
            if(response.data.message) {
                setLoginStatus(response.data.message);
            } else {
                setLoginStatus(response.data[0].username + ' Logged in Successfully!');
            }
        })
    }
    */

    const login = (e) => {
        e.preventDefault();
        axios
          .post("http://localhost:3001/Login", {
            username: username,
            password: password,
          })
          .then((response) => {
            if (response.data.message) {
              setLoginStatus(response.data.message);
            } else {
              setLoginStatus(response.data[0].username + ' Logged in Successfully!');
            }
          })
          .catch((error) => {
            console.error("Error occurred:", error.message);
          });
      };

    return (
        <div className='auth-form-container'>
            <div className='title'>
                <h1>Login</h1>
            </div>
            <form onSubmit={login}>
                <label htmlFor="username">Username: </label>
                <input defaultValue={username} OnChange={(e) => {setUsername(e.target.value)}} type="username" placeholder="Username" id="username" name="username"></input>
                <br></br>
                <br></br>
                <label htmlFor="password">Password: </label>
                <input defaultValue={password} OnChange={(e) => {setPassword(e.target.value)}} type="password" placeholder="****************" id="password" name="password"></input>
                <br></br>
                <br></br>
                <button type="submit">Login</button>
            </form>
            <br></br>
            <button onClick={() => props.onFormSwitch('register')}>Dont have an Account? Register here.</button>
        </div>
    )
}