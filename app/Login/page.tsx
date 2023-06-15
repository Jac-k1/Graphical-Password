import './login.css'

export default function Login() {
    return (
        <body>
            <div className='main'>
                <h1>Welcome to the Login page</h1>

                <form>
                    <p>Username</p>
                    <input type='text'></input>
                    <br></br>
                    <p>Password</p>
                    <input type='password'></input>
                    <br></br>
                    <br></br>
                    <input type='button' value='submit'></input>
                </form>
            </div>
        </body>

    );
}